@extends('layouts.app')

@section('title', 'Admin - Support Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 fw-bold">
                        <i class="fas fa-headset me-2 text-primary"></i>
                        Support Management
                    </h1>
                    <p class="text-muted mb-0">Manage and respond to support tickets</p>
                </div>
                <div>
                    <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#bulkActionModal">
                        <i class="fas fa-tasks me-2"></i>Bulk Actions
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-ticket-alt fa-2x text-primary mb-2"></i>
                    <h4 class="mb-1">{{ $stats['total'] }}</h4>
                    <p class="text-muted mb-0">Total Tickets</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                    <h4 class="mb-1">{{ $stats['open'] }}</h4>
                    <p class="text-muted mb-0">Open</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h4 class="mb-1">{{ $stats['resolved'] }}</h4>
                    <p class="text-muted mb-0">Resolved</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-archive fa-2x text-secondary mb-2"></i>
                    <h4 class="mb-1">{{ $stats['closed'] }}</h4>
                    <p class="text-muted mb-0">Closed</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                    <h4 class="mb-1">{{ $stats['urgent'] }}</h4>
                    <p class="text-muted mb-0">Urgent</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="">All Statuses</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" name="priority" id="priority">
                                <option value="">All Priorities</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" name="category" id="category">
                                <option value="">All Categories</option>
                                <option value="technical" {{ request('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                                <option value="billing" {{ request('category') == 'billing' ? 'selected' : '' }}>Billing</option>
                                <option value="account" {{ request('category') == 'account' ? 'selected' : '' }}>Account</option>
                                <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>General</option>
                                <option value="bug_report" {{ request('category') == 'bug_report' ? 'selected' : '' }}>Bug Report</option>
                                <option value="feature_request" {{ request('category') == 'feature_request' ? 'selected' : '' }}>Feature Request</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="assigned_to" class="form-label">Assigned To</label>
                            <select class="form-select" name="assigned_to" id="assigned_to">
                                <option value="">All Assignees</option>
                                <option value="unassigned" {{ request('assigned_to') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ request('assigned_to') == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('admin.support.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">Support Tickets</h5>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label" for="selectAll">
                                Select All
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($tickets->count() > 0)
                        <form id="bulkActionForm" method="POST" action="{{ route('admin.support.bulk') }}">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0 py-3 px-4 fw-semibold">
                                                <input type="checkbox" class="form-check-input" id="selectAllCheckbox">
                                            </th>
                                            <th class="border-0 py-3 px-4 fw-semibold">Ticket #</th>
                                            <th class="border-0 py-3 px-4 fw-semibold">User</th>
                                            <th class="border-0 py-3 px-4 fw-semibold">Subject</th>
                                            <th class="border-0 py-3 px-4 fw-semibold">Category</th>
                                            <th class="border-0 py-3 px-4 fw-semibold">Priority</th>
                                            <th class="border-0 py-3 px-4 fw-semibold">Status</th>
                                            <th class="border-0 py-3 px-4 fw-semibold">Assigned To</th>
                                            <th class="border-0 py-3 px-4 fw-semibold">Created</th>
                                            <th class="border-0 py-3 px-4 fw-semibold">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tickets as $ticket)
                                            <tr>
                                                <td class="py-3 px-4">
                                                    <input type="checkbox" class="form-check-input ticket-checkbox" 
                                                           name="ticket_ids[]" value="{{ $ticket->id }}">
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span class="fw-semibold text-primary">{{ $ticket->ticket_number }}</span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                            {{ substr($ticket->user->name, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">{{ $ticket->user->name }}</div>
                                                            <small class="text-muted">{{ $ticket->user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <div class="d-flex align-items-center">
                                                        <i class="{{ $ticket->category_icon }} me-2 text-muted"></i>
                                                        <div>
                                                            <div class="fw-semibold">{{ Str::limit($ticket->subject, 40) }}</div>
                                                            @if($ticket->messages->count() > 0)
                                                                <small class="text-muted">{{ $ticket->messages->count() }} message(s)</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span class="badge bg-light text-dark px-2 py-1">
                                                        {{ ucfirst($ticket->category) }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span class="badge bg-{{ $ticket->priority_color }} text-white px-2 py-1">
                                                        {{ ucfirst($ticket->priority) }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span class="badge bg-{{ $ticket->status_color }} text-white px-2 py-1">
                                                        {{ ucfirst($ticket->status) }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    @if($ticket->assignedTo)
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                {{ substr($ticket->assignedTo->name, 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold">{{ $ticket->assignedTo->name }}</div>
                                                                <small class="text-muted">Admin</small>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Unassigned</span>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4">
                                                    <div>{{ $ticket->created_at->format('M d, Y') }}</div>
                                                    <small class="text-muted">{{ $ticket->created_at->format('h:i A') }}</small>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <a href="{{ route('admin.support.show', $ticket) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i>View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                        <div class="card-footer bg-white border-0 py-3">
                            {{ $tickets->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-ticket-alt fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Support Tickets Found</h5>
                            <p class="text-muted mb-4">No tickets match your current filters.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkActionForm">
                    @csrf
                    <div class="mb-3">
                        <label for="bulkAction" class="form-label">Action</label>
                        <select class="form-select" id="bulkAction" name="action" required>
                            <option value="">Select an action</option>
                            <option value="assign">Assign to Admin</option>
                            <option value="close">Close Tickets</option>
                            <option value="resolve">Mark as Resolved</option>
                            <option value="delete">Delete Tickets</option>
                        </select>
                    </div>
                    <div class="mb-3" id="assignToField" style="display: none;">
                        <label for="assigned_to" class="form-label">Assign To</label>
                        <select class="form-select" id="assigned_to" name="assigned_to">
                            <option value="">Select Admin</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This action will be applied to all selected tickets.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitBulkAction()">Apply Action</button>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    font-weight: 600;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all functionality
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const ticketCheckboxes = document.querySelectorAll('.ticket-checkbox');
    
    selectAllCheckbox.addEventListener('change', function() {
        ticketCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Bulk action form
    const bulkActionSelect = document.getElementById('bulkAction');
    const assignToField = document.getElementById('assignToField');
    
    bulkActionSelect.addEventListener('change', function() {
        if (this.value === 'assign') {
            assignToField.style.display = 'block';
        } else {
            assignToField.style.display = 'none';
        }
    });
});

function submitBulkAction() {
    const selectedTickets = document.querySelectorAll('.ticket-checkbox:checked');
    if (selectedTickets.length === 0) {
        alert('Please select at least one ticket.');
        return;
    }
    
    const form = document.getElementById('bulkActionForm');
    const formData = new FormData(form);
    
    // Add selected ticket IDs
    selectedTickets.forEach(checkbox => {
        formData.append('ticket_ids[]', checkbox.value);
    });
    
    fetch('{{ route("admin.support.bulk") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the request.');
    });
}
</script>
@endsection
