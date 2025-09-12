@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <h1 class="h3 mb-0 fw-bold text-dark">
                        <i class="fas fa-calculator me-2"></i>
                        Trading Earnings Calculator
                    </h1>
                    <p class="mb-0 mt-2 fw-bold text-dark">
                        Calculate your potential monthly earnings based on your trading setup, account size, and profit percentage.
                    </p>
                </div>
                
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <p class="text-muted mb-4">
                                        Select your funded accounts and calculate potential earnings.
                                    </p>
                                    
                                    <!-- Account Selection -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Add Funded Account</label>
                                            <div class="input-group">
                                                <select class="form-select" id="accountDropdown">
                                                    <option value="">Select Account Size</option>
                                                    <option value="10000">Funded 10K - $10,000</option>
                                                    <option value="25000">Funded 25K - $25,000</option>
                                                    <option value="50000">Funded 50K - $50,000</option>
                                                    <option value="100000">Funded 100K - $100,000</option>
                                                    <option value="200000">Funded 200K - $200,000</option>
                                                </select>
                                                <button class="btn btn-success" type="button" id="addBtn">
                                                    <i class="fas fa-plus"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Total Account Size</label>
                                            <input type="text" class="form-control" id="totalSize" value="$0" readonly style="background-color: #f8f9fa;">
                                        </div>
                                    </div>

                                    <!-- Selected Accounts Display -->
                                    <div class="mb-4">
                                        <label class="form-label">Selected Accounts</label>
                                        <div id="accountsList" class="border rounded p-3" style="min-height: 60px; background-color: #f8f9fa;">
                                            <div class="text-muted text-center">No accounts selected</div>
                                        </div>
                                    </div>

                                    <!-- Trading Parameters -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Number of Accounts</label>
                                                <input type="text" class="form-control" id="accountCount" value="0" readonly style="background-color: #f8f9fa;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Monthly Profit %</label>
                                                <input type="number" class="form-control" id="profitPercent" value="5" min="1" max="50" step="0.5">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Withdrawal Fee %</label>
                                                <input type="number" class="form-control" id="withdrawalFee" value="10" min="0" max="50" step="1">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Risk Level</label>
                                                <select class="form-select" id="riskLevel">
                                                    <option value="conservative">Conservative (2-4% monthly)</option>
                                                    <option value="moderate" selected>Moderate (4-6% monthly)</option>
                                                    <option value="aggressive">Aggressive (6-10% monthly)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Results Panel -->
                        <div class="col-md-4">
                            <!-- Tab Buttons -->
                            <div class="d-flex mb-3">
                                <button class="btn btn-primary flex-fill me-2 active" id="earningsTab" onclick="showEarnings()">
                                    <i class="fas fa-chart-line me-2"></i>Your Potential Earnings
                                </button>
                                <button class="btn btn-outline-primary flex-fill" id="planTab" onclick="showPlan()">
                                    <i class="fas fa-star me-2"></i>Recommended Plan
                                </button>
                            </div>

                            <!-- Earnings Content -->
                            <div id="earningsContent" class="card bg-light border-0 mb-4">
                                <div class="card-body text-center">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="p-3 bg-white rounded shadow-sm">
                                                <div class="h5 text-primary mb-1" id="monthlyProfit">$0</div>
                                                <div class="small text-muted">Monthly Profit</div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-6">
                                            <div class="p-3 bg-white rounded shadow-sm">
                                                <div class="h5 text-success mb-1" id="netEarnings">$0</div>
                                                <div class="small text-muted">After Withdrawal Fee</div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-6">
                                            <div class="p-3 bg-white rounded shadow-sm">
                                                <div class="h5 text-warning mb-1" id="yearlyEarnings">$0</div>
                                                <div class="small text-muted">Yearly Earnings</div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-6">
                                            <div class="p-3 bg-white rounded shadow-sm">
                                                <div class="h5 text-danger mb-1" id="totalCapital">$0</div>
                                                <div class="small text-muted">Total Capital</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Plan Content -->
                            <div id="planContent" class="card border-0 shadow-sm" style="display: none;">
                                <div class="card-body" id="planRecommendation">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                                        <p class="mb-0">Add accounts to see your recommended plan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Breakdown -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-list-alt me-2"></i>
                                        Calculation Breakdown
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Account Size:</strong></td>
                                                    <td id="breakdownAccountSize">$0</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Number of Accounts:</strong></td>
                                                    <td id="breakdownAccountCount">0</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Total Capital:</strong></td>
                                                    <td id="breakdownTotalCapital">$0</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Profit %:</strong></td>
                                                    <td id="breakdownProfitPercent">0%</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Monthly Profit:</strong></td>
                                                    <td id="breakdownMonthlyProfit">$0</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Withdrawal Fee:</strong></td>
                                                    <td id="breakdownWithdrawalFee">$0</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Net Earnings:</strong></td>
                                                    <td id="breakdownNetEarnings">$0</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
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
// Simple global variables
let selectedAccounts = [];

