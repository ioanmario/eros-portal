<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AffiliateController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $affiliate = $user->affiliate;
        
        return view('affiliate.index', compact('affiliate', 'user'));
    }

    public function apply(Request $request)
    {
        $user = Auth::user();
        
        // Check if user already has an affiliate application
        if ($user->affiliate) {
            return redirect()->route('affiliate')->with('error', 'You already have an affiliate application.');
        }

        // Validate the application
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        // Generate unique affiliate code
        $affiliateCode = $this->generateAffiliateCode();

        // Create affiliate application
        $affiliate = Affiliate::create([
            'user_id' => $user->id,
            'affiliate_code' => $affiliateCode,
            'commission_rate' => 20.00, // Fixed 20% commission rate
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return redirect()->route('affiliate')->with('success', 'Your affiliate application has been submitted successfully!');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $affiliate = $user->affiliate;
        
        if (!$affiliate || !$affiliate->isApproved()) {
            return redirect()->route('affiliate')->with('error', 'You need to be an approved affiliate to access the dashboard.');
        }

        // Get recent commissions
        $recentCommissions = $affiliate->commissions()
            ->with('referredUser')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get referral statistics
        $referralStats = [
            'total_referrals' => $affiliate->referral_count,
            'total_earnings' => $affiliate->total_earnings,
            'paid_earnings' => $affiliate->paid_earnings,
            'pending_earnings' => $affiliate->pending_earnings,
            'available_earnings' => $affiliate->getAvailableEarnings(),
        ];

        // Get monthly earnings for the last 6 months
        $monthlyEarnings = $affiliate->commissions()
            ->selectRaw('DATE_FORMAT(commission_date, "%Y-%m") as month, SUM(amount) as total')
            ->where('status', 'paid')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get();

        return view('affiliate.dashboard', compact('affiliate', 'recentCommissions', 'referralStats', 'monthlyEarnings'));
    }

    public function earnings()
    {
        $user = Auth::user();
        $affiliate = $user->affiliate;
        
        if (!$affiliate || !$affiliate->isApproved()) {
            return redirect()->route('affiliate')->with('error', 'You need to be an approved affiliate to view earnings.');
        }

        $commissions = $affiliate->commissions()
            ->with('referredUser')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('affiliate.earnings', compact('affiliate', 'commissions'));
    }

    public function referrals()
    {
        $user = Auth::user();
        $affiliate = $user->affiliate;
        
        if (!$affiliate || !$affiliate->isApproved()) {
            return redirect()->route('affiliate')->with('error', 'You need to be an approved affiliate to view referrals.');
        }

        $referrals = $affiliate->referredUsers()
            ->with(['affiliate', 'brokerAccounts'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('affiliate.referrals', compact('affiliate', 'referrals'));
    }

    public function requestPayout(Request $request)
    {
        $user = Auth::user();
        $affiliate = $user->affiliate;
        
        if (!$affiliate || !$affiliate->isApproved()) {
            return redirect()->route('affiliate')->with('error', 'You need to be an approved affiliate to request payouts.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:50|max:' . $affiliate->getAvailableEarnings(),
            'payment_method' => 'required|string|in:crypto,bank',
            'payment_details' => 'required|array',
            'payment_details.wallet_address' => 'required_if:payment_method,crypto|string',
            'payment_details.network' => 'required_if:payment_method,crypto|string',
            'payment_details.account_holder' => 'required_if:payment_method,bank|string',
            'payment_details.account_number' => 'required_if:payment_method,bank|string',
            'payment_details.bank_name' => 'required_if:payment_method,bank|string',
            'payment_details.swift' => 'required_if:payment_method,bank|string',
        ]);

        $availableEarnings = $affiliate->getAvailableEarnings();
        
        if ($availableEarnings < 50) { // Minimum payout threshold
            return redirect()->route('affiliate.dashboard')->with('error', 'Minimum payout amount is $50. You have $' . number_format($availableEarnings, 2) . ' available.');
        }

        // Create payout request
        $payoutRequest = $affiliate->payoutRequests()->create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_details' => $request->payment_details,
        ]);

        return redirect()->route('affiliate.dashboard')->with('success', 'Payout request submitted successfully! It will be reviewed by our team.');
    }

    public function remove(Request $request)
    {
        $user = Auth::user();
        $affiliate = $user->affiliate;
        
        if (!$affiliate) {
            return redirect()->route('affiliate')->with('error', 'No affiliate application found.');
        }

        // Only allow removal if status is pending or rejected
        if (!in_array($affiliate->status, ['pending', 'rejected'])) {
            return redirect()->route('affiliate')->with('error', 'Cannot remove approved or suspended affiliate applications.');
        }

        // Delete the affiliate application
        $affiliate->delete();

        return redirect()->route('affiliate')->with('success', 'Your affiliate application has been removed successfully.');
    }

    private function generateAffiliateCode(): string
    {
        do {
            $code = 'EROS' . strtoupper(Str::random(6));
        } while (Affiliate::where('affiliate_code', $code)->exists());
        
        return $code;
    }

    // Admin methods
    public function adminIndex()
    {
        $affiliates = Affiliate::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.affiliates.index', compact('affiliates'));
    }

    public function adminApprove($id)
    {
        $affiliate = Affiliate::findOrFail($id);
        $affiliate->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Affiliate application approved successfully!');
    }

    public function adminReject($id)
    {
        $affiliate = Affiliate::findOrFail($id);
        $affiliate->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Affiliate application rejected.');
    }

    public function adminSuspend($id)
    {
        $affiliate = Affiliate::findOrFail($id);
        $affiliate->update(['status' => 'suspended']);

        return redirect()->back()->with('success', 'Affiliate suspended.');
    }
}