<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/account', [App\Http\Controllers\AccountController::class, 'index'])->name('account');
    Route::get('/roadmap', [App\Http\Controllers\RoadmapController::class, 'index'])->name('roadmap');
    Route::get('/affiliate', [App\Http\Controllers\AffiliateController::class, 'index'])->name('affiliate');
    Route::get('/expert-advisors', [App\Http\Controllers\ExpertAdvisorController::class, 'index'])->name('expert.advisors');
    Route::get('/support', [App\Http\Controllers\SupportController::class, 'index'])->name('support');
});
