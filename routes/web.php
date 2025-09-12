<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BrokerAccountController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\BrokerSyncController;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ----------------- ADMIN ROUTES -----------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Admin Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
Route::get('/test-metaapi', function () {
    $response = Http::withToken(env('METAAPI_TOKEN'))
        ->get(env('METAAPI_BASE') . '/users/current');
    return $response->json();
});

// Stripe Webhook (no auth required)
Route::post('/stripe/webhook', [App\Http\Controllers\PaymentController::class, 'webhook'])->name('stripe.webhook');
    // Manage Users & Permissions
    Route::get('/users', [PermissionController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/manage', [PermissionController::class, 'manage'])->name('admin.users.manage');
    Route::get('/users/{user}/permissions', [PermissionController::class, 'edit'])->name('admin.users.permissions.edit');
    Route::post('/users/{user}/permissions', [PermissionController::class, 'update'])->name('admin.users.permissions.update');
    
    // Affiliate Management Routes
    Route::post('/affiliates/{user}/approve', [PermissionController::class, 'approveAffiliate'])->name('admin.affiliates.approve');
    Route::post('/affiliates/{user}/reject', [PermissionController::class, 'rejectAffiliate'])->name('admin.affiliates.reject');
    Route::post('/affiliates/{user}/suspend', [PermissionController::class, 'suspendAffiliate'])->name('admin.affiliates.suspend');

    // Admin Profile
    Route::get('/profile', [ProfileController::class, 'adminIndex'])->name('admin.profile');

    // Payout Management
    Route::get('/payouts', [App\Http\Controllers\Admin\PayoutController::class, 'index'])->name('admin.payouts.index');
    Route::get('/payouts/{payoutRequest}', [App\Http\Controllers\Admin\PayoutController::class, 'show'])->name('admin.payouts.show');
    Route::post('/payouts/{payoutRequest}/approve', [App\Http\Controllers\Admin\PayoutController::class, 'approve'])->name('admin.payouts.approve');
    Route::post('/payouts/{payoutRequest}/reject', [App\Http\Controllers\Admin\PayoutController::class, 'reject'])->name('admin.payouts.reject');
    Route::post('/payouts/{payoutRequest}/process', [App\Http\Controllers\Admin\PayoutController::class, 'process'])->name('admin.payouts.process');

    // Analytics & Sales
    Route::get('/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('admin.analytics.index');
    Route::get('/analytics/sales-report', [App\Http\Controllers\Admin\AnalyticsController::class, 'salesReport'])->name('admin.analytics.sales');

    // Support Management
    Route::get('/support', [App\Http\Controllers\Admin\AdminSupportController::class, 'index'])->name('admin.support.index');
    Route::get('/support/{ticket}', [App\Http\Controllers\Admin\AdminSupportController::class, 'show'])->name('admin.support.show');
    Route::post('/support/{ticket}/assign', [App\Http\Controllers\Admin\AdminSupportController::class, 'assign'])->name('admin.support.assign');
    Route::post('/support/{ticket}/status', [App\Http\Controllers\Admin\AdminSupportController::class, 'updateStatus'])->name('admin.support.status');
    Route::post('/support/{ticket}/reply', [App\Http\Controllers\Admin\AdminSupportController::class, 'reply'])->name('admin.support.reply');
    Route::post('/support/bulk', [App\Http\Controllers\Admin\AdminSupportController::class, 'bulkAction'])->name('admin.support.bulk');

    // Role Switch (Admin ↔ User)
    Route::get('/switch/user', function () {
        session(['view_as_user' => true]);
        return redirect()->route('dashboard');
    })->name('admin.switch.user');

    Route::get('/switch/admin', function () {
        session()->forget('view_as_user');
        return redirect()->route('admin.dashboard');
    })->name('admin.switch.admin');
});

// ----------------- PUBLIC ROUTES -----------------
Route::get('/', function () {
    return view('welcome');
});

// ----------------- AUTH ROUTES -----------------
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ----------------- USER ROUTES -----------------
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/account', [App\Http\Controllers\AccountController::class, 'index'])->name('account');
    Route::get('/roadmap', [App\Http\Controllers\RoadmapController::class, 'index'])->name('roadmap');
    Route::get('/affiliate', [App\Http\Controllers\AffiliateController::class, 'index'])->name('affiliate');
    Route::get('/expert-advisors', [App\Http\Controllers\ExpertAdvisorController::class, 'index'])->name('expert.advisors');
    Route::get('/prop-firms', [App\Http\Controllers\PropFirmController::class, 'index'])->name('prop.firms');
    // Support System
    Route::get('/support', [App\Http\Controllers\SupportController::class, 'index'])->name('support.index');
    Route::get('/support/create', [App\Http\Controllers\SupportController::class, 'create'])->name('support.create');
    Route::post('/support', [App\Http\Controllers\SupportController::class, 'store'])->name('support.store');
    Route::get('/support/{ticket}', [App\Http\Controllers\SupportController::class, 'show'])->name('support.show');
    Route::post('/support/{ticket}/reply', [App\Http\Controllers\SupportController::class, 'reply'])->name('support.reply');
    Route::post('/support/{ticket}/close', [App\Http\Controllers\SupportController::class, 'close'])->name('support.close');
    Route::get('/support/{ticket}/download/{attachment}', [App\Http\Controllers\SupportController::class, 'downloadAttachment'])->name('support.download');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/plans', [PlanController::class, 'index'])->name('plans');
    Route::post('/plans/checkout', [PlanController::class, 'checkout'])->name('plans.checkout');
    Route::get('/plans/checkout/success', [PlanController::class, 'checkoutSuccess'])->name('plans.checkout.success');
    Route::post('/plans/cancel', [PlanController::class, 'cancel'])->name('plans.cancel');

    // Payment Routes
    Route::get('/payment', [App\Http\Controllers\PaymentController::class, 'index'])->name('payment.index');
    Route::post('/payment/checkout', [App\Http\Controllers\PaymentController::class, 'createCheckoutSession'])->name('payment.checkout');
    Route::get('/payment/success', [App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');
    Route::get('/payment/billing', [App\Http\Controllers\PaymentController::class, 'billingPortal'])->name('payment.billing');
    Route::get('/payment/subscription', [App\Http\Controllers\PaymentController::class, 'subscription'])->name('payment.subscription');
    Route::post('/payment/subscription/update', [App\Http\Controllers\PaymentController::class, 'updateSubscription'])->name('payment.subscription.update');
    Route::post('/payment/subscription/cancel', [App\Http\Controllers\PaymentController::class, 'cancelSubscription'])->name('payment.subscription.cancel');
    Route::post('/payment/subscription/resume', [App\Http\Controllers\PaymentController::class, 'resumeSubscription'])->name('payment.subscription.resume');

    // ----------------- BROKER SYNC ROUTES -----------------
    Route::prefix('broker-sync')->group(function () {
        Route::get('/', [BrokerSyncController::class, 'select'])->name('broker.sync.select');

        // MetaTrader flow (MT4 / MT5)
        Route::get('/{platform}/form', [BrokerSyncController::class, 'mtForm'])
            ->whereIn('platform', ['mt4','mt5'])
            ->name('broker.sync.mt.form');

        Route::post('/{platform}/verify', [BrokerSyncController::class, 'mtVerify'])
            ->whereIn('platform', ['mt4','mt5'])
            ->name('broker.sync.mt.verify');

        // Server search (autocomplete)
        Route::get('/servers/search', [BrokerSyncController::class, 'searchServers'])
            ->name('broker.sync.servers.search');

        // Stubs — UI only for now
        Route::get('/ctrader', [BrokerSyncController::class, 'ctrader'])->name('broker.sync.ctrader');
        Route::get('/matchtrader', [BrokerSyncController::class, 'matchtrader'])->name('broker.sync.matchtrader');
    });

    // Broker accounts CRUD (auth only)
    Route::get('/broker-accounts', [BrokerAccountController::class, 'index'])->name('broker.accounts.index');
    Route::post('/broker-accounts', [BrokerAccountController::class, 'store'])->name('broker.accounts.store');
    Route::delete('/broker-accounts/{brokerAccount}', [BrokerAccountController::class, 'destroy'])->name('broker.accounts.destroy');

    // Affiliate system
    Route::get('/affiliate', [App\Http\Controllers\AffiliateController::class, 'index'])->name('affiliate');
    Route::prefix('affiliate')->name('affiliate.')->group(function () {
        Route::post('/apply', [App\Http\Controllers\AffiliateController::class, 'apply'])->name('apply');
        Route::delete('/remove', [App\Http\Controllers\AffiliateController::class, 'remove'])->name('remove');
        Route::get('/dashboard', [App\Http\Controllers\AffiliateController::class, 'dashboard'])->name('dashboard');
        Route::get('/earnings', [App\Http\Controllers\AffiliateController::class, 'earnings'])->name('earnings');
        Route::get('/referrals', [App\Http\Controllers\AffiliateController::class, 'referrals'])->name('referrals');
        Route::post('/payout', [App\Http\Controllers\AffiliateController::class, 'requestPayout'])->name('payout');
    });
});
