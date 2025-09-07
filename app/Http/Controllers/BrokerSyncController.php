<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VerifyMtRequest;
use App\Services\Brokers\MetaApiService;

class BrokerSyncController extends Controller
{
    /**
     * Show broker selection page.
     */
    public function select()
    {
        return view('brokers.select');
    }

    /**
     * Show MT4 / MT5 sync form.
     */
    public function mtForm(string $platform)
    {
        abort_unless(in_array($platform, ['mt4', 'mt5']), 404);

        return view('brokers.mt-form', compact('platform'));
    }

    /**
     * Verify MT4 / MT5 credentials using MetaApiService.
     * Handles both AJAX (JSON) and regular form requests.
     */
    public function mtVerify(string $platform, VerifyMtRequest $request, MetaApiService $metaApi)
    {
        // Optional: log the request for debugging
        \Log::info('BrokerSync: MT Verify', [
            'platform' => $platform,
            'server'   => $request->input('server'),
            'login'    => $request->input('login'),
            'expects_json' => $request->expectsJson(),
        ]);

        // Perform verification via MetaApi service
        $result = $metaApi->verify(
            $platform,
            $request->input('server'),
            $request->input('login'),
            $request->input('password')
        );

        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => $result['ok'],
                'message' => $result['message'],
            ]);
        }

        // For regular form submissions, redirect with flash message
        return back()->with($result['ok'] ? 'status' : 'error', $result['message']);
    }

    /**
     * Search MetaTrader servers via MetaApi.
     * Used for autocomplete or live server search.
     */
    public function searchServers(Request $request, MetaApiService $metaApi)
    {
        $version = (int) $request->input('version', 5); // Default to MT5
        $query = trim($request->input('q', ''));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = $metaApi->searchServers($version, $query);

        return response()->json($results);
    }

    /**
     * Placeholder for cTrader integration.
     * Will be replaced by OAuth access-token-based flow.
     */
    public function ctrader()
    {
        return view('brokers.ctrader');
    }

    /**
     * Placeholder for MatchTrader integration.
     */
    public function matchtrader()
    {
        return view('brokers.matchtrader');
    }
}
