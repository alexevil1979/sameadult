<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Services\NowPaymentsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Plan Controller — AIVidCatalog18
 *
 * Handles displaying subscription plans and initiating purchases
 * via NOWPayments crypto gateway.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
class PlanController extends Controller
{
    /**
     * Display available subscription plans.
     *
     * GET /plans
     */
    public function index()
    {
        $plans = Plan::active()->orderBy('price_usd')->get();

        return view('plans.index', compact('plans'));
    }

    /**
     * Initiate a plan purchase via NOWPayments.
     *
     * POST /plans/{plan}/buy
     * Requires authentication.
     *
     * Creates an invoice and redirects the user to the NOWPayments checkout page.
     */
    public function buy(Plan $plan, NowPaymentsService $nowPayments)
    {
        // Ensure plan is active
        if (!$plan->is_active) {
            return back()->with('error', __('This plan is no longer available.'));
        }

        // Check if user already has an active subscription
        $user = auth()->user();
        if ($user->hasActiveSubscription()) {
            return back()->with('info', __('You already have an active subscription.'));
        }

        try {
            $invoice = $nowPayments->createInvoice($user, $plan);

            if (!empty($invoice['invoice_url'])) {
                return redirect()->away($invoice['invoice_url']);
            }

            return back()->with('error', __('Could not create payment invoice. Please try again.'));
        } catch (\Exception $e) {
            Log::error('Plan purchase failed', [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'error'   => $e->getMessage(),
            ]);

            return back()->with('error', __('Payment processing error. Please try again later.'));
        }
    }

    /**
     * Payment success callback page.
     *
     * GET /payments/success
     */
    public function success()
    {
        return view('payments.success');
    }

    /**
     * Payment cancel/failure callback page.
     *
     * GET /payments/cancel
     */
    public function cancel()
    {
        return view('payments.cancel');
    }
}
