@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 920px;">
    <h1 class="h4 mb-3">Profile</h1>
    <p class="text-muted mb-4">Manage your account details, plans and security.</p>

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
    </ul>

    <div class="tab-content pt-3">
        <div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="settings-tab">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
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
                    <p class="text-muted mb-0">Coming soon: manage subscriptions, upgrades, and billing.</p>
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


