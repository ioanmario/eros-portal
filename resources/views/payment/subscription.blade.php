@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-dark fw-bold">
                        <i class="fas fa-credit-card me-2 text-primary"></i>
                        Subscription Management
                    </h1>
                    <p class="text-muted mb-0">Manage your subscription, billing, and payment methods</p>
                </div>
                <div>
                    <a href="{{ route('payment.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Plans
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Subscription Card -->
    @if($subscription)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-crown me-2"></i>
                        Current Subscription
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Plan</h6>
                            <h4 class="mb-3">{{ ucfirst($user->plan) }} Plan</h4>
                            
                            <h6 class="text-muted">Status</h6>
                            <span class="badge {{ $subscription->status === 'active' ? 'bg-success' : ($subscription->status === 'canceled' ? 'bg-danger' : 'bg-warning') }} fs-6">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Next Billing Date</h6>
                            <h5 class="mb-3">
                                {{ \Carbon\Carbon::createFromTimestamp($subscription->current_period_end)->format('M d, Y') }}
                            </h5>
                            
                            <h6 class="text-muted">Amount</h6>
                            <h5 class="mb-0">${{ number_format($subscription->items->data[0]->price->unit_amount / 100, 2) }}/month</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Subscription Actions</h5>
                    <div class="d-flex flex-wrap gap-2">
                        @if($subscription->status === 'active')
                            <button class="btn btn-warning" onclick="cancelSubscription()">
                                <i class="fas fa-pause me-2"></i>Cancel Subscription
                            </button>
                            <button class="btn btn-info" onclick="changePlan()">
                                <i class="fas fa-exchange-alt me-2"></i>Change Plan
                            </button>
                        @elseif($subscription->status === 'canceled' && $subscription->cancel_at_period_end)
                            <button class="btn btn-success" onclick="resumeSubscription()">
                                <i class="fas fa-play me-2"></i>Resume Subscription
                            </button>
                        @endif
                        <a href="{{ route('payment.billing') }}" class="btn btn-outline-primary">
                            <i class="fas fa-credit-card me-2"></i>Manage Billing
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Payment Methods -->
    @if($paymentMethods && count($paymentMethods->data) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        Payment Methods
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($paymentMethods->data as $paymentMethod)
                        <div class="col-md-6 mb-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-credit-card fa-2x text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-1">
                                                **** **** **** {{ $paymentMethod->card->last4 }}
                                            </h6>
                                            <small class="text-muted">
                                                {{ $paymentMethod->card->brand }} • Expires {{ $paymentMethod->card->exp_month }}/{{ $paymentMethod->card->exp_year }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Upcoming Invoice -->
    @if($upcomingInvoice)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>
                        Upcoming Invoice
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Amount Due</h6>
                            <h4 class="text-primary">${{ number_format($upcomingInvoice->amount_due / 100, 2) }}</h4>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Due Date</h6>
                            <h5>{{ \Carbon\Carbon::createFromTimestamp($upcomingInvoice->due_date)->format('M d, Y') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Billing History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Billing History
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">View your complete billing history and download invoices.</p>
                    <a href="{{ route('payment.billing') }}" class="btn btn-outline-primary">
                        <i class="fas fa-external-link-alt me-2"></i>View Full Billing History
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Plan Modal -->
<div class="modal fade" id="changePlanModal" tabindex="-1" aria-labelledby="changePlanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePlanModalLabel">Change Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="newPlan" class="form-label">Select New Plan</label>
                    <select class="form-select" id="newPlan">
                        <option value="starter" {{ $user->plan === 'starter' ? 'selected' : '' }}>Eros Starter - $97/month</option>
                        <option value="pro" {{ $user->plan === 'pro' ? 'selected' : '' }}>Eros Pro - $197/month</option>
                        <option value="diablo" {{ $user->plan === 'diablo' ? 'selected' : '' }}>Eros Diablo - $497/month</option>
                    </select>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Your subscription will be updated immediately. You'll be charged or credited the prorated amount.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updatePlan()">Update Plan</button>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Subscription Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel your subscription?</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="immediateCancel">
                    <label class="form-check-label" for="immediateCancel">
                        Cancel immediately (no refund)
                    </label>
                </div>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    If you cancel, you'll lose access to all premium features at the end of your current billing period.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Subscription</button>
                <button type="button" class="btn btn-danger" onclick="confirmCancel()">Cancel Subscription</button>
            </div>
        </div>
    </div>
</div>

<script>
function changePlan() {
    const modal = new bootstrap.Modal(document.getElementById('changePlanModal'));
    modal.show();
}

function updatePlan() {
    const newPlan = document.getElementById('newPlan').value;
    
    if (newPlan === '{{ $user->plan }}') {
        alert('You are already on this plan.');
        return;
    }
    
    fetch('{{ route("payment.subscription.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            plan: newPlan
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Plan updated successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Failed to update plan'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating your plan.');
    });
}

function cancelSubscription() {
    const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
    modal.show();
}

function confirmCancel() {
    const immediate = document.getElementById('immediateCancel').checked;
    
    fetch('{{ route("payment.subscription.cancel") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            immediately: immediate
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Subscription cancelled successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Failed to cancel subscription'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while cancelling your subscription.');
    });
}

function resumeSubscription() {
    if (confirm('Resume your subscription?')) {
        fetch('{{ route("payment.subscription.resume") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Subscription resumed successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.error || 'Failed to resume subscription'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while resuming your subscription.');
        });
    }
}
</script>
@endsection
