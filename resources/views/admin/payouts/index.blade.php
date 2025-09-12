@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-dark fw-bold">
                        <i class="fas fa-money-bill-wave me-2 text-success"></i>
                        Manage Payouts
                    </h1>
                    <p class="text-muted mb-0">Review and manage affiliate payout requests</p>
                </div>
                <div>
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="fas fa-dollar-sign me-1"></i>
                        ${{ number_format($stats['total_amount'], 2) }} Total
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">{{ $stats['pending_requests'] }}</div>
                            <div class="small">Pending Requests</div>
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
                            <div class="h4 mb-0">{{ $stats['approved_requests'] }}</div>
                            <div class="small">Approved</div>
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
                            <div class="h4 mb-0">{{ $stats['rejected_requests'] }}</div>
                            <div class="small">Rejected</div>
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
                            <i class="fas fa-hourglass-half fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">${{ number_format($stats['pending_amount'], 2) }}</div>
                            <div class="small">Pending Amount</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payout Requests Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 text-dark">
                    <i class="fas fa-list me-2"></i>
                    Payout Requests
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted">Request ID</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted">Affiliate</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted">Amount</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted">Payment Method</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted">Status</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted">Requested</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payoutRequests as $request)
                        <tr class="border-bottom">
                            <td class="py-3 px-4">
                                <div class="fw-semibold text-dark">#{{ $request->id }}</div>
                                <div class="text-muted small">{{ $request->created_at->format('M d, Y') }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $request->user->name }}</div>
                                        <div class="text-muted small">{{ $request->user->email }}</div>
                                        @if($request->affiliate->affiliate_code)
                                            <span class="badge bg-info-subtle text-info small">
                                                {{ $request->affiliate->affiliate_code }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="fw-bold text-success fs-5">${{ number_format($request->amount, 2) }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    @if($request->payment_method === 'crypto')
                                        <i class="fas fa-coins text-warning me-2"></i>
                                        <span class="text-capitalize">{{ $request->payment_method }}</span>
                                        <div class="text-muted small d-block">{{ $request->payment_details['network'] ?? 'N/A' }}</div>
                                    @else
                                        <i class="fas fa-university text-primary me-2"></i>
                                        <span class="text-capitalize">{{ $request->payment_method }}</span>
                                        <div class="text-muted small d-block">{{ $request->payment_details['bank_name'] ?? 'N/A' }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="badge {{ $request->getStatusBadgeClass() }} text-white px-2 py-1">
                                    {{ $request->getStatusText() }}
                                </span>
                                @if($request->processed_at)
                                    <div class="text-muted small mt-1">
                                        {{ $request->processed_at->format('M d, Y H:i') }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-muted small">{{ $request->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.payouts.show', $request) }}" 
                                       class="btn btn-outline-info btn-sm" 
                                       data-bs-toggle="tooltip" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($request->isPending())
                                        <form action="{{ route('admin.payouts.approve', $request) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-outline-success btn-sm" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Approve Request"
                                                    onclick="return confirm('Are you sure you want to approve this payout request?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $request->id }}"
                                                title="Reject Request">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @elseif($request->isApproved())
                                        <form action="{{ route('admin.payouts.process', $request) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-outline-primary btn-sm" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Mark as Processed"
                                                    onclick="return confirm('Are you sure you want to mark this as processed?')">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.payouts.reject', $request) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Payout Request</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Reason for rejection <span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="admin_notes" rows="3" required placeholder="Please provide a reason for rejecting this payout request..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Reject Request</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $payoutRequests->firstItem() ?? 0 }} to {{ $payoutRequests->lastItem() ?? 0 }} of {{ $payoutRequests->total() }} requests
                </div>
                <div>
                    {{ $payoutRequests->links() }}
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

.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1) !important;
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
</script>
@endsection
