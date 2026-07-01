<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Throwable;

class PaymentController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────────
    // Product definition
    // Change these constants to match your actual product/price.
    // In a real app, these would come from a Product model or route parameters.
    // ──────────────────────────────────────────────────────────────────────────
    private const PRODUCT_NAME = 'Premium Membership';
    private const AMOUNT       = '9.99';
    private const CURRENCY     = 'USD';

    // ──────────────────────────────────────────────────────────────────────────
    // Pages
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Show the checkout / product page.
     * GET /payment/checkout
     */
    public function checkout(): View
    {
        return view('payment.checkout', [
            'product'  => self::PRODUCT_NAME,
            'amount'   => self::AMOUNT,
            'currency' => self::CURRENCY,
        ]);
    }

    /**
     * Show the user's payment history.
     * GET /payment/history
     */
    public function history(Request $request): View
    {
        $payments = $request->user()
            ->payments()                    // uses HasMany defined on User model
            ->latest()
            ->paginate(10);

        return view('payment.history', compact('payments'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // PayPal Flow — 3 steps
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * STEP 1 — Create a PayPal order and redirect the user to PayPal.
     *
     * What happens here:
     * 1. We call PayPal's Orders API to create an order for our product.
     * 2. PayPal returns an order ID and an approval URL.
     * 3. We save a 'pending' payment record in our DB with the PayPal order ID.
     * 4. We redirect the user to PayPal's approval URL.
     *
     * POST /payment/create
     */
    public function create(Request $request): RedirectResponse
    {
        try {
            $provider = $this->getPayPalProvider();

            // Build the order payload using PayPal REST API v2 format.
            // This tells PayPal: "I want to collect $9.99 USD for Premium Membership"
            $response = $provider->createOrder([
                'intent' => 'CAPTURE', // CAPTURE = charge immediately on approval

                'application_context' => [
                    'return_url'          => route('payment.success'), // where PayPal sends user after approval
                    'cancel_url'          => route('payment.cancel'),  // where PayPal sends user on cancel
                    'brand_name'          => config('app.name'),
                    'user_action'         => 'PAY_NOW',                // button shows "Pay Now" not "Continue"
                    'shipping_preference' => 'NO_SHIPPING',            // digital product — no shipping address
                ],

                'purchase_units' => [[
                    'reference_id' => 'default',
                    'description'  => self::PRODUCT_NAME,
                    'amount'       => [
                        'currency_code' => self::CURRENCY,
                        'value'         => self::AMOUNT,
                    ],
                ]],
            ]);

            // PayPal returns a unique order ID like "5O190127TN364715T"
            $paypalOrderId = $response['id'] ?? null;

            if (! $paypalOrderId || ($response['status'] ?? null) !== 'CREATED') {
                throw new \RuntimeException('PayPal order creation failed: ' . json_encode($response));
            }

            // Save a PENDING record immediately.
            // We record it now so we can track even abandoned checkouts.
            $request->user()->payments()->create([
                'paypal_order_id' => $paypalOrderId,
                'description'     => self::PRODUCT_NAME,
                'amount'          => self::AMOUNT,
                'currency'        => self::CURRENCY,
                'status'          => 'pending',
            ]);

            // Find the PayPal approval URL in the response links array.
            // PayPal returns multiple links; we need the one with rel="approve".
            $approvalUrl = collect($response['links'] ?? [])
                ->firstWhere('rel', 'approve')['href'] ?? null;

            if (! $approvalUrl) {
                throw new \RuntimeException('PayPal approval URL not found in response.');
            }

            // Redirect user to PayPal to log in and approve the payment.
            return redirect()->away($approvalUrl);

        } catch (Throwable $e) {
            report($e); // log the full exception
            return redirect()->route('payment.checkout')
                ->with('error', 'Could not connect to PayPal. Please try again.');
        }
    }

    /**
     * STEP 2 — Capture the payment after the user approves on PayPal.
     *
     * What happens here:
     * 1. PayPal redirects user back here with ?token=<paypal_order_id>
     * 2. We call PayPal's capture endpoint to actually charge the user.
     * 3. PayPal confirms the charge and returns the payer's email.
     * 4. We update our DB record to 'completed'.
     * 5. We show the success page.
     *
     * GET /payment/success
     */
    public function success(Request $request): View|RedirectResponse
    {
        // 'token' in the query string IS the PayPal order ID
        $paypalOrderId = $request->query('token');

        if (! $paypalOrderId) {
            return redirect()->route('payment.checkout')
                ->with('error', 'Invalid payment session.');
        }

        // Find our pending record in the DB.
        $payment = $request->user()
            ->payments()
            ->where('paypal_order_id', $paypalOrderId)
            ->where('status', 'pending')
            ->first();

        if (! $payment) {
            // Already processed or not found — redirect to history.
            return redirect()->route('payment.history')
                ->with('error', 'Payment not found or already processed.');
        }

        try {
            $provider = $this->getPayPalProvider();

            // CAPTURE — this is the call that actually moves the money.
            $response = $provider->capturePaymentOrder($paypalOrderId);

            // A successful capture returns status = "COMPLETED"
            if (($response['status'] ?? null) === 'COMPLETED') {

                // Extract the buyer's email from the nested response structure.
                $payerEmail = $response['payer']['email_address'] ?? null;

                // Update our DB record.
                $payment->update([
                    'status'      => 'completed',
                    'payer_email' => $payerEmail,
                ]);

                return view('payment.success', compact('payment'));
            }

            // Capture returned something unexpected — mark as failed.
            $payment->update(['status' => 'failed']);

            return redirect()->route('payment.checkout')
                ->with('error', 'Payment could not be completed. Please try again.');

        } catch (Throwable $e) {
            report($e);
            $payment->update(['status' => 'failed']);

            return redirect()->route('payment.checkout')
                ->with('error', 'An error occurred while processing your payment.');
        }
    }

    /**
     * STEP 3 — Handle cancellation.
     *
     * User clicked "Cancel" on the PayPal page.
     * PayPal redirects here with ?token=<paypal_order_id>
     *
     * GET /payment/cancel
     */
    public function cancel(Request $request): View
    {
        $paypalOrderId = $request->query('token');

        if ($paypalOrderId) {
            // Update our pending record to 'cancelled'
            $request->user()
                ->payments()
                ->where('paypal_order_id', $paypalOrderId)
                ->where('status', 'pending')
                ->update(['status' => 'cancelled']);
        }

        return view('payment.cancel');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Build and authenticate a PayPal API client.
     * The package reads credentials from config/paypal.php (sourced from .env).
     */
    private function getPayPalProvider(): PayPalClient
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken(); // OAuth2 token exchange happens here
        return $provider;
    }
}
