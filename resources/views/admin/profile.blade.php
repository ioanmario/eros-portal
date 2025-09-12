@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 920px;">
    <h1 class="h4 mb-3">Admin Profile</h1>
    <p class="text-muted mb-4">Manage your admin account details, plans and security.</p>

    <ul class="nav nav-tabs" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="true">Settings</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="plans-tab" data-bs-toggle="tab" data-bs-target="#plans" type="button" role="tab" aria-controls="plans" aria-selected="false">Plans</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">Security</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="payout-tab" data-bs-toggle="tab" data-bs-target="#payout" type="button" role="tab" aria-controls="payout" aria-selected="false">Payout Options</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button" role="tab" aria-controls="admin" aria-selected="false">Admin Tools</button>
        </li>
    </ul>

    <div class="tab-content pt-3">
        <div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="settings-tab">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-semibold">Theme</div>
                            <div class="text-muted small">Toggle between light and dark</div>
                        </div>
                        <button id="profileThemeToggle" class="btn btn-outline-secondary">
                            <span id="profileThemeEmoji">🌞</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="plans" role="tabpanel" aria-labelledby="plans-tab">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Your Plan</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary me-3 fs-6">{{ ucfirst($user->plan ?? 'admin') }}</span>
                                <span class="text-muted">Current Plan</span>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <a href="{{ route('plans') }}" class="btn btn-brand btn-sm">
                                Manage Plans
                            </a>
                        </div>
                    </div>
                    <hr>
                    <p class="text-muted mb-0">
                        @if(($user->plan ?? 'admin') === 'admin')
                            You have admin access with full system privileges. <a href="{{ route('admin.dashboard') }}">Access admin dashboard</a> to manage the system.
                        @else
                            You have an active subscription. <a href="{{ route('plans') }}">Manage your plan</a> or view billing details.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Multi‑Factor Authentication (MFA)</h2>
                    <p class="text-muted">Add an extra layer of security to your account.</p>
                    <div class="d-flex gap-3">
                        <button class="btn btn-brand" type="button" disabled>Enable Authenticator App (soon)</button>
                        <button class="btn btn-outline-secondary" type="button" disabled>Manage Backup Codes (soon)</button>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h2 class="h5 mb-3">Account Actions</h2>
                    <p class="text-muted">Manage your account session and security.</p>
                    <div class="d-flex gap-3">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to logout?')">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                        <button class="btn btn-outline-secondary" type="button" disabled>Change Password (soon)</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="payout" role="tabpanel" aria-labelledby="payout-tab">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Payout Options</h2>
                    <p class="text-muted">Choose how you'd like to receive payouts.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Method</label>
                            <select id="payoutMethod" class="form-select">
                                <option value="crypto">Crypto</option>
                                <option value="bank">Bank Transfer</option>
                            </select>
                        </div>
                    </div>

                    <div id="payoutDetails" class="mt-3"></div>
                </div>
            </div>
        </div>

        <!-- Admin Tools Tab -->
        <div class="tab-pane fade" id="admin" role="tabpanel" aria-labelledby="admin-tab">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Administrative Tools</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-2x text-primary mb-3"></i>
                                    <h6 class="card-title">User Management</h6>
                                    <p class="card-text small text-muted">Manage user accounts and permissions</p>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-cog me-1"></i>Manage Users
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-line fa-2x text-success mb-3"></i>
                                    <h6 class="card-title">Admin Dashboard</h6>
                                    <p class="card-text small text-muted">View system overview and statistics</p>
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-tachometer-alt me-1"></i>View Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-eye fa-2x text-warning mb-3"></i>
                                    <h6 class="card-title">Switch to User View</h6>
                                    <p class="card-text small text-muted">View the application as a regular user</p>
                                    <a href="{{ route('admin.switch.user') }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-exchange-alt me-1"></i>Switch View
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <i class="fas fa-shield-alt fa-2x text-info mb-3"></i>
                                    <h6 class="card-title">Admin Privileges</h6>
                                    <p class="card-text small text-muted">Full system access and control</p>
                                    <span class="badge bg-info">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    var btn = document.getElementById('profileThemeToggle');
    var emoji = document.getElementById('profileThemeEmoji');
    function sync(){ emoji.textContent = document.documentElement.classList.contains('dark') ? '🌙' : '🌞'; }
    sync();
    btn.addEventListener('click', function(){
        document.documentElement.classList.toggle('dark');
        try { localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light'); } catch(e) {}
        sync();
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function(){
    var method = document.getElementById('payoutMethod');
    var details = document.getElementById('payoutDetails');
    if (!method || !details) return;
    function render(){
        var val = method.value;
        if (val === 'crypto') {
            details.innerHTML = '<div class="row g-3">\
                <div class="col-md-6">\
                    <label class="form-label">Network</label>\
                    <select class="form-select"><option>USDT (TRC20)</option><option>USDT (ERC20)</option><option>BTC</option><option>ETH</option></select>\
                </div>\
                <div class="col-md-6">\
                    <label class="form-label">Wallet Address</label>\
                    <input type="text" class="form-control" placeholder="Your wallet address">\
                </div>\
            </div>';
        } else {
            details.innerHTML = '<div class="row g-3">\
                <div class="col-md-6">\
                    <label class="form-label">Account Holder</label>\
                    <input type="text" class="form-control" placeholder="Full name">\
                </div>\
                <div class="col-md-6">\
                    <label class="form-label">IBAN / Account Number</label>\
                    <input type="text" class="form-control" placeholder="IBAN or account number">\
                </div>\
                <div class="col-md-6">\
                    <label class="form-label">Bank Name</label>\
                    <input type="text" class="form-control" placeholder="Bank name">\
                </div>\
                <div class="col-md-6">\
                    <label class="form-label">SWIFT / BIC</label>\
                    <input type="text" class="form-control" placeholder="SWIFT/BIC">\
                </div>\
            </div>';
        }
    }
    method.addEventListener('change', render);
    render();
});
</script>
@endsection
