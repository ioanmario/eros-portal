<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $users = User::with('roles', 'permissions', 'affiliate')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function manage(User $user)
    {
        $user->load(['roles', 'permissions', 'affiliate', 'brokerAccounts', 'payoutRequests']);
        
        // Get user statistics
        $stats = [
            'total_referrals' => $user->referrals()->count(),
            'total_earnings' => $user->affiliate ? $user->affiliate->total_earnings : 0,
            'pending_earnings' => $user->affiliate ? $user->affiliate->pending_earnings : 0,
            'paid_earnings' => $user->affiliate ? $user->affiliate->paid_earnings : 0,
            'broker_accounts' => $user->brokerAccounts()->count(),
            'payout_requests' => $user->payoutRequests()->count(),
        ];
        
        return view('admin.users.manage', compact('user', 'stats'));
    }

    // Show permissions for a specific user
    public function edit(User $user)
    {
        $permissions = Permission::all();
        return view('admin.users.permissions', compact('user', 'permissions'));
    }

    // Update permissions for a user
    public function update(Request $request, User $user)
    {
        $user->syncPermissions($request->permissions ?? []);
        return redirect()->route('admin.users.index')->with('success', 'Permissions updated!');
    }

    // Approve affiliate application
    public function approveAffiliate(Request $request, User $user)
    {
        $affiliate = $user->affiliate;
        
        if (!$affiliate) {
            return response()->json(['success' => false, 'message' => 'No affiliate application found.'], 404);
        }

        if ($affiliate->status === 'approved') {
            return response()->json(['success' => false, 'message' => 'Affiliate is already approved.'], 400);
        }

        $affiliate->update([
            'status' => 'approved',
            'approved_at' => now(),
            'notes' => $request->notes ?? $affiliate->notes
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Affiliate application approved successfully!',
            'new_status' => 'approved'
        ]);
    }

    // Reject affiliate application
    public function rejectAffiliate(Request $request, User $user)
    {
        $affiliate = $user->affiliate;
        
        if (!$affiliate) {
            return response()->json(['success' => false, 'message' => 'No affiliate application found.'], 404);
        }

        if ($affiliate->status === 'rejected') {
            return response()->json(['success' => false, 'message' => 'Affiliate application is already rejected.'], 400);
        }

        $request->validate([
            'notes' => 'required|string|min:10|max:500'
        ], [
            'notes.required' => 'Please provide a reason for rejection.',
            'notes.min' => 'Rejection reason must be at least 10 characters.',
            'notes.max' => 'Rejection reason must not exceed 500 characters.'
        ]);

        $affiliate->update([
            'status' => 'rejected',
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Affiliate application rejected.',
            'new_status' => 'rejected'
        ]);
    }

    // Suspend affiliate
    public function suspendAffiliate(Request $request, User $user)
    {
        $affiliate = $user->affiliate;
        
        if (!$affiliate) {
            return response()->json(['success' => false, 'message' => 'No affiliate application found.'], 404);
        }

        if ($affiliate->status === 'suspended') {
            return response()->json(['success' => false, 'message' => 'Affiliate is already suspended.'], 400);
        }

        $request->validate([
            'notes' => 'required|string|min:10|max:500'
        ], [
            'notes.required' => 'Please provide a reason for suspension.',
            'notes.min' => 'Suspension reason must be at least 10 characters.',
            'notes.max' => 'Suspension reason must not exceed 500 characters.'
        ]);

        $affiliate->update([
            'status' => 'suspended',
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Affiliate has been suspended.',
            'new_status' => 'suspended'
        ]);
    }
}
