<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\User;

class AffiliateService
{
    public function processReferral(User $referredUser, string $referralCode): void
    {
        // Find the affiliate by code
        $affiliate = Affiliate::where('affiliate_code', $referralCode)
            ->where('status', 'approved')
            ->first();

        if (!$affiliate) {
            return;
        }

        // Update user's referral information
        $referredUser->update([
            'referred_by_code' => $referralCode,
            'referred_by_user_id' => $affiliate->user_id,
        ]);

        // Update affiliate referral count
        $affiliate->increment('referral_count');
    }

    public function processMonthlyCommission(User $user): void
    {
        if (!$user->referred_by_user_id) {
            return;
        }

        $affiliate = Affiliate::where('user_id', $user->referred_by_user_id)
            ->where('status', 'approved')
            ->first();

        if (!$affiliate) {
            return;
        }

        // Calculate commission based on user's plan
        $commissionAmount = $this->calculateCommission($user->plan, $affiliate->commission_rate);

        if ($commissionAmount > 0) {
            // Create commission record
            AffiliateCommission::create([
                'affiliate_id' => $affiliate->id,
                'referred_user_id' => $user->id,
                'commission_type' => 'monthly_subscription',
                'amount' => $commissionAmount,
                'commission_rate' => $affiliate->commission_rate,
                'status' => 'pending',
                'commission_date' => now()->startOfMonth(),
                'description' => "Monthly commission for {$user->plan} plan",
            ]);

            // Update affiliate earnings
            $affiliate->increment('total_earnings', $commissionAmount);
            $affiliate->increment('pending_earnings', $commissionAmount);
        }
    }

    public function processUpgradeCommission(User $user, string $oldPlan, string $newPlan): void
    {
        if (!$user->referred_by_user_id) {
            return;
        }

        $affiliate = Affiliate::where('user_id', $user->referred_by_user_id)
            ->where('status', 'approved')
            ->first();

        if (!$affiliate) {
            return;
        }

        // Calculate upgrade commission (difference between plans)
        $oldCommission = $this->calculateCommission($oldPlan, $affiliate->commission_rate);
        $newCommission = $this->calculateCommission($newPlan, $affiliate->commission_rate);
        $upgradeAmount = $newCommission - $oldCommission;

        if ($upgradeAmount > 0) {
            AffiliateCommission::create([
                'affiliate_id' => $affiliate->id,
                'referred_user_id' => $user->id,
                'commission_type' => 'upgrade',
                'amount' => $upgradeAmount,
                'commission_rate' => $affiliate->commission_rate,
                'status' => 'pending',
                'commission_date' => now(),
                'description' => "Upgrade commission from {$oldPlan} to {$newPlan}",
            ]);

            $affiliate->increment('total_earnings', $upgradeAmount);
            $affiliate->increment('pending_earnings', $upgradeAmount);
        }
    }

    private function calculateCommission(string $plan, float $commissionRate): float
    {
        $planPrices = [
            'free' => 0,
            'starter' => 97,
            'pro' => 197,
            'diablo' => 497,
        ];

        $planPrice = $planPrices[$plan] ?? 0;
        return ($planPrice * $commissionRate) / 100;
    }

    public function processPayout(Affiliate $affiliate, float $amount): void
    {
        if ($amount > $affiliate->getAvailableEarnings()) {
            throw new \Exception('Payout amount exceeds available earnings');
        }

        // Update affiliate earnings
        $affiliate->increment('paid_earnings', $amount);
        $affiliate->decrement('pending_earnings', $amount);

        // Mark commissions as paid
        $affiliate->commissions()
            ->where('status', 'pending')
            ->where('amount', '<=', $amount)
            ->update(['status' => 'paid']);

        // Here you would integrate with your payment processor
        // For now, we'll just log the payout
        \Log::info("Payout processed for affiliate {$affiliate->id}: $amount");
    }
}
