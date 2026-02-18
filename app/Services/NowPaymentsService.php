<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * NOWPayments Integration Service — AIVidCatalog18
 *
 * Handles creating invoices and processing webhook callbacks
 * from NOWPayments crypto payment gateway.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 *
 * @see https://documenter.getpostman.com/view/7907941/S1a32n38 NOWPayments API Docs
 */
class NowPaymentsService
{
    protected string $apiKey;
    protected string $ipnSecret;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey    = config('nowpayments.api_key');
        $this->ipnSecret = config('nowpayments.ipn_secret');
        $this->baseUrl   = config('nowpayments.base_url');
    }

    /**
     * Create an invoice on NOWPayments for a subscription purchase.
     *
     * @param User $user The purchasing user
     * @param Plan $plan The subscription plan to buy
     * @return array{invoice_url: string, invoice_id: string}
     *
     * @throws \Exception If the API call fails
     */
    public function createInvoice(User $user, Plan $plan): array
    {
        // Build a unique order ID for tracking
        $orderId = "sub_{$user->id}_{$plan->id}_" . time();

        $payload = [
            'price_amount'      => (float) $plan->price_usd,
            'price_currency'    => 'usd',
            'order_id'          => $orderId,
            'order_description' => "AIVidCatalog18 — {$plan->name} subscription ({$plan->duration_days} days)",
            'ipn_callback_url'  => route('webhook.nowpayments'),
            'success_url'       => route('payments.success'),
            'cancel_url'        => route('payments.cancel'),
        ];

        Log::info('NowPayments: Creating invoice', [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'payload' => $payload,
        ]);

        $response = Http::withHeaders([
            'x-api-key'    => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/invoice", $payload);

        if (!$response->successful()) {
            Log::error('NowPayments: Invoice creation failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            throw new \Exception('Failed to create payment invoice. Please try again later.');
        }

        $data = $response->json();

        // Create a pending payment record in our database
        Payment::create([
            'user_id'                => $user->id,
            'plan_id'                => $plan->id,
            'amount'                 => $plan->price_usd,
            'currency'               => 'usd',
            'status'                 => 'pending',
            'nowpayments_invoice_id' => $data['id'] ?? null,
            'metadata'               => [
                'order_id'        => $orderId,
                'invoice_response' => $data,
            ],
        ]);

        return [
            'invoice_url' => $data['invoice_url'] ?? '',
            'invoice_id'  => $data['id'] ?? '',
        ];
    }

    /**
     * Handle the IPN (Instant Payment Notification) webhook from NOWPayments.
     *
     * Security: Verifies the HMAC-SHA512 signature using IPN_SECRET.
     * On successful payment: creates subscription, updates user, logs payment.
     *
     * @param Request $request The incoming webhook request
     * @return bool True if processed successfully
     */
    public function handleWebhook(Request $request): bool
    {
        $payload = $request->all();

        // =====================================================================
        // Step 1: Verify webhook signature (HMAC-SHA512)
        // =====================================================================
        if (!$this->verifySignature($request)) {
            Log::warning('NowPayments: Webhook signature verification FAILED', [
                'ip' => $request->ip(),
            ]);
            return false;
        }

        Log::info('NowPayments: Webhook received', $payload);

        // =====================================================================
        // Step 2: Process based on payment status
        // =====================================================================
        $paymentStatus = $payload['payment_status'] ?? '';
        $invoiceId     = $payload['invoice_id'] ?? $payload['payment_id'] ?? null;
        $orderId       = $payload['order_id'] ?? '';

        // Find the pending payment in our database
        $payment = Payment::where('nowpayments_invoice_id', $invoiceId)
            ->orWhere(function ($query) use ($orderId) {
                if ($orderId) {
                    $query->whereJsonContains('metadata->order_id', $orderId);
                }
            })
            ->first();

        if (!$payment) {
            Log::error('NowPayments: Payment record not found', [
                'invoice_id' => $invoiceId,
                'order_id'   => $orderId,
            ]);
            return false;
        }

        // Update payment metadata with the latest webhook data
        $payment->update([
            'metadata' => array_merge($payment->metadata ?? [], [
                'webhook_data' => $payload,
                'webhook_at'   => now()->toISOString(),
            ]),
        ]);

        // =====================================================================
        // Step 3: Handle payment completion
        // =====================================================================
        if (in_array($paymentStatus, ['finished', 'confirmed'])) {
            return $this->activateSubscription($payment, $payload);
        }

        if (in_array($paymentStatus, ['failed', 'refunded', 'expired'])) {
            $payment->markFailed();
            Log::info("NowPayments: Payment marked as failed", [
                'payment_id' => $payment->id,
                'status'     => $paymentStatus,
            ]);
        }

        return true;
    }

    /**
     * Verify the HMAC-SHA512 signature of the webhook request.
     *
     * NOWPayments signs the webhook body using the IPN secret.
     * We must sort the JSON keys alphabetically before computing HMAC.
     */
    protected function verifySignature(Request $request): bool
    {
        $receivedSignature = $request->header('x-nowpayments-sig');

        if (!$receivedSignature) {
            return false;
        }

        // Sort the request data alphabetically by key (as per NOWPayments docs)
        $payload = $request->all();
        ksort($payload);

        $computedSignature = hash_hmac(
            'sha512',
            json_encode($payload, JSON_UNESCAPED_UNICODE),
            $this->ipnSecret
        );

        return hash_equals($computedSignature, $receivedSignature);
    }

    /**
     * Activate a subscription after successful payment.
     *
     * Creates a Subscription record and updates the User's subscription_end field.
     */
    protected function activateSubscription(Payment $payment, array $webhookData): bool
    {
        try {
            // Mark payment as successful
            $payment->markSuccess();

            $user = $payment->user;
            $plan = $payment->plan;

            if (!$user || !$plan) {
                Log::error('NowPayments: User or Plan not found for payment', [
                    'payment_id' => $payment->id,
                ]);
                return false;
            }

            // Calculate subscription dates
            // If user already has an active sub, extend it; otherwise start now
            $startsAt = now();
            $currentSub = $user->activeSubscription();

            if ($currentSub && $currentSub->ends_at->isFuture()) {
                // Extend existing subscription
                $startsAt = $currentSub->ends_at;
            }

            $endsAt = $startsAt->copy()->addDays($plan->duration_days);

            // Create subscription record
            $subscription = Subscription::create([
                'user_id'    => $user->id,
                'plan_id'    => $plan->id,
                'starts_at'  => $startsAt,
                'ends_at'    => $endsAt,
                'status'     => 'active',
                'payment_id' => $payment->nowpayments_invoice_id,
            ]);

            // Update denormalized field on user
            $user->update(['subscription_end' => $endsAt]);

            Log::info('NowPayments: Subscription activated', [
                'user_id'         => $user->id,
                'plan'            => $plan->name,
                'subscription_id' => $subscription->id,
                'ends_at'         => $endsAt->toDateTimeString(),
            ]);

            // Dispatch event for listeners (email notification, etc.)
            event(new \App\Events\SubscriptionActivated($subscription));

            return true;
        } catch (\Exception $e) {
            Log::error('NowPayments: Subscription activation failed', [
                'payment_id' => $payment->id,
                'error'      => $e->getMessage(),
            ]);
            return false;
        }
    }
}
