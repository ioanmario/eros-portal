<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    public function index()
    {
        $payoutRequests = PayoutRequest::with(['user', 'affiliate', 'processedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_requests' => PayoutRequest::count(),
            'pending_requests' => PayoutRequest::where('status', 'pending')->count(),
            'approved_requests' => PayoutRequest::where('status', 'approved')->count(),
            'rejected_requests' => PayoutRequest::where('status', 'rejected')->count(),
            'total_amount' => PayoutRequest::where('status', '!=', 'rejected')->sum('amount'),
            'pending_amount' => PayoutRequest::where('status', 'pending')->sum('amount'),
        ];

        return view('admin.payouts.index', compact('payoutRequests', 'stats'));
    }

    public function show(PayoutRequest $payoutRequest)
    {
        $payoutRequest->load(['user', 'affiliate', 'processedBy']);
        return view('admin.payouts.show', compact('payoutRequest'));
    }

    public function approve(Request $request, PayoutRequest $payoutRequest)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $payoutRequest->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'processed_at' => now(),
            'processed_by' => Auth::id(),
        ]);

        return redirect()->route('admin.payouts.index')
            ->with('success', 'Payout request approved successfully!');
    }

    public function reject(Request $request, PayoutRequest $payoutRequest)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $payoutRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'processed_at' => now(),
            'processed_by' => Auth::id(),
        ]);

        return redirect()->route('admin.payouts.index')
            ->with('success', 'Payout request rejected.');
    }

    public function process(PayoutRequest $payoutRequest)
    {
        if ($payoutRequest->status !== 'approved') {
            return redirect()->route('admin.payouts.index')
                ->with('error', 'Only approved requests can be marked as processed.');
        }

        $payoutRequest->update([
            'status' => 'processed',
            'processed_at' => now(),
            'processed_by' => Auth::id(),
        ]);

        // Update affiliate's paid earnings
        $affiliate = $payoutRequest->affiliate;
        $affiliate->increment('paid_earnings', $payoutRequest->amount);

        return redirect()->route('admin.payouts.index')
            ->with('success', 'Payout marked as processed successfully!');
    }
}
