<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\BrokerSyncController;

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

    // Manage Users & Permissions
    Route::get('/users', [PermissionController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/permissions', [PermissionController::class, 'edit'])->name('admin.users.permissions.edit');
    Route::post('/users/{user}/permissions', [PermissionController::class, 'update'])->name('admin.users.permissions.update');

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
    Route::get('/support', [App\Http\Controllers\SupportController::class, 'index'])->name('support');

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
});
