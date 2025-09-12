@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-dark fw-bold">
                        <i class="fas fa-user-cog me-2 text-primary"></i>
                        Manage User: {{ $user->name }}
                    </h1>
                    <p class="text-muted mb-0">Complete user management and administration</p>
                </div>
                <div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- User Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">{{ $stats['total_referrals'] }}</div>
                            <div class="small">Total Referrals</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">${{ number_format($stats['total_earnings'], 2) }}</div>
                            <div class="small">Total Earnings</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">${{ number_format($stats['pending_earnings'], 2) }}</div>
                            <div class="small">Pending Earnings</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">{{ $stats['broker_accounts'] }}</div>
                            <div class="small">Broker Accounts</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="userTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                                <i class="fas fa-user me-2"></i>Profile
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="affiliate-tab" data-bs-toggle="tab" data-bs-target="#affiliate" type="button" role="tab">
                                <i class="fas fa-handshake me-2"></i>Affiliate
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="plan-tab" data-bs-toggle="tab" data-bs-target="#plan" type="button" role="tab">
                                <i class="fas fa-crown me-2"></i>Plan Management
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" role="tab">
                                <i class="fas fa-cog me-2"></i>Permissions
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                                <i class="fas fa-history me-2"></i>Activity
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="userTabsContent">
                        <!-- Profile Tab -->
                        <div class="tab-pane fade show active" id="profile" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Basic Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-semibold">Name:</td>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Email:</td>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">User ID:</td>
                                            <td>{{ $user->id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Email Verified:</td>
                                            <td>
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success">Verified</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Created:</td>
                                            <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Last Login:</td>
                                            <td>{{ $user->updated_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="mb-3">Account Status</h5>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-outline-primary" onclick="editUserProfile({{ $user->id }})">
                                            <i class="fas fa-edit me-2"></i>Edit Profile
                                        </button>
                                        <button class="btn btn-outline-warning" onclick="resetPassword({{ $user->id }})">
                                            <i class="fas fa-key me-2"></i>Reset Password
                                        </button>
                                        <button class="btn btn-outline-danger" onclick="suspendUser({{ $user->id }})">
                                            <i class="fas fa-ban me-2"></i>Suspend User
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Affiliate Tab -->
                        <div class="tab-pane fade" id="affiliate" role="tabpanel">
                            @if($user->affiliate)
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="mb-3">Affiliate Information</h5>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="fw-semibold">Status:</td>
                                                <td>
                                                    <span class="badge {{ $user->affiliate->status === 'pending' ? 'bg-warning' : ($user->affiliate->status === 'approved' ? 'bg-success' : ($user->affiliate->status === 'rejected' ? 'bg-danger' : 'bg-secondary')) }}">
                                                        {{ ucfirst($user->affiliate->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Affiliate Code:</td>
                                                <td><code>{{ $user->affiliate->affiliate_code }}</code></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Commission Rate:</td>
                                                <td>{{ $user->affiliate->commission_rate }}%</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Total Earnings:</td>
                                                <td>${{ number_format($user->affiliate->total_earnings, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Pending Earnings:</td>
                                                <td>${{ number_format($user->affiliate->pending_earnings, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Paid Earnings:</td>
                                                <td>${{ number_format($user->affiliate->paid_earnings, 2) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="mb-3">Affiliate Actions</h5>
                                        <div class="d-grid gap-2">
                                            @if($user->affiliate->status === 'pending')
                                                <button class="btn btn-success" onclick="approveAffiliate({{ $user->id }})">
                                                    <i class="fas fa-check me-2"></i>Approve Application
                                                </button>
                                                <button class="btn btn-danger" onclick="rejectAffiliate({{ $user->id }})">
                                                    <i class="fas fa-times me-2"></i>Reject Application
                                                </button>
                                            @elseif($user->affiliate->status === 'approved')
                                                <button class="btn btn-warning" onclick="suspendAffiliate({{ $user->id }})">
                                                    <i class="fas fa-ban me-2"></i>Suspend Affiliate
                                                </button>
                                            @elseif($user->affiliate->status === 'suspended')
                                                <button class="btn btn-success" onclick="reactivateAffiliate({{ $user->id }})">
                                                    <i class="fas fa-undo me-2"></i>Reactivate Affiliate
                                                </button>
                                            @elseif($user->affiliate->status === 'rejected')
                                                <button class="btn btn-success" onclick="approveAffiliate({{ $user->id }})">
                                                    <i class="fas fa-check me-2"></i>Approve Application
                                                </button>
                                            @endif
                                            <button class="btn btn-outline-info" onclick="viewAffiliateDetails({{ $user->id }})">
                                                <i class="fas fa-eye me-2"></i>View Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Affiliate Account</h5>
                                    <p class="text-muted">This user doesn't have an affiliate account yet.</p>
                                    <button class="btn btn-primary" onclick="createAffiliate({{ $user->id }})">
                                        <i class="fas fa-user-plus me-2"></i>Create Affiliate Account
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Plan Management Tab -->
                        <div class="tab-pane fade" id="plan" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Current Plan</h5>
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                @if($user->plan)
                                                    {{ ucfirst($user->plan) }} Plan
                                                @else
                                                    No Plan Assigned
                                                @endif
                                            </h6>
                                            <p class="card-text text-muted">
                                                @if($user->plan)
                                                    Current subscription plan details
                                                @else
                                                    User has no active subscription plan
                                                @endif
                                            </p>
                                            @if($user->stripe_subscription_id)
                                                <div class="mt-3">
                                                    <small class="text-muted">Stripe Subscription ID:</small><br>
                                                    <code class="small">{{ $user->stripe_subscription_id }}</code>
                                                </div>
                                            @endif
                                            @if($user->subscription_ends_at)
                                                <div class="mt-2">
                                                    <small class="text-muted">Next Billing:</small><br>
                                                    <strong>{{ $user->subscription_ends_at->format('M d, Y') }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="mb-3">Plan Actions</h5>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary" onclick="manageUserPlan({{ $user->id }}, '{{ $user->plan ?? 'none' }}')">
                                            <i class="fas fa-crown me-2"></i>Change Plan
                                        </button>
                                        @if($user->stripe_subscription_id)
                                            <button class="btn btn-outline-info" onclick="viewStripeSubscription('{{ $user->stripe_subscription_id }}')">
                                                <i class="fas fa-external-link-alt me-2"></i>View in Stripe
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="suspendStripePlan({{ $user->id }})">
                                                <i class="fas fa-pause me-2"></i>Suspend Subscription
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="cancelStripePlan({{ $user->id }})">
                                                <i class="fas fa-times me-2"></i>Cancel Subscription
                                            </button>
                                        @else
                                            <button class="btn btn-outline-success" onclick="createStripeSubscription({{ $user->id }})">
                                                <i class="fas fa-plus me-2"></i>Create Stripe Subscription
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Stripe Customer Info -->
                            @if($user->stripe_customer_id)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="fab fa-stripe me-2"></i>
                                                Stripe Customer Information
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>Customer ID:</strong><br>
                                                    <code>{{ $user->stripe_customer_id }}</code>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Actions:</strong><br>
                                                    <a href="https://dashboard.stripe.com/customers/{{ $user->stripe_customer_id }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-external-link-alt me-1"></i>View in Stripe Dashboard
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Permissions Tab -->
                        <div class="tab-pane fade" id="permissions" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Current Roles</h5>
                                    @if($user->roles->count() > 0)
                                        @foreach($user->getRoleNames() as $role)
                                            <span class="badge bg-primary me-2 mb-2">{{ ucfirst($role) }}</span>
                                        @endforeach
                                    @else
                                        <p class="text-muted">No roles assigned</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h5 class="mb-3">Permissions</h5>
                                    @if($user->permissions->count() > 0)
                                        <p class="text-muted">{{ $user->permissions->count() }} permissions assigned</p>
                                    @else
                                        <p class="text-muted">No direct permissions assigned</p>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.users.permissions.edit', $user->id) }}" class="btn btn-primary">
                                    <i class="fas fa-cog me-2"></i>Manage Permissions
                                </a>
                            </div>
                        </div>

                        <!-- Activity Tab -->
                        <div class="tab-pane fade" id="activity" role="tabpanel">
                            <h5 class="mb-3">Recent Activity</h5>
                            <div class="list-group">
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Account Created</h6>
                                        <small>{{ $user->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">User account was created</p>
                                </div>
                                @if($user->affiliate)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Affiliate Application</h6>
                                        <small>{{ $user->affiliate->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">Applied for affiliate program</p>
                                </div>
                                @endif
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Last Activity</h6>
                                        <small>{{ $user->updated_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">Last account update</p>
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
// User Management Functions
function editUserProfile(userId) {
    alert('Edit User Profile for ID: ' + userId + '\n\nThis feature can:\n- Open edit modal\n- Redirect to edit page\n- Show user form with current data');
}

function resetPassword(userId) {
    if (confirm('Reset password for this user?')) {
        alert('Password reset for User ID: ' + userId + '\n\nThis feature can:\n- Generate new password\n- Send reset email\n- Force password change on next login');
    }
}

function suspendUser(userId) {
    if (confirm('Suspend this user account?')) {
        alert('Suspend User ID: ' + userId + '\n\nThis feature can:\n- Disable user account\n- Prevent login\n- Send suspension notification');
    }
}

// Affiliate Functions
function approveAffiliate(userId) {
    if (confirm('Approve this affiliate application?')) {
        processAffiliateAction(userId, 'approve');
    }
}

function rejectAffiliate(userId) {
    showReasonModal(userId, 'reject', 'Are you sure you want to reject this affiliate application?');
}

function suspendAffiliate(userId) {
    showReasonModal(userId, 'suspend', 'Are you sure you want to suspend this affiliate?');
}

function reactivateAffiliate(userId) {
    if (confirm('Reactivate this affiliate?')) {
        processAffiliateAction(userId, 'approve');
    }
}

function createAffiliate(userId) {
    if (confirm('Create affiliate account for this user?')) {
        alert('Create Affiliate Account for User ID: ' + userId + '\n\nThis feature can:\n- Create affiliate record\n- Generate affiliate code\n- Set commission rate\n- Send welcome email');
    }
}

function viewAffiliateDetails(userId) {
    alert('View affiliate details for user ID: ' + userId + '\n\nThis feature can show:\n- Application date\n- Commission rate\n- Total earnings\n- Referral count\n- Notes\n- Status history');
}

// Plan Management Functions
function manageUserPlan(userId, currentPlan) {
    const planModal = `
        <div class="modal fade" id="planModal" tabindex="-1" aria-labelledby="planModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="planModalLabel">Manage User Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Current Plan: <strong>${currentPlan}</strong></p>
                        <div class="mb-3">
                            <label for="planSelect" class="form-label">Select New Plan</label>
                            <select class="form-select" id="planSelect">
                                <option value="none" ${currentPlan === 'none' ? 'selected' : ''}>No Plan</option>
                                <option value="starter" ${currentPlan === 'starter' ? 'selected' : ''}>Eros Starter - $97/month</option>
                                <option value="pro" ${currentPlan === 'pro' ? 'selected' : ''}>Eros Pro - $197/month</option>
                                <option value="diablo" ${currentPlan === 'diablo' ? 'selected' : ''}>Eros Diablo - $497/month</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="planNotes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" id="planNotes" rows="3" placeholder="Add any notes about this plan change..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="updateUserPlan(${userId})">Update Plan</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('planModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', planModal);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('planModal'));
    modal.show();
}

function updateUserPlan(userId) {
    const newPlan = document.getElementById('planSelect').value;
    const notes = document.getElementById('planNotes').value;
    
    alert('Update User Plan:\nUser ID: ' + userId + '\nNew Plan: ' + newPlan + '\nNotes: ' + notes);
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('planModal'));
    if (modal) {
        modal.hide();
    }
}

function suspendPlan(userId) {
    if (confirm('Suspend this user\'s plan?')) {
        alert('Suspend plan for User ID: ' + userId);
    }
}

function cancelPlan(userId) {
    if (confirm('Cancel this user\'s plan?')) {
        alert('Cancel plan for User ID: ' + userId);
    }
}

// Stripe Integration Functions
function viewStripeSubscription(subscriptionId) {
    window.open('https://dashboard.stripe.com/subscriptions/' + subscriptionId, '_blank');
}

function createStripeSubscription(userId) {
    if (confirm('Create a Stripe subscription for this user? This will require them to add a payment method.')) {
        alert('Create Stripe subscription for User ID: ' + userId + '\n\nThis feature can:\n- Create Stripe customer\n- Generate subscription\n- Send payment setup email\n- Redirect to payment page');
    }
}

function suspendStripePlan(userId) {
    if (confirm('Suspend this user\'s Stripe subscription?')) {
        alert('Suspend Stripe subscription for User ID: ' + userId + '\n\nThis feature can:\n- Pause subscription in Stripe\n- Update user status\n- Send notification email');
    }
}

function cancelStripePlan(userId) {
    if (confirm('Cancel this user\'s Stripe subscription? This will immediately stop billing.')) {
        alert('Cancel Stripe subscription for User ID: ' + userId + '\n\nThis feature can:\n- Cancel subscription in Stripe\n- Update user plan to null\n- Send cancellation email');
    }
}

// Affiliate Action Processing
function processAffiliateAction(userId, action, reason = '') {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let url = '';
    
    switch(action) {
        case 'approve':
            url = `/admin/affiliates/${userId}/approve`;
            break;
        case 'reject':
            url = `/admin/affiliates/${userId}/reject`;
            break;
        case 'suspend':
            url = `/admin/affiliates/${userId}/suspend`;
            break;
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            notes: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert('error', data.message || 'An error occurred while processing the request.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while processing the request.');
    });
}

// Show modal for actions that require a reason
function showReasonModal(userId, action, message) {
    const modalHtml = `
        <div class="modal fade" id="reasonModal" tabindex="-1" aria-labelledby="reasonModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reasonModalLabel">${action === 'suspend' ? 'Suspend Affiliate' : 'Reject Application'}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>${message}</p>
                        <div class="mb-3">
                            <label for="reasonText" class="form-label">
                                Reason <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="reasonText" rows="4" 
                                placeholder="Please provide a detailed reason for this action (minimum 10 characters)"></textarea>
                            <div class="form-text">This reason will be visible to the affiliate.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-${action === 'suspend' ? 'warning' : 'danger'}" 
                                onclick="processAffiliateActionWithReason(${userId}, '${action}')">
                            ${action === 'suspend' ? 'Suspend' : 'Reject'}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('reasonModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('reasonModal'));
    modal.show();
}

// Process affiliate action with reason
function processAffiliateActionWithReason(userId, action) {
    const reason = document.getElementById('reasonText').value.trim();
    
    if (reason.length < 10) {
        alert('Please provide a reason with at least 10 characters.');
        return;
    }
    
    processAffiliateAction(userId, action, reason);
}

// Show alert message
function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999;" role="alert">
            <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endsection
