@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1 text-dark fw-bold">
                        <i class="fas fa-chart-line me-2 text-primary"></i>
                        Affiliate Dashboard
                    </h1>
                    <p class="text-muted mb-0">Track your referrals, earnings, and manage payouts</p>
                </div>
                <div>
                    <span class="badge bg-primary fs-6 px-3 py-2">
                        <i class="fas fa-handshake me-1"></i>
                        {{ $affiliate->affiliate_code }}
                    </span>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="h4 mb-0">{{ $referralStats['total_referrals'] }}</div>
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
                                    <div class="h4 mb-0">${{ number_format($referralStats['total_earnings'], 2) }}</div>
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
                                    <div class="h4 mb-0">${{ number_format($referralStats['available_earnings'], 2) }}</div>
                                    <div class="small">Available for Payout</div>
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
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="h4 mb-0">${{ number_format($referralStats['paid_earnings'], 2) }}</div>
                                    <div class="small">Paid Out</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Payout Request Form -->
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 text-dark">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                Request Payout
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($referralStats['available_earnings'] >= 50)
                                <form action="{{ route('affiliate.payout') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                   class="form-control" 
                                                   name="amount" 
                                                   value="{{ min($referralStats['available_earnings'], 1000) }}"
                                                   min="50" 
                                                   max="{{ $referralStats['available_earnings'] }}" 
                                                   step="0.01" 
                                                   required>
                                        </div>
                                        <div class="form-text">
                                            Available: ${{ number_format($referralStats['available_earnings'], 2) }} | Minimum: $50
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                        <select class="form-select" name="payment_method" id="paymentMethod" required>
                                            <option value="">Select payment method</option>
                                            <option value="crypto">Cryptocurrency</option>
                                            <option value="bank">Bank Transfer</option>
                                        </select>
                                    </div>

                                    <div id="paymentDetails">
                                        <!-- Payment details will be populated by JavaScript -->
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane me-2"></i>Submit Payout Request
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    You need at least $50 in available earnings to request a payout. 
                                    Current available: ${{ number_format($referralStats['available_earnings'], 2) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Commissions -->
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 text-dark">
                                <i class="fas fa-history me-2"></i>
                                Recent Commissions
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($recentCommissions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentCommissions as $commission)
                                            <tr>
                                                <td>{{ $commission->referredUser->name }}</td>
                                                <td class="text-success">${{ number_format($commission->amount, 2) }}</td>
                                                <td class="text-muted small">{{ $commission->created_at->format('M d, Y') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('affiliate.earnings') }}" class="btn btn-outline-primary btn-sm">
                                        View All Earnings
                                    </a>
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-chart-line fa-3x mb-3"></i>
                                    <p>No commissions yet. Start referring users to earn!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethod = document.getElementById('paymentMethod');
    const paymentDetails = document.getElementById('paymentDetails');

    function updatePaymentDetails() {
        const method = paymentMethod.value;
        
        if (method === 'crypto') {
            paymentDetails.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">Network <span class="text-danger">*</span></label>
                    <select class="form-select" name="payment_details[network]" required>
                        <option value="">Select network</option>
                        <option value="USDT (TRC20)">USDT (TRC20)</option>
                        <option value="USDT (ERC20)">USDT (ERC20)</option>
                        <option value="BTC">Bitcoin</option>
                        <option value="ETH">Ethereum</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Wallet Address <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="payment_details[wallet_address]" required placeholder="Your wallet address">
                </div>
            `;
        } else if (method === 'bank') {
            paymentDetails.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Account Holder <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="payment_details[account_holder]" required placeholder="Full name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Account Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="payment_details[account_number]" required placeholder="Account number">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="payment_details[bank_name]" required placeholder="Bank name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">SWIFT/BIC <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="payment_details[swift]" required placeholder="SWIFT/BIC code">
                        </div>
                    </div>
                </div>
            `;
        } else {
            paymentDetails.innerHTML = '';
        }
    }

    paymentMethod.addEventListener('change', updatePaymentDetails);
    updatePaymentDetails();
});
</script>
@endsection
