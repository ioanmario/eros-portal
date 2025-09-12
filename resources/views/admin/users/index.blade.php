@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-dark fw-bold">
                        <i class="fas fa-users me-2 text-primary"></i>
                        User Management
                    </h1>
                    <p class="text-muted mb-0">Manage user accounts, roles, and permissions</p>
                </div>
                <div>
                    <span class="badge bg-primary fs-6 px-3 py-2">
                        <i class="fas fa-users me-1"></i>
                        {{ $users->total() }} Users
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">{{ $users->where('email_verified_at', '!=', null)->count() }}</div>
                            <div class="small">Verified Users</div>
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
                            <i class="fas fa-crown fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">{{ $users->filter(function($user) { return $user->hasRole('admin'); })->count() }}</div>
                            <div class="small">Admin Users</div>
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
                            <i class="fas fa-handshake fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">{{ $users->whereNotNull('affiliate_code')->count() }}</div>
                            <div class="small">Affiliates</div>
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
                            <div class="h4 mb-0">{{ $users->where('email_verified_at', null)->count() }}</div>
                            <div class="small">Pending Verification</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">{{ $users->filter(function($user) { return $user->affiliate && $user->affiliate->status === 'pending'; })->count() }}</div>
                            <div class="small">Pending Affiliate Applications</div>
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
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">{{ $users->filter(function($user) { return $user->affiliate && $user->affiliate->status === 'approved'; })->count() }}</div>
                            <div class="small">Approved Affiliates</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">{{ $users->filter(function($user) { return $user->affiliate && $user->affiliate->status === 'rejected'; })->count() }}</div>
                            <div class="small">Rejected Applications</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-ban fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">{{ $users->filter(function($user) { return $user->affiliate && $user->affiliate->status === 'suspended'; })->count() }}</div>
                            <div class="small">Suspended Affiliates</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 text-dark">
                    <i class="fas fa-table me-2"></i>
                    All Users
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Add User
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted">User</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted">Contact</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted">Affiliate</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted">Affiliate Application</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted">Status</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted">Roles & Permissions</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                        <tr class="border-bottom">
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                        <div class="text-muted small">ID: {{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-dark">{{ $user->email }}</div>
                                @if($user->email_verified_at)
                                    <span class="badge bg-success-subtle text-success small">
                                        <i class="fas fa-check-circle me-1"></i>Verified
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning small">
                                        <i class="fas fa-clock me-1"></i>Pending
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($user->affiliate_code)
                                    <span class="badge bg-info text-white px-2 py-1">
                                        <i class="fas fa-handshake me-1"></i>{{ $user->affiliate_code }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($user->affiliate)
                                    @if($user->affiliate->status === 'pending')
                                        <span class="badge bg-warning text-white px-2 py-1">
                                            <i class="fas fa-clock me-1"></i>Pending Review
                                        </span>
                                        <div class="small text-muted mt-1">
                                            Applied: {{ $user->affiliate->created_at->format('M d, Y') }}
                                        </div>
                                    @elseif($user->affiliate->status === 'approved')
                                        <span class="badge bg-success text-white px-2 py-1">
                                            <i class="fas fa-check-circle me-1"></i>Approved
                                        </span>
                                        <div class="small text-muted mt-1">
                                            Approved: {{ $user->affiliate->approved_at ? $user->affiliate->approved_at->format('M d, Y') : 'N/A' }}
                                        </div>
                                    @elseif($user->affiliate->status === 'rejected')
                                        <span class="badge bg-danger text-white px-2 py-1">
                                            <i class="fas fa-times-circle me-1"></i>Rejected
                                        </span>
                                        <div class="small text-muted mt-1">
                                            Applied: {{ $user->affiliate->created_at->format('M d, Y') }}
                                        </div>
                                    @elseif($user->affiliate->status === 'suspended')
                                        <span class="badge bg-secondary text-white px-2 py-1">
                                            <i class="fas fa-ban me-1"></i>Suspended
                                        </span>
                                        <div class="small text-muted mt-1">
                                            Applied: {{ $user->affiliate->created_at->format('M d, Y') }}
                                        </div>
                                    @endif
                                @else
                                    <span class="text-muted">No Application</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($user->hasRole('admin'))
                                    <span class="badge bg-danger text-white px-2 py-1">
                                        <i class="fas fa-crown me-1"></i>Admin
                                    </span>
                                @else
                                    <span class="badge bg-secondary text-white px-2 py-1">
                                        <i class="fas fa-user me-1"></i>User
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                    @if($user->roles->count() > 0)
                                    <div class="mb-1">
                                        <span class="text-muted small">Roles:</span>
                                        @foreach($user->getRoleNames() as $role)
                                            <span class="badge bg-primary-subtle text-primary me-1">{{ ucfirst($role) }}</span>
                                        @endforeach
                                    </div>
                    @endif
                    @if($user->permissions->count() > 0)
                                    <div>
                                        <span class="text-muted small">Permissions:</span>
                                        <span class="text-muted small">{{ $user->permissions->count() }} assigned</span>
                                    </div>
                                @endif
                                @if($user->roles->count() == 0 && $user->permissions->count() == 0)
                                    <span class="text-muted small">No roles or permissions</span>
                    @endif
                </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.users.manage', $user->id) }}" 
                                   class="btn btn-primary btn-sm" 
                                   data-bs-toggle="tooltip" 
                                   title="Manage User - View all details and actions">
                                    <i class="fas fa-cog me-1"></i>
                                    Manage
                                </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
            </div>
        </div>
        <div class="card-footer bg-light border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
                </div>
                <div>
        {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 16px;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.bg-primary-subtle {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

/* Action Buttons Styling */
.d-flex.gap-1 .btn {
    margin: 0 1px;
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}

.d-flex.gap-1 .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Dropdown Styling */
.dropdown-item {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    border-radius: 0.25rem;
    margin: 0.125rem;
}

.dropdown-item:hover {
    background-color: rgba(0, 123, 255, 0.1);
    transform: translateX(2px);
}

.dropdown-item i {
    width: 16px;
    text-align: center;
}

.dropdown-menu {
    border: 1px solid rgba(0, 0, 0, 0.1);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border-radius: 0.375rem;
    min-width: 200px;
    padding: 0.25rem;
}

/* Status-based button colors for affiliate dropdown */
.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

.btn-success {
    background-color: #198754;
    border-color: #198754;
    color: #fff;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #fff;
}

/* Button color variations */
.btn-outline-primary:hover {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

.btn-outline-info:hover {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: #000;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #fff;
}

.btn-outline-success:hover {
    background-color: #198754;
    border-color: #198754;
    color: #fff;
}

.btn-outline-dark:hover {
    background-color: #212529;
    border-color: #212529;
    color: #fff;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Function to manage affiliate applications
function manageAffiliate(userId, status) {
    let action = '';
    let message = '';
    let needsReason = false;
    
    switch(status) {
        case 'pending':
            action = 'approve';
            message = 'Are you sure you want to approve this affiliate application?';
            break;
        case 'approved':
            action = 'suspend';
            message = 'Are you sure you want to suspend this affiliate?';
            needsReason = true;
            break;
        case 'suspended':
            action = 'approve';
            message = 'Are you sure you want to reactivate this affiliate?';
            break;
        case 'rejected':
            action = 'approve';
            message = 'Are you sure you want to approve this previously rejected application?';
            break;
    }
    
    if (needsReason) {
        showReasonModal(userId, action, message);
    } else {
        if (confirm(message)) {
            processAffiliateAction(userId, action);
        }
    }
}

// Function to reject affiliate application
function rejectAffiliate(userId) {
    showReasonModal(userId, 'reject', 'Are you sure you want to reject this affiliate application?');
}

// Function to view affiliate details
function viewAffiliateDetails(userId) {
    // This could open a modal with detailed affiliate information
    // For now, we'll show an alert with the user ID
    alert('View affiliate details for user ID: ' + userId + '\n\nThis feature can be expanded to show:\n- Application date\n- Commission rate\n- Total earnings\n- Referral count\n- Notes\n- Status history');
}

// All user management functions have been moved to the manage page

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

// Process affiliate action
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
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
    button.disabled = true;
    
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
            // Show success message
            showAlert('success', data.message);
            
            // Close modal if open
            const modal = bootstrap.Modal.getInstance(document.getElementById('reasonModal'));
            if (modal) {
                modal.hide();
            }
            
            // Reload page to show updated status
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
    })
    .finally(() => {
        // Restore button state
        button.innerHTML = originalText;
        button.disabled = false;
    });
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
