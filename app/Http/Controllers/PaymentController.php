<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Show payment page for plan selection
     */
    public function index()
    {
        $user = Auth::user();
        $plans = [
            'starter' => [
                'name' => 'Eros Starter',
                'price' => 97,
                'description' => 'Designed for traders ready to pass their first challenge',
                'features' => [
                    '1 Prop Account Management',
                    '1 Prop Challenge Management',
                    'Customer Support',
                    '$1M PROP FUNDING ROADMAP',
                    '70/30 Profit Share (you keep 70%)',
                    'Eros Prop EA'
                ]
            ],
            'pro' => [
                'name' => 'Eros Pro',
                'price' => 197,
                'description' => 'Go pro, scale bigger, and earn more',
                'features' => [
                    'Up to 5 Prop Account Management',
                    'Up to 5 Prop Challenge Management',
                    'Premium Customer Support',
                    '$2M PROP FUNDING ROADMAP',
                    '80/20 Profit Share (you keep 80%)',
                    'Real Capital Account Management',
                    'Eros Prop EA + Eros Gold EA'
                ]
            ],
            'diablo' => [
                'name' => 'Eros Diablo',
                'price' => 497,
                'description' => 'Maximum funding, maximum accounts, maximum freedom',
                'features' => [
                    'Up to 20 Prop Accounts Management',
                    'Up to 20 Prop Challenge Management',
                    'Premium Customer Support',
                    '90/10 Profit Share (you keep 90%)',
                    'Real Capital Account Management',
                    '+$6M PROP FUNDING ROADMAP',
                    'Eros Prop EA + Eros Gold EA + EA ELITE EA v2.3'
                ]
            ]
        ];

        return view('payment.index', compact('plans', 'user'));
    }

    /**
     * Create checkout session for selected plan
     */
    public function createCheckoutSession(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:starter,pro,diablo',
        ]);

        try {
            $user = Auth::user();
            $plan = $request->plan;
            $priceIds = $this->stripeService->getPlanPriceIds();
            $priceId = $priceIds[$plan];

            // Create or get customer
            $customer = $this->stripeService->createOrGetCustomer($user);

            // Create checkout session
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $session = $stripe->checkout->sessions->create([
                'customer' => $customer->id,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $priceId,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel'),
                'metadata' => [
                    'user_id' => $user->id,
                    'plan' => $plan,
                ],
            ]);

            return response()->json(['session_id' => $session->id]);

        } catch (\Exception $e) {
            Log::error('Checkout session creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create checkout session'], 500);
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        
        if (!$sessionId) {
            return redirect()->route('payment.index')->with('error', 'Invalid session');
        }

        try {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $session = $stripe->checkout->sessions->retrieve($sessionId);
            
            if ($session->payment_status === 'paid') {
                $user = Auth::user();
                $plan = $session->metadata->plan ?? 'starter';
                
                // Update user plan
                $user->update([
                    'plan' => $plan,
                    'stripe_subscription_id' => $session->subscription,
                    'subscription_ends_at' => now()->addMonth(),
                ]);

                return redirect()->route('dashboard')->with('success', 'Payment successful! Your subscription has been activated.');
            }

            return redirect()->route('payment.index')->with('error', 'Payment was not completed.');

        } catch (\Exception $e) {
            Log::error('Payment success handling failed: ' . $e->getMessage());
            return redirect()->route('payment.index')->with('error', 'An error occurred while processing your payment.');
        }
    }

    /**
     * Handle cancelled payment
     */
    public function cancel()
    {
        return redirect()->route('payment.index')->with('info', 'Payment was cancelled. You can try again anytime.');
    }

    /**
     * Show billing portal
     */
    public function billingPortal()
    {
        try {
            $user = Auth::user();
            $customer = $this->stripeService->createOrGetCustomer($user);

            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $session = $stripe->billingPortal->sessions->create([
                'customer' => $customer->id,
                'return_url' => route('dashboard'),
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {
            Log::error('Billing portal creation failed: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Failed to access billing portal.');
        }
    }

    /**
     * Show subscription management page
     */
    public function subscription()
    {
        $user = Auth::user();
        
        if (!$user->stripe_customer_id) {
            return redirect()->route('payment.index')->with('info', 'Please subscribe to a plan first.');
        }

        try {
            $customer = $this->stripeService->createOrGetCustomer($user);
            $subscription = null;
            $paymentMethods = [];
            $upcomingInvoice = null;

            if ($user->stripe_subscription_id) {
                $subscription = $this->stripeService->getSubscription($user->stripe_subscription_id);
            }

            $paymentMethods = $this->stripeService->getPaymentMethods($customer->id);
            $upcomingInvoice = $this->stripeService->getUpcomingInvoice($customer->id);

            return view('payment.subscription', compact('user', 'subscription', 'paymentMethods', 'upcomingInvoice'));

        } catch (\Exception $e) {
            Log::error('Subscription page failed: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Failed to load subscription details.');
        }
    }

    /**
     * Update subscription plan
     */
    public function updateSubscription(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:starter,pro,diablo',
        ]);

        try {
            $user = Auth::user();
            
            if (!$user->stripe_subscription_id) {
                return response()->json(['error' => 'No active subscription found'], 400);
            }

            $priceIds = $this->stripeService->getPlanPriceIds();
            $newPriceId = $priceIds[$request->plan];

            $subscription = $this->stripeService->updateSubscription($user->stripe_subscription_id, $newPriceId);
            
            // Update user plan
            $user->update(['plan' => $request->plan]);

            return response()->json(['success' => true, 'message' => 'Subscription updated successfully']);

        } catch (\Exception $e) {
            Log::error('Subscription update failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update subscription'], 500);
        }
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user->stripe_subscription_id) {
                return response()->json(['error' => 'No active subscription found'], 400);
            }

            $immediately = $request->boolean('immediately', false);
            $this->stripeService->cancelSubscription($user->stripe_subscription_id, $immediately);

            if ($immediately) {
                $user->update([
                    'plan' => null,
                    'stripe_subscription_id' => null,
                    'subscription_ends_at' => now(),
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Subscription cancelled successfully']);

        } catch (\Exception $e) {
            Log::error('Subscription cancellation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to cancel subscription'], 500);
        }
    }

    /**
     * Resume subscription
     */
    public function resumeSubscription()
    {
        try {
            $user = Auth::user();
            
            if (!$user->stripe_subscription_id) {
                return response()->json(['error' => 'No active subscription found'], 400);
            }

            $this->stripeService->resumeSubscription($user->stripe_subscription_id);

            return response()->json(['success' => true, 'message' => 'Subscription resumed successfully']);

        } catch (\Exception $e) {
            Log::error('Subscription resume failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to resume subscription'], 500);
        }
    }

    /**
     * Handle Stripe webhooks
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $result = $this->stripeService->handleWebhook($payload, $signature);
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Webhook handling failed: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook handling failed'], 400);
        }
    }
}
