<?php

namespace App\Http\Controllers;

use App\Models\BrokerAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class BrokerAccountController extends Controller
{
    public function index(Request $request)
    {
        $accounts = BrokerAccount::where('user_id', $request->user()->id)
            ->orderByDesc('id')
            ->get(['id','platform','server','login','label','created_at']);
        return response()->json($accounts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => ['required','in:mt4,mt5,ctrader,matchtrader'],
            'server'   => ['nullable','string','max:120'],
            'login'    => ['required','string','max:64'],
            'password' => ['required','string','max:128'],
            'label'    => ['nullable','string','max:64'],
        ]);

        // Enforce plan-based limits
        $plan = $request->user()->plan ?? 'free';
        $limits = ['free' => 0, 'starter' => 1, 'pro' => 5, 'diablo' => 20];
        $max = $limits[$plan] ?? 1;
        $count = BrokerAccount::where('user_id', $request->user()->id)->count();
        if ($count >= $max) {
            return response()->json(['success' => false, 'message' => 'Account limit reached for your plan.'], 403);
        }

        $account = BrokerAccount::create([
            'user_id'            => $request->user()->id,
            'platform'           => $validated['platform'],
            'server'             => $validated['server'] ?? null,
            'login'              => $validated['login'],
            'encrypted_password' => Crypt::encryptString($validated['password']),
            'label'              => $validated['label'] ?? null,
        ]);

        return response()->json(['success' => true, 'id' => $account->id]);
    }

    public function destroy(Request $request, BrokerAccount $brokerAccount)
    {
        if ($brokerAccount->user_id !== $request->user()->id) {
            return response()->json(['success' => false], 403);
        }
        $brokerAccount->delete();
        return response()->json(['success' => true]);
    }
}


