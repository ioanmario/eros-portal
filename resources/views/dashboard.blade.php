@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Hero -->
    <div class="text-center mb-5">
        <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold" style="background: var(--brand-accent, #8B0000); color: #fff;">Members Area</span>
        <h1 class="display-5 fw-bold mt-3">Pass Challenges. Get Payouts. Scale Safely.</h1>
        <p class="lead text-muted mx-auto" style="max-width: 800px;">
            Your central hub for connecting brokers, deploying EAs and tracking your journey. Let’s get you profitable with clarity and control.
        </p>
        <div class="d-flex gap-3 justify-content-center mt-3">
            <a href="{{ route('account') }}" class="btn btn-primary btn-lg">Connect Broker</a>
            <a href="{{ route('expert.advisors') }}" class="btn btn-brand btn-lg shadow fw-semibold">Try Our Free EA</a>
        </div>
    </div>

    <!-- Feature Cards -->
    <div class="row g-4">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h3 class="h5">Account Management</h3>
                    <p class="text-muted">Link MT4/MT5, validate credentials and enable auto-sync analytics.</p>
                    <a href="{{ route('account') }}" class="stretched-link">Manage accounts</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h3 class="h5">Expert Advisors</h3>
                    <p class="text-muted">Download, install and configure our latest EAs with step‑by‑step guides.</p>
                    <a href="{{ route('expert.advisors') }}" class="stretched-link">Explore EAs</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h3 class="h5">Affiliate</h3>
                    <p class="text-muted">Earn commissions by sharing Eros Equity with your audience.</p>
                    <a href="{{ route('affiliate') }}" class="stretched-link">Open affiliate</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h3 class="h5">Roadmap</h3>
                    <p class="text-muted">See what’s shipping next and vote on features.</p>
                    <a href="{{ route('roadmap') }}" class="stretched-link">View roadmap</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h3 class="h5">Support</h3>
                    <p class="text-muted">Need help? Browse guides or contact our team.</p>
                    <a href="{{ route('support') }}" class="stretched-link">Get support</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
