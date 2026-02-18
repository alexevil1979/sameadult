<?php

namespace App\Http\Controllers;

use App\Services\NowPaymentsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Webhook Controller — AIVidCatalog18
 *
 * Handles incoming webhooks from payment gateways.
 * CSRF protection is skipped for webhook routes (configured in routes).
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
class WebhookController extends Controller
{
    /**
     * Handle NOWPayments IPN (Instant Payment Notification).
     *
     * POST /webhook/nowpayments
     *
     * Security:
     * - Verifies HMAC-SHA512 signature
     * - Rate limited
     * - Logs all incoming requests
     */
    public function nowpayments(Request $request, NowPaymentsService $nowPayments)
    {
        Log::info('NowPayments webhook received', [
            'ip'      => $request->ip(),
            'headers' => $request->headers->all(),
        ]);

        $success = $nowPayments->handleWebhook($request);

        if (!$success) {
            Log::warning('NowPayments webhook processing failed');
            return response()->json(['status' => 'error'], 400);
        }

        return response()->json(['status' => 'ok'], 200);
    }
}
