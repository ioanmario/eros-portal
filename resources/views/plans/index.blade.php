@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">Choose Your Plan</h1>
        <p class="lead text-muted">Unlock the power of automated trading with our flexible plans.</p>
    </div>

    <div class="row g-4">
        @foreach($plans as $key => $plan)
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card h-100 {{ $user->plan === $key ? 'border-primary' : '' }} {{ $key === 'pro' ? 'border-warning' : '' }}">
                @if($key === 'pro')
                    <div class="card-header bg-warning text-dark text-center">
                        <strong>🔥 Most Popular</strong>
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h3 class="h4 mb-3">{{ $plan['name'] }}</h3>
                    
                    <div class="mb-3">
                        <span class="display-6 fw-bold">${{ $plan['price'] }}</span>
                        <span class="text-muted">/month</span>
                    </div>

                    <div class="mb-4">
                        <span class="badge bg-primary fs-6">{{ $plan['accounts'] }} Broker Account{{ $plan['accounts'] !== 1 ? 's' : '' }}</span>
                    </div>

                    <ul class="list-unstyled mb-4">
                        @foreach($plan['features'] as $feature)
                            <li class="mb-2">
                                <span class="text-success me-2">✓</span>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-auto">
                        @if($user->plan === $key)
                            <button class="btn btn-success w-100" disabled>
                                <span class="me-2">✓</span>Current Plan
                            </button>
                        @elseif($plan['price'] === 0)
                            <button class="btn btn-outline-secondary w-100" disabled>
                                Free Plan
                            </button>
                        @else
                            <form method="POST" action="{{ route('plans.checkout') }}" class="d-inline w-100">
                                @csrf
                                <input type="hidden" name="plan" value="{{ $key }}">
                                <button type="submit" class="btn btn-brand w-100">
                                    Upgrade to {{ $plan['name'] }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($user->plan !== 'free')
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h4 class="text-danger mb-3">Cancel Subscription</h4>
                    <p class="text-muted mb-3">You can cancel your subscription anytime. You'll keep access until the end of your billing period.</p>
                    <form method="POST" action="{{ route('plans.cancel') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to cancel your subscription?')">
                            Cancel Subscription
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
