@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-dark fw-bold">
                        <i class="fas fa-chart-line me-2 text-primary"></i>
                        Sales Analytics Dashboard
                    </h1>
                    <p class="text-muted mb-0">Track your sales, revenue, and subscription metrics in real-time</p>
                </div>
                <div>
                    <button class="btn btn-outline-primary" onclick="refreshAnalytics()">
                        <i class="fas fa-sync-alt me-1"></i>Refresh Data
                    </button>
                    <button class="btn btn-primary" onclick="exportReport()">
                        <i class="fas fa-download me-1"></i>Export Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(isset($error))
    <div class="alert alert-warning" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ $error }}
    </div>
    @endif

    <!-- Revenue Overview Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">
                                @if($stripeAnalytics)
                                    ${{ number_format($stripeAnalytics['monthly_revenue'], 2) }}
                                @else
                                    ${{ number_format($revenueAnalytics['total_monthly'], 2) }}
                                @endif
                            </div>
                            <div class="small">Monthly Revenue</div>
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
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">
                                @if($stripeAnalytics)
                                    {{ $stripeAnalytics['total_subscriptions'] }}
                                @else
                                    {{ $localAnalytics['active_subscriptions'] }}
                                @endif
                            </div>
                            <div class="small">Active Subscriptions</div>
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
                            <i class="fas fa-user-plus fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">{{ $localAnalytics['total_users'] }}</div>
                            <div class="small">Total Users</div>
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
                            <i class="fas fa-percentage fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0">
                                @if($localAnalytics['total_users'] > 0)
                                    {{ number_format(($localAnalytics['users_with_plans'] / $localAnalytics['total_users']) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </div>
                            <div class="small">Conversion Rate</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Plan Performance -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-crown me-2"></i>
                        Plan Performance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach(['starter', 'pro', 'diablo'] as $plan)
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-uppercase text-muted mb-2">{{ ucfirst($plan) }} Plan</h6>
                                    <div class="h3 text-primary mb-1">
                                        ${{ number_format($revenueAnalytics[$plan]['monthly_revenue'], 0) }}
                                    </div>
                                    <div class="text-muted small mb-2">Monthly Revenue</div>
                                    <div class="d-flex justify-content-between">
                                        <span class="badge bg-primary">{{ $revenueAnalytics[$plan]['active_users'] }} Active</span>
                                        <span class="text-muted">${{ $revenueAnalytics[$plan]['price'] }}/mo</span>
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

    <!-- Subscription Status Overview -->
    @if($stripeAnalytics)
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Subscription Status
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="subscriptionStatusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Plan Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="planDistributionChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- User Growth Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        User Growth (Last 12 Months)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="userGrowthChart" width="800" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Affiliate Performance -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-handshake me-2"></i>
                        Affiliate Performance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="h4 text-primary">{{ $affiliateAnalytics['total_affiliates'] }}</div>
                            <div class="text-muted">Total Affiliates</div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="h4 text-success">{{ $affiliateAnalytics['approved_affiliates'] }}</div>
                            <div class="text-muted">Approved</div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="h4 text-warning">{{ $affiliateAnalytics['pending_affiliates'] }}</div>
                            <div class="text-muted">Pending</div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="h4 text-info">${{ number_format($affiliateAnalytics['total_earnings'], 2) }}</div>
                            <div class="text-muted">Total Earnings</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sales Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Recent Sales
                        </h5>
                        <button class="btn btn-outline-primary btn-sm" onclick="loadSalesReport()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="salesTable">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading sales data...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Chart configurations
let subscriptionStatusChart, planDistributionChart, userGrowthChart;

document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    loadSalesReport();
});

function initializeCharts() {
    // Subscription Status Chart
    @if($stripeAnalytics)
    const subscriptionCtx = document.getElementById('subscriptionStatusChart').getContext('2d');
    subscriptionStatusChart = new Chart(subscriptionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Canceled', 'Incomplete', 'Past Due', 'Unpaid'],
            datasets: [{
                data: [
                    {{ $stripeAnalytics['subscription_counts']['active'] }},
                    {{ $stripeAnalytics['subscription_counts']['canceled'] }},
                    {{ $stripeAnalytics['subscription_counts']['incomplete'] }},
                    {{ $stripeAnalytics['subscription_counts']['past_due'] }},
                    {{ $stripeAnalytics['subscription_counts']['unpaid'] }}
                ],
                backgroundColor: [
                    '#28a745',
                    '#dc3545',
                    '#ffc107',
                    '#fd7e14',
                    '#6c757d'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Plan Distribution Chart
    const planCtx = document.getElementById('planDistributionChart').getContext('2d');
    planDistributionChart = new Chart(planCtx, {
        type: 'bar',
        data: {
            labels: ['Starter', 'Pro', 'Diablo'],
            datasets: [{
                label: 'Active Subscriptions',
                data: [
                    {{ $subscriptionMetrics['starter']['active'] }},
                    {{ $subscriptionMetrics['pro']['active'] }},
                    {{ $subscriptionMetrics['diablo']['active'] }}
                ],
                backgroundColor: ['#0d6efd', '#198754', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    @endif

    // User Growth Chart
    const growthCtx = document.getElementById('userGrowthChart').getContext('2d');
    userGrowthChart = new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($userGrowth as $month)
                '{{ $month['month'] }}',
                @endforeach
            ],
            datasets: [{
                label: 'New Users',
                data: [
                    @foreach($userGrowth as $month)
                    {{ $month['users'] }},
                    @endforeach
                ],
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4
            }, {
                label: 'New Subscriptions',
                data: [
                    @foreach($userGrowth as $month)
                    {{ $month['subscriptions'] }},
                    @endforeach
                ],
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function loadSalesReport() {
    fetch('{{ route("admin.analytics.sales") }}?period=30')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displaySalesTable(data.sales, data.total_revenue);
            } else {
                document.getElementById('salesTable').innerHTML = 
                    '<div class="alert alert-warning">Unable to load sales data. Please check your Stripe configuration.</div>';
            }
        })
        .catch(error => {
            console.error('Error loading sales report:', error);
            document.getElementById('salesTable').innerHTML = 
                '<div class="alert alert-danger">Error loading sales data.</div>';
        });
}

function displaySalesTable(sales, totalRevenue) {
    let tableHtml = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">Total Revenue: <strong>$${totalRevenue.toFixed(2)}</strong></h6>
            <small class="text-muted">Last 30 days</small>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Plan</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
    `;

    if (sales.length === 0) {
        tableHtml += `
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    <i class="fas fa-shopping-cart fa-2x mb-2"></i><br>
                    No sales found for the selected period
                </td>
            </tr>
        `;
    } else {
        sales.forEach(sale => {
            const planBadge = {
                'starter': 'badge bg-primary',
                'pro': 'badge bg-success',
                'diablo': 'badge bg-danger',
                'unknown': 'badge bg-secondary'
            }[sale.plan] || 'badge bg-secondary';

            tableHtml += `
                <tr>
                    <td>${new Date(sale.date).toLocaleDateString()}</td>
                    <td>${sale.customer_email || 'N/A'}</td>
                    <td><span class="${planBadge}">${sale.plan.toUpperCase()}</span></td>
                    <td><strong>$${sale.amount.toFixed(2)}</strong></td>
                    <td><span class="badge bg-success">${sale.status.toUpperCase()}</span></td>
                </tr>
            `;
        });
    }

    tableHtml += `
                </tbody>
            </table>
        </div>
    `;

    document.getElementById('salesTable').innerHTML = tableHtml;
}

function refreshAnalytics() {
    location.reload();
}

function exportReport() {
    // This would typically generate a CSV or PDF report
    alert('Export functionality would be implemented here to generate a detailed sales report.');
}
</script>
@endsection
