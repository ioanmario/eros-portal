<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $plans = [
            'free' => [
                'name' => 'Free',
                'price' => 0,
                'accounts' => 0,
                'features' => ['Basic dashboard', 'Limited support'],
                'stripe_price_id' => null,
            ],
            'starter' => [
                'name' => 'Eros Starter',
                'price' => 97,
                'accounts' => 1,
                'features' => [
                    '1 Broker Account',
                    'Eros Starter EA',
                    'Basic Risk Management',
                    'Email Support',
                    'Account Management',
                    'Basic Analytics'
                ],
                'stripe_price_id' => 'price_starter_monthly',
            ],
            'pro' => [
                'name' => 'Eros Pro',
                'price' => 197,
                'accounts' => 5,
                'features' => [
                    '5 Broker Accounts',
                    'Eros Pro EA',
                    'Advanced Risk Management',
                    'Priority Support',
                    'Advanced Analytics',
                    'Custom Strategies',
                    'Performance Tracking',
                    'Risk Assessment Tools'
                ],
                'stripe_price_id' => 'price_pro_monthly',
            ],
            'diablo' => [
                'name' => 'Eros Diablo',
                'price' => 497,
                'accounts' => 20,
                'features' => [
                    '20 Broker Accounts',
                    'Eros Diablo EA',
                    'Premium Risk Management',
                    'VIP Support',
                    'Advanced Analytics',
                    'Custom Development',
                    'White Label Options',
                    'Dedicated Account Manager',
                    'Custom EA Development',
                    'API Access'
                ],
                'stripe_price_id' => 'price_diablo_monthly',
            ],
        ];

        return view('plans.index', compact('plans', 'user'));
    }

    public function checkout(Request $request)
    {
        $plan = $request->input('plan');
        $validPlans = ['starter', 'pro', 'diablo'];
        
        if (!in_array($plan, $validPlans)) {
            return back()->with('error', 'Invalid plan selected.');
        }

        // In a real implementation, you'd create a Stripe checkout session here
        // For now, we'll simulate the checkout process
        return redirect()->route('plans.checkout.success', ['plan' => $plan]);
    }

    public function checkoutSuccess(Request $request)
    {
        $plan = $request->input('plan');
        return view('plans.checkout-success', compact('plan'));
    }

    public function cancel()
    {
        // In a real implementation, you'd cancel the Stripe subscription here
        $user = Auth::user();
        $user->update([
            'plan' => 'free',
            'stripe_subscription_id' => null,
            'subscription_ends_at' => now(),
        ]);

        return back()->with('success', 'Subscription cancelled. You can resubscribe anytime.');
    }
}
