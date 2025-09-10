<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $brokers = [
            ['key' => 'mt4', 'name' => 'MT4', 'logo' => '/images/brokers/mt4.png'],
            ['key' => 'mt5', 'name' => 'MT5', 'logo' => '/images/brokers/mt5.png'],
            ['key' => 'ctrader', 'name' => 'cTrader', 'logo' => '/images/brokers/ctrader.png'],
            ['key' => 'matchtrader', 'name' => 'MatchTrader', 'logo' => '/images/brokers/matchtrader.png'],
        ];

        $plan = auth()->user()->plan ?? 'starter';
        $limits = ['starter' => 1, 'pro' => 5, 'diablo' => 20];
        $maxAccounts = $limits[$plan] ?? 1;
        $currentCount = \App\Models\BrokerAccount::where('user_id', auth()->id())->count();
        $remaining = max(0, $maxAccounts - $currentCount);

        return view('account.index', compact('brokers', 'maxAccounts', 'currentCount', 'remaining', 'plan'));
    }

}