// Simple functions
function addAccount() {
    const dropdown = document.getElementById('accountDropdown');
    const value = parseInt(dropdown.value);
    
    if (value && value > 0) {
        selectedAccounts.push(value);
        updateDisplay();
        calculateEarnings();
    } else {
        alert('Please select an account size first');
    }
}

function removeAccount(index) {
    selectedAccounts.splice(index, 1);
    updateDisplay();
    calculateEarnings();
}

function clearAllAccounts() {
    selectedAccounts = [];
    updateDisplay();
    calculateEarnings();
}

function updateDisplay() {
    const accountsList = document.getElementById('accountsList');
    const totalSize = document.getElementById('totalSize');
    const accountCount = document.getElementById('accountCount');
    
    if (selectedAccounts.length === 0) {
        accountsList.innerHTML = '<div class="text-muted text-center">No accounts selected</div>';
        totalSize.value = '$0';
        accountCount.value = '0';
    } else {
        const total = selectedAccounts.reduce((sum, amount) => sum + amount, 0);
        const badges = selectedAccounts.map((amount, index) => 
            `<span class="badge bg-primary me-2 mb-1">
                $${(amount/1000)}K 
                <button type="button" class="btn-close btn-close-white ms-1" onclick="removeAccount(${index})" style="font-size: 0.7em;"></button>
            </span>`
        ).join('');
        
        accountsList.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>Selected Accounts:</strong> ${badges}
                    <div class="text-muted small mt-1">Total: $${total.toLocaleString()} | Count: ${selectedAccounts.length}</div>
                </div>
                <button class="btn btn-sm btn-outline-danger" onclick="clearAllAccounts()">
                    <i class="fas fa-trash"></i> Clear All
                </button>
</div>
        `;
        
        totalSize.value = '$' + total.toLocaleString();
        accountCount.value = selectedAccounts.length;
    }
}

function calculateEarnings() {
    const totalCapital = selectedAccounts.reduce((sum, amount) => sum + amount, 0);
    const accountCount = selectedAccounts.length;
    const profitPercent = parseFloat(document.getElementById('profitPercent').value) || 0;
    const withdrawalFeePercent = parseFloat(document.getElementById('withdrawalFee').value) || 0;

    const monthlyProfit = totalCapital * (profitPercent / 100);
    const withdrawalFee = monthlyProfit * (withdrawalFeePercent / 100);
    const netEarnings = monthlyProfit - withdrawalFee;
    const yearlyEarnings = netEarnings * 12;

    // Update main results
    document.getElementById('monthlyProfit').textContent = '$' + monthlyProfit.toLocaleString();
    document.getElementById('netEarnings').textContent = '$' + netEarnings.toLocaleString();
    document.getElementById('yearlyEarnings').textContent = '$' + yearlyEarnings.toLocaleString();
    document.getElementById('totalCapital').textContent = '$' + totalCapital.toLocaleString();

    // Update breakdown
    const avgAccountSize = accountCount > 0 ? totalCapital / accountCount : 0;
    document.getElementById('breakdownAccountSize').textContent = '$' + avgAccountSize.toLocaleString();
    document.getElementById('breakdownAccountCount').textContent = accountCount;
    document.getElementById('breakdownTotalCapital').textContent = '$' + totalCapital.toLocaleString();
    document.getElementById('breakdownProfitPercent').textContent = profitPercent + '%';
    document.getElementById('breakdownMonthlyProfit').textContent = '$' + monthlyProfit.toLocaleString();
    document.getElementById('breakdownWithdrawalFee').textContent = '$' + withdrawalFee.toLocaleString();
    document.getElementById('breakdownNetEarnings').textContent = '$' + netEarnings.toLocaleString();

    // Update plan recommendation
    updatePlanRecommendation(netEarnings, accountCount, totalCapital);
}

function updatePlanRecommendation(monthlyEarnings, accountCount, totalCapital) {
    const planElement = document.getElementById('planRecommendation');
    
    if (accountCount === 0) {
        planElement.innerHTML = `
            <div class="text-center text-muted">
                <i class="fas fa-info-circle fa-2x mb-3"></i>
                <p class="mb-0">Add accounts to see your recommended plan</p>
            </div>
        `;
        return;
    }

    let planName, planPrice, planFeatures, planColor, planIcon, planBenefits, profitShare;

    if (monthlyEarnings < 3000) {
        planName = "Eros Starter";
        planPrice = "$97/month";
        planIcon = "fas fa-seedling";
        planColor = "primary";
        planFeatures = "Designed for traders ready to pass their first challenge, earn steady payouts, and build long-term account growth with less risk.";
        profitShare = "70/30 Profit Share (you keep 70%)";
        planBenefits = [
            "1 Prop Account Management",
            "1 Prop Challenge Management",
            "Customer Support",
            "$1M PROP FUNDING ROADMAP",
            "Eros Prop EA"
        ];
    } else if (monthlyEarnings < 8000) {
        planName = "Eros Pro";
        planPrice = "$197/month";
        planIcon = "fas fa-chart-line";
        planColor = "success";
        planFeatures = "Go pro, scale bigger, and earn more, manage multiple accounts, follow the $2M roadmap, and keep the lion's share of your profits.";
        profitShare = "80/20 Profit Share (you keep 80%)";
        planBenefits = [
            "Up to 5 Prop Account Management",
            "Up to 5 Prop Challenge Management",
            "Premium Customer Support",
            "$2M PROP FUNDING ROADMAP",
            "Real Capital Account Management",
            "Eros Prop EA + Eros Gold EA"
        ];
    } else {
        planName = "Eros Diablo";
        planPrice = "$497/month";
        planIcon = "fas fa-crown";
        planColor = "danger";
        planFeatures = "Maximum funding, maximum accounts, maximum freedom.";
        profitShare = "90/10 Profit Share (you keep 90%)";
        planBenefits = [
            "Up to 20 Prop Accounts Management",
            "Up to 20 Prop Challenge Management",
            "Premium Customer Support",
            "+$6M PROP FUNDING ROADMAP",
            "Real Capital Account Management",
            "Eros Prop EA + Eros Gold EA + EA ELITE EA v2.3"
        ];
    }

    const benefitsList = planBenefits.map(benefit => `<li class="mb-2"><i class="fas fa-check text-${planColor} me-2"></i>${benefit}</li>`).join('');

    planElement.innerHTML = `
        <div class="text-center mb-3">
            <i class="${planIcon} fa-3x text-${planColor} mb-3"></i>
            <h4 class="text-${planColor} mb-2">${planName}</h4>
            <h3 class="text-${planColor} mb-2">${planPrice}</h3>
            <p class="text-muted small">${planFeatures}</p>
        </div>
        
        <div class="mb-3">
            <div class="alert alert-${planColor} border-0 text-center mb-3">
                <strong class="text-${planColor}">${profitShare}</strong>
            </div>
            
            <h6 class="text-dark mb-2">What's Included:</h6>
            <ul class="list-unstyled small">
                ${benefitsList}
            </ul>
        </div>
        
        <div class="text-center">
            <a href="{{ route('plans') }}" class="btn btn-${planColor} btn-lg w-100">
                <i class="fas fa-rocket me-2"></i>Get Started with ${planName}
            </a>
            <small class="text-muted d-block mt-2">
                Based on your potential monthly earnings of $${monthlyEarnings.toLocaleString()}
            </small>
        </div>
    `;
}

function showEarnings() {
    document.getElementById('earningsContent').style.display = 'block';
    document.getElementById('planContent').style.display = 'none';
    document.getElementById('earningsTab').className = 'btn btn-primary flex-fill me-2 active';
    document.getElementById('planTab').className = 'btn btn-outline-primary flex-fill';
}

function showPlan() {
    document.getElementById('earningsContent').style.display = 'none';
    document.getElementById('planContent').style.display = 'block';
    document.getElementById('earningsTab').className = 'btn btn-outline-primary flex-fill me-2';
    document.getElementById('planTab').className = 'btn btn-primary flex-fill';
}

function updateRiskLevel() {
    const risk = document.getElementById('riskLevel').value;
    const profitInput = document.getElementById('profitPercent');
    const feeInput = document.getElementById('withdrawalFee');
    
    switch(risk) {
        case 'conservative':
            profitInput.value = 3;
            feeInput.value = 5;
            break;
        case 'moderate':
            profitInput.value = 5;
            feeInput.value = 10;
            break;
        case 'aggressive':
            profitInput.value = 8;
            feeInput.value = 15;
            break;
    }
    calculateEarnings();
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addBtn').addEventListener('click', addAccount);
    document.getElementById('riskLevel').addEventListener('change', updateRiskLevel);
    document.getElementById('profitPercent').addEventListener('input', calculateEarnings);
    document.getElementById('withdrawalFee').addEventListener('input', calculateEarnings);
    
    // Initial calculation
    updateDisplay();
    calculateEarnings();
});
</script>
@endsection