<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
{
    $brokers = ['MT4', 'MT5', 'cTrader', 'MatchTrader'];
    return view('account.index', ['brokers' => $brokers]);
}

}
