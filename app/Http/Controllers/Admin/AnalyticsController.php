<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StripeService;
use App\Models\User;
use App\Models\Affiliate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Show analytics dashboard
     */
    public function index()
    {
        try {
            // Get Stripe analytics
            $stripeAnalytics = $this->getStripeAnalytics();
            
            // Get local database analytics
            $localAnalytics = $this->getLocalAnalytics();
            
            // Get subscription metrics
            $subscriptionMetrics = $this->getSubscriptionMetrics();
            
            // Get revenue analytics
            $revenueAnalytics = $this->getRevenueAnalytics();
            
            // Get user growth analytics
            $userGrowth = $this->getUserGrowthAnalytics();
            
            // Get affiliate analytics
            $affiliateAnalytics = $this->getAffiliateAnalytics();

            return view('admin.analytics.index', compact(
                'stripeAnalytics',
                'localAnalytics', 
                'subscriptionMetrics',
                'revenueAnalytics',
                'userGrowth',
                'affiliateAnalytics'
            ));

        } catch (\Exception $e) {
            \Log::error('Analytics dashboard error: ' . $e->getMessage());
            return view('admin.analytics.index', [
                'stripeAnalytics' => null,
                'localAnalytics' => $this->getLocalAnalytics(),
                'subscriptionMetrics' => $this->getSubscriptionMetrics(),
                'revenueAnalytics' => $this->getRevenueAnalytics(),
                'userGrowth' => $this->getUserGrowthAnalytics(),
                'affiliateAnalytics' => $this->getAffiliateAnalytics(),
                'error' => 'Unable to load Stripe analytics. Please check your API keys.'
            ]);
        }
    }

    /**
     * Get Stripe analytics data
     */
    protected function getStripeAnalytics()
    {
        try {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            
            // Get subscription counts by status
            $subscriptions = $stripe->subscriptions->all(['limit' => 100]);
            $subscriptionCounts = [
                'active' => 0,
                'canceled' => 0,
                'incomplete' => 0,
                'past_due' => 0,
                'unpaid' => 0
            ];

            foreach ($subscriptions->data as $subscription) {
                $status = $subscription->status;
                if (isset($subscriptionCounts[$status])) {
                    $subscriptionCounts[$status]++;
                }
            }

            // Get revenue data (last 30 days)
            $endDate = now();
            $startDate = now()->subDays(30);
            
            $invoices = $stripe->invoices->all([
                'created' => [
                    'gte' => $startDate->timestamp,
                    'lte' => $endDate->timestamp,
                ],
                'status' => 'paid',
                'limit' => 100
            ]);

            $monthlyRevenue = 0;
            $planRevenue = [
                'starter' => 0,
                'pro' => 0,
                'diablo' => 0
            ];

            foreach ($invoices->data as $invoice) {
                $amount = $invoice->amount_paid / 100; // Convert from cents
                $monthlyRevenue += $amount;
                
                // Try to determine plan from price ID
                if ($invoice->lines->data) {
                    $priceId = $invoice->lines->data[0]->price->id;
                    $planPriceIds = config('services.stripe.price_ids');
                    
                    if ($priceId === $planPriceIds['starter']) {
                        $planRevenue['starter'] += $amount;
                    } elseif ($priceId === $planPriceIds['pro']) {
                        $planRevenue['pro'] += $amount;
                    } elseif ($priceId === $planPriceIds['diablo']) {
                        $planRevenue['diablo'] += $amount;
                    }
                }
            }

            // Get customer count
            $customers = $stripe->customers->all(['limit' => 100]);
            $customerCount = count($customers->data);

            return [
                'subscription_counts' => $subscriptionCounts,
                'monthly_revenue' => $monthlyRevenue,
                'plan_revenue' => $planRevenue,
                'customer_count' => $customerCount,
                'total_subscriptions' => array_sum($subscriptionCounts)
            ];

        } catch (\Exception $e) {
            \Log::error('Stripe analytics error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get local database analytics
     */
    protected function getLocalAnalytics()
    {
        return [
            'total_users' => User::count(),
            'users_with_plans' => User::whereNotNull('plan')->count(),
            'users_with_stripe' => User::whereNotNull('stripe_customer_id')->count(),
            'active_subscriptions' => User::whereNotNull('stripe_subscription_id')
                ->where('subscription_ends_at', '>', now())
                ->count(),
            'expired_subscriptions' => User::whereNotNull('stripe_subscription_id')
                ->where('subscription_ends_at', '<=', now())
                ->count(),
        ];
    }

    /**
     * Get subscription metrics
     */
    protected function getSubscriptionMetrics()
    {
        $plans = ['starter', 'pro', 'diablo'];
        $metrics = [];

        foreach ($plans as $plan) {
            $metrics[$plan] = [
                'total' => User::where('plan', $plan)->count(),
                'active' => User::where('plan', $plan)
                    ->where('subscription_ends_at', '>', now())
                    ->count(),
                'expired' => User::where('plan', $plan)
                    ->where('subscription_ends_at', '<=', now())
                    ->count(),
            ];
        }

        return $metrics;
    }

    /**
     * Get revenue analytics
     */
    protected function getRevenueAnalytics()
    {
        $plans = ['starter', 'pro', 'diablo'];
        $planPrices = [97, 197, 497];
        $revenue = [];

        foreach ($plans as $index => $plan) {
            $activeUsers = User::where('plan', $plan)
                ->where('subscription_ends_at', '>', now())
                ->count();
            
            $revenue[$plan] = [
                'monthly_revenue' => $activeUsers * $planPrices[$index],
                'active_users' => $activeUsers,
                'price' => $planPrices[$index]
            ];
        }

        $revenue['total_monthly'] = array_sum(array_column($revenue, 'monthly_revenue'));
        $revenue['total_users'] = array_sum(array_column($revenue, 'active_users'));

        return $revenue;
    }

    /**
     * Get user growth analytics
     */
    protected function getUserGrowthAnalytics()
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'users' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'subscriptions' => User::whereNotNull('stripe_subscription_id')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }

        return $months;
    }

    /**
     * Get affiliate analytics
     */
    protected function getAffiliateAnalytics()
    {
        return [
            'total_affiliates' => Affiliate::count(),
            'approved_affiliates' => Affiliate::where('status', 'approved')->count(),
            'pending_affiliates' => Affiliate::where('status', 'pending')->count(),
            'rejected_affiliates' => Affiliate::where('status', 'rejected')->count(),
            'suspended_affiliates' => Affiliate::where('status', 'suspended')->count(),
            'total_earnings' => Affiliate::sum('total_earnings'),
            'pending_earnings' => Affiliate::sum('pending_earnings'),
            'paid_earnings' => Affiliate::sum('paid_earnings'),
        ];
    }

    /**
     * Get detailed sales report
     */
    public function salesReport(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = now()->subDays($period);
        $endDate = now();

        try {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            
            // Get all paid invoices in the period
            $invoices = $stripe->invoices->all([
                'created' => [
                    'gte' => $startDate->timestamp,
                    'lte' => $endDate->timestamp,
                ],
                'status' => 'paid',
                'limit' => 100
            ]);

            $sales = [];
            $totalRevenue = 0;

            foreach ($invoices->data as $invoice) {
                $amount = $invoice->amount_paid / 100;
                $totalRevenue += $amount;
                
                $plan = 'unknown';
                if ($invoice->lines->data) {
                    $priceId = $invoice->lines->data[0]->price->id;
                    $planPriceIds = config('services.stripe.price_ids');
                    
                    if ($priceId === $planPriceIds['starter']) {
                        $plan = 'starter';
                    } elseif ($priceId === $planPriceIds['pro']) {
                        $plan = 'pro';
                    } elseif ($priceId === $planPriceIds['diablo']) {
                        $plan = 'diablo';
                    }
                }

                $sales[] = [
                    'id' => $invoice->id,
                    'customer_email' => $invoice->customer_email,
                    'amount' => $amount,
                    'plan' => $plan,
                    'date' => Carbon::createFromTimestamp($invoice->created),
                    'status' => $invoice->status,
                ];
            }

            return response()->json([
                'success' => true,
                'sales' => $sales,
                'total_revenue' => $totalRevenue,
                'period' => $period,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
            ]);

        } catch (\Exception $e) {
            \Log::error('Sales report error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate sales report'], 500);
        }
    }
}
