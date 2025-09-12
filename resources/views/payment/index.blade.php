@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="h2 mb-3 text-dark fw-bold">
                <i class="fas fa-crown me-2 text-primary"></i>
                Choose Your Trading Plan
            </h1>
            <p class="text-muted lead">Unlock your potential with our professional trading plans</p>
        </div>
    </div>

    <!-- Plans Grid -->
    <div class="row g-4 mb-5">
        @foreach($plans as $planKey => $plan)
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0 plan-card {{ $user->plan === $planKey ? 'border-primary' : '' }}">
                @if($user->plan === $planKey)
                <div class="card-header bg-primary text-white text-center">
                    <i class="fas fa-check-circle me-2"></i>Current Plan
                </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <div class="text-center mb-4">
                        <h3 class="h4 mb-2">{{ $plan['name'] }}</h3>
                        <div class="display-4 fw-bold text-primary mb-2">${{ $plan['price'] }}</div>
                        <div class="text-muted">per month</div>
                    </div>
                    
                    <p class="text-muted text-center mb-4">{{ $plan['description'] }}</p>
                    
                    <ul class="list-unstyled mb-4 flex-grow-1">
                        @foreach($plan['features'] as $feature)
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            {{ $feature }}
                        </li>
                        @endforeach
                    </ul>
                    
                    <div class="mt-auto">
                        @if($user->plan === $planKey)
                            <button class="btn btn-outline-primary w-100" disabled>
                                <i class="fas fa-check me-2"></i>Current Plan
                            </button>
                        @else
                            <button class="btn btn-primary w-100" onclick="selectPlan('{{ $planKey }}')">
                                <i class="fas fa-credit-card me-2"></i>Subscribe Now
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Current Subscription Info -->
    @if($user->plan)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-1">Current Subscription</h5>
                            <p class="text-muted mb-0">
                                You're currently subscribed to the <strong>{{ ucfirst($user->plan) }}</strong> plan.
                                @if($user->subscription_ends_at)
                                    Next billing date: {{ $user->subscription_ends_at->format('M d, Y') }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="{{ route('payment.subscription') }}" class="btn btn-outline-primary me-2">
                                <i class="fas fa-cog me-1"></i>Manage
                            </a>
                            <a href="{{ route('payment.billing') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-credit-card me-1"></i>Billing
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Features Comparison -->
    <div class="row">
        <div class="col-12">
            <h3 class="h4 mb-4 text-center">Why Choose Eros Trading Plans?</h3>
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="p-4">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h5>Secure & Reliable</h5>
                        <p class="text-muted">Bank-level security with encrypted transactions and data protection.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="p-4">
                        <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                        <h5>24/7 Support</h5>
                        <p class="text-muted">Round-the-clock customer support to help you succeed in your trading journey.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="p-4">
                        <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                        <h5>Proven Results</h5>
                        <p class="text-muted">Join thousands of successful traders who have grown their accounts with our system.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stripe Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">Complete Your Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p>Redirecting to secure payment...</p>
            </div>
        </div>
    </div>
</div>

<style>
.plan-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.plan-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
}

.plan-card.border-primary {
    box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
}

.card-header.bg-primary {
    background: linear-gradient(135deg, #0d6efd, #0b5ed7) !important;
}
</style>

<script>
function selectPlan(plan) {
    // Show loading modal
    const modal = new bootstrap.Modal(document.getElementById('checkoutModal'));
    modal.show();
    
    // Create checkout session
    fetch('{{ route("payment.checkout") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            plan: plan
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.session_id) {
            // Redirect to Stripe Checkout
            const stripe = Stripe('{{ config("services.stripe.key") }}');
            stripe.redirectToCheckout({
                sessionId: data.session_id
            });
        } else {
            modal.hide();
            alert('Error: ' + (data.error || 'Failed to create checkout session'));
        }
    })
    .catch(error => {
        modal.hide();
        console.error('Error:', error);
        alert('An error occurred while processing your request.');
    });
}

// Initialize Stripe
document.addEventListener('DOMContentLoaded', function() {
    // Stripe is loaded from CDN in the layout
});
</script>

<!-- Stripe.js CDN -->
<script src="https://js.stripe.com/v3/"></script>
@endsection
