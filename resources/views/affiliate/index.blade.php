@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Affiliate Program</h1>
                @if($affiliate && $affiliate->isApproved())
                    <a href="{{ route('affiliate.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-chart-line me-2"></i>Dashboard
                    </a>
                @endif
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(!$affiliate)
                <!-- Application Form -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Apply to Become an Affiliate</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-4">
                                    Join our affiliate program and earn 20% commission on every referral's subscription. 
                                    Build your network and grow your income with Eros Equity.
                                </p>

                                <!-- Affiliate Earnings Calculator -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">💰 Affiliate Earnings Calculator</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted mb-3">Calculate your potential monthly earnings based on referrals and sales:</p>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="referrals_count" class="form-label">Total Referrals</label>
                                                    <input type="number" 
                                                           class="form-control" 
                                                           id="referrals_count" 
                                                           value="10" 
                                                           readonly
                                                           style="background-color: #f8f9fa;">
                                                    <div class="form-text">Automatically calculated from total sales</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="commission_rate" class="form-label">Commission Rate (%)</label>
                                                    <input type="number" 
                                                           class="form-control" 
                                                           id="commission_rate" 
                                                           value="20" 
                                                           readonly
                                                           style="background-color: #f8f9fa;">
                                                    <div class="form-text">Fixed at 20% for all affiliates</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="starter_sales" class="form-label">Eros Starter Sales</label>
                                                    <input type="number" 
                                                           class="form-control" 
                                                           id="starter_sales" 
                                                           value="5" 
                                                           min="0" 
                                                           max="1000">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="pro_sales" class="form-label">Eros Pro Sales</label>
                                                    <input type="number" 
                                                           class="form-control" 
                                                           id="pro_sales" 
                                                           value="3" 
                                                           min="0" 
                                                           max="1000">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="diablo_sales" class="form-label">Eros Diablo Sales</label>
                                                    <input type="number" 
                                                           class="form-control" 
                                                           id="diablo_sales" 
                                                           value="2" 
                                                           min="0" 
                                                           max="1000">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-info">
                                            <div class="row text-center">
                                                <div class="col-md-3">
                                                    <div class="h5 text-primary mb-1" id="starter_commission">$97.00</div>
                                                    <small class="text-muted">Starter Commission</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="h5 text-success mb-1" id="pro_commission">$118.20</div>
                                                    <small class="text-muted">Pro Commission</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="h5 text-warning mb-1" id="diablo_commission">$198.80</div>
                                                    <small class="text-muted">Diablo Commission</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="h5 text-danger mb-1" id="total_commission">$414.00</div>
                                                    <small class="text-muted">Total Monthly</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('affiliate.apply') }}">
                                    @csrf
                                    
                                    <input type="hidden" name="commission_rate" id="hidden_commission_rate" value="20">

                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Additional Notes (Optional)</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                  id="notes" 
                                                  name="notes" 
                                                  rows="3" 
                                                  placeholder="Tell us about your marketing experience or any questions...">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>Submit Application
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Program Benefits</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>20% Commission</strong> on all referrals
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Monthly Payouts</strong> via your preferred method
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Real-time Tracking</strong> of your referrals
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Marketing Materials</strong> provided
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Dedicated Support</strong> for affiliates
                                    </li>
                                    <li class="mb-0">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Pyramid Structure</strong> - referrals can become affiliates too
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Commission Structure</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Plan</th>
                                                <th class="text-end">Monthly Commission</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Eros Starter</td>
                                                <td class="text-end">$19.40</td>
                                            </tr>
                                            <tr>
                                                <td>Eros Pro</td>
                                                <td class="text-end">$39.40</td>
                                            </tr>
                                            <tr>
                                                <td>Eros Diablo</td>
                                                <td class="text-end">$99.40</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($affiliate->isPending())
                <!-- Pending Application -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Application Status: Pending</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-clock me-2"></i>
                                    Your affiliate application is currently under review. We'll notify you once it's been processed.
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Application Date:</strong><br>
                                        {{ $affiliate->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Commission Rate:</strong><br>
                                        {{ $affiliate->commission_rate }}%
                                    </div>
                                </div>

                                @if($affiliate->notes)
                                    <div class="mt-3">
                                        <strong>Your Notes:</strong><br>
                                        <p class="text-muted">{{ $affiliate->notes }}</p>
                                    </div>
                                @endif

                                <div class="mt-4">
                                    <form action="{{ route('affiliate.remove') }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove your affiliate application? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-trash me-2"></i>Remove Application
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($affiliate->isApproved())
                <!-- Approved Affiliate -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Welcome, Affiliate!</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Congratulations! Your affiliate application has been approved.
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Your Affiliate Code:</strong><br>
                                        <code class="fs-5">{{ $affiliate->affiliate_code }}</code>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Commission Rate:</strong><br>
                                        <span class="fs-5">{{ $affiliate->commission_rate }}%</span>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('affiliate.dashboard') }}" class="btn btn-primary me-2">
                                        <i class="fas fa-chart-line me-2"></i>View Dashboard
                                    </a>
                                    <a href="{{ route('affiliate.earnings') }}" class="btn btn-outline-primary me-2">
                                        <i class="fas fa-dollar-sign me-2"></i>View Earnings
                                    </a>
                                    <a href="{{ route('affiliate.referrals') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-users me-2"></i>View Referrals
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Quick Stats</h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="h4 text-primary">{{ $affiliate->referral_count }}</div>
                                        <small class="text-muted">Referrals</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="h4 text-success">${{ number_format($affiliate->total_earnings, 2) }}</div>
                                        <small class="text-muted">Total Earnings</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- Rejected/Suspended -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Application Status: {{ ucfirst($affiliate->status) }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Your affiliate application has been {{ $affiliate->status }}.
                                    @if($affiliate->status === 'rejected')
                                        You can submit a new application if you believe this was an error.
                                    @endif
                                </div>

                                @if($affiliate->notes)
                                    <div class="mt-3">
                                        <strong>Admin Notes:</strong><br>
                                        <p class="text-muted">{{ $affiliate->notes }}</p>
                                    </div>
                                @endif

                                <div class="mt-4">
                                    @if($affiliate->status === 'rejected')
                                        <a href="{{ route('affiliate') }}" class="btn btn-primary me-2">
                                            <i class="fas fa-redo me-2"></i>Apply Again
                                        </a>
                                    @endif
                                    
                                    <form action="{{ route('affiliate.remove') }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove your affiliate application? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-trash me-2"></i>Remove Application
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Plan prices
    const planPrices = {
        starter: 97,
        pro: 197,
        diablo: 497
    };

    // Get form elements
    const referralsCount = document.getElementById('referrals_count');
    const commissionRate = document.getElementById('commission_rate');
    const starterSales = document.getElementById('starter_sales');
    const proSales = document.getElementById('pro_sales');
    const diabloSales = document.getElementById('diablo_sales');
    const hiddenCommissionRate = document.getElementById('hidden_commission_rate');

    // Get result elements
    const starterCommission = document.getElementById('starter_commission');
    const proCommission = document.getElementById('pro_commission');
    const diabloCommission = document.getElementById('diablo_commission');
    const totalCommission = document.getElementById('total_commission');

    function calculateEarnings() {
        const rate = 0.20; // Fixed 20% commission rate
        const starter = parseInt(starterSales.value) || 0;
        const pro = parseInt(proSales.value) || 0;
        const diablo = parseInt(diabloSales.value) || 0;

        // Calculate total referrals (sum of all sales)
        const totalReferrals = starter + pro + diablo;
        referralsCount.value = totalReferrals;

        // Calculate commissions
        const starterComm = (planPrices.starter * rate * starter).toFixed(2);
        const proComm = (planPrices.pro * rate * pro).toFixed(2);
        const diabloComm = (planPrices.diablo * rate * diablo).toFixed(2);
        const total = (parseFloat(starterComm) + parseFloat(proComm) + parseFloat(diabloComm)).toFixed(2);

        // Update display
        starterCommission.textContent = '$' + starterComm;
        proCommission.textContent = '$' + proComm;
        diabloCommission.textContent = '$' + diabloComm;
        totalCommission.textContent = '$' + total;

        // Update hidden field with fixed rate
        hiddenCommissionRate.value = 20;
    }

    // Add event listeners only to sales inputs
    [starterSales, proSales, diabloSales].forEach(element => {
        element.addEventListener('input', calculateEarnings);
    });

    // Initial calculation
    calculateEarnings();
});
</script>
@endsection
