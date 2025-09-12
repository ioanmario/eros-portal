@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1 text-dark fw-bold">
                        <i class="fas fa-money-bill-wave me-2 text-success"></i>
                        Payout Request #{{ $payoutRequest->id }}
                    </h1>
                    <p class="text-muted mb-0">Review payout request details and take action</p>
                </div>
                <div>
                    <span class="badge {{ $payoutRequest->getStatusBadgeClass() }} fs-6 px-3 py-2">
                        {{ $payoutRequest->getStatusText() }}
                    </span>
                </div>
            </div>

            <div class="row">
                <!-- Main Details -->
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 text-dark">
                                <i class="fas fa-info-circle me-2"></i>
                                Request Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Request ID</label>
                                        <div class="text-dark">#{{ $payoutRequest->id }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Requested Amount</label>
                                        <div class="h4 text-success mb-0">${{ number_format($payoutRequest->amount, 2) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Payment Method</label>
                                        <div class="d-flex align-items-center">
                                            @if($payoutRequest->payment_method === 'crypto')
                                                <i class="fas fa-coins text-warning me-2"></i>
                                                <span class="text-capitalize">{{ $payoutRequest->payment_method }}</span>
                                            @else
                                                <i class="fas fa-university text-primary me-2"></i>
                                                <span class="text-capitalize">{{ $payoutRequest->payment_method }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Requested Date</label>
                                        <div class="text-dark">{{ $payoutRequest->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Details -->
                            <hr class="my-4">
                            <h6 class="fw-semibold mb-3">Payment Details</h6>
                            <div class="row">
                                @if($payoutRequest->payment_method === 'crypto')
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Network</label>
                                            <div class="text-dark">{{ $payoutRequest->payment_details['network'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Wallet Address</label>
                                            <div class="text-dark font-monospace">{{ $payoutRequest->payment_details['wallet_address'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Account Holder</label>
                                            <div class="text-dark">{{ $payoutRequest->payment_details['account_holder'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Account Number</label>
                                            <div class="text-dark font-monospace">{{ $payoutRequest->payment_details['account_number'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Bank Name</label>
                                            <div class="text-dark">{{ $payoutRequest->payment_details['bank_name'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">SWIFT/BIC</label>
                                            <div class="text-dark font-monospace">{{ $payoutRequest->payment_details['swift'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if($payoutRequest->admin_notes)
                                <hr class="my-4">
                                <h6 class="fw-semibold mb-3">Admin Notes</h6>
                                <div class="alert alert-info">
                                    {{ $payoutRequest->admin_notes }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Affiliate Info -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 text-dark">
                                <i class="fas fa-user me-2"></i>
                                Affiliate Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">{{ $payoutRequest->user->name }}</div>
                                    <div class="text-muted small">{{ $payoutRequest->user->email }}</div>
                                </div>
                            </div>
                            
                            <div class="mb-2">
                                <span class="text-muted small">Affiliate Code:</span>
                                <span class="badge bg-info text-white">{{ $payoutRequest->affiliate->affiliate_code }}</span>
                            </div>
                            
                            <div class="mb-2">
                                <span class="text-muted small">Commission Rate:</span>
                                <span class="fw-semibold">{{ $payoutRequest->affiliate->commission_rate }}%</span>
                            </div>
                            
                            <div class="mb-2">
                                <span class="text-muted small">Total Earnings:</span>
                                <span class="fw-semibold">${{ number_format($payoutRequest->affiliate->total_earnings, 2) }}</span>
                            </div>
                            
                            <div class="mb-2">
                                <span class="text-muted small">Paid Earnings:</span>
                                <span class="fw-semibold">${{ number_format($payoutRequest->affiliate->paid_earnings, 2) }}</span>
                            </div>
                            
                            <div class="mb-0">
                                <span class="text-muted small">Available:</span>
                                <span class="fw-semibold text-success">${{ number_format($payoutRequest->affiliate->getAvailableEarnings(), 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($payoutRequest->isPending())
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="card-title mb-0 text-dark">
                                    <i class="fas fa-cogs me-2"></i>
                                    Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.payouts.approve', $payoutRequest) }}" method="POST" class="mb-3">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-success w-100" 
                                            onclick="return confirm('Are you sure you want to approve this payout request?')">
                                        <i class="fas fa-check me-2"></i>Approve Request
                                    </button>
                                </form>
                                
                                <button type="button" 
                                        class="btn btn-danger w-100" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#rejectModal">
                                    <i class="fas fa-times me-2"></i>Reject Request
                                </button>
                            </div>
                        </div>
                    @elseif($payoutRequest->isApproved())
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="card-title mb-0 text-dark">
                                    <i class="fas fa-cogs me-2"></i>
                                    Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.payouts.process', $payoutRequest) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-primary w-100" 
                                            onclick="return confirm('Are you sure you want to mark this as processed?')">
                                        <i class="fas fa-check-double me-2"></i>Mark as Processed
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Processing Info -->
                    @if($payoutRequest->processed_at)
                        <div class="card shadow-sm">
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="card-title mb-0 text-dark">
                                    <i class="fas fa-history me-2"></i>
                                    Processing Info
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <span class="text-muted small">Processed:</span>
                                    <div class="fw-semibold">{{ $payoutRequest->processed_at->format('M d, Y H:i') }}</div>
                                </div>
                                
                                @if($payoutRequest->processedBy)
                                    <div class="mb-0">
                                        <span class="text-muted small">Processed by:</span>
                                        <div class="fw-semibold">{{ $payoutRequest->processedBy->name }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.payouts.reject', $payoutRequest) }}" method="POST">
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

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 16px;
}
</style>
@endsection
