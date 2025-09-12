@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white text-center py-4 position-relative overflow-hidden">
                    <div class="hero-particles position-absolute top-0 start-0 w-100 h-100"></div>
                    <div class="position-relative">
                        <h1 class="h3 mb-0 fw-bold animate-fade-in">
                            <i class="fas fa-calculator me-2"></i>
                            Trading Earnings Calculator
                        </h1>
                        <p class="mb-0 mt-2 fw-bold animate-fade-in-delay">
                            Calculate your potential monthly earnings based on your trading setup, account size, and profit percentage.
                        </p>
                    </div>
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
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Prop Firm Profit Split</label>
                                                <select class="form-select" id="profitSplit">
                                                    <option value="80">80% (You keep 80%)</option>
                                                    <option value="90" selected>90% (You keep 90%)</option>
                                                    <option value="100">100% (You keep 100%)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Eros Plan</label>
                                                <select class="form-select" id="erosPlan">
                                                    <option value="starter">Eros Starter (70% keep)</option>
                                                    <option value="pro" selected>Eros Pro (80% keep)</option>
                                                    <option value="diablo">Eros Diablo (90% keep)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Risk Management</label>
                                                <select class="form-select" id="riskManagement">
                                                    <option value="0.5">0.5% per trade</option>
                                                    <option value="1" selected>1% per trade</option>
                                                    <option value="2">2% per trade</option>
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
                                                <div class="small text-muted">After Profit Split</div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-6">
                                            <div class="p-3 bg-white rounded shadow-sm">
                                                <div class="h5 text-info mb-1" id="finalEarnings">$0</div>
                                                <div class="small text-muted">Final Earnings</div>
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
                                                    <td><strong>Prop Firm Fee:</strong></td>
                                                    <td id="breakdownWithdrawalFee">$0</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Net Earnings:</strong></td>
                                                    <td id="breakdownNetEarnings">$0</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Account Management Fee:</strong></td>
                                                    <td id="breakdownAccountFee">$0</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Final Earnings:</strong></td>
                                                    <td id="breakdownFinalEarnings">$0</td>
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
    const profitSplitPercent = parseFloat(document.getElementById('profitSplit').value) || 90;

    const monthlyProfit = totalCapital * (profitPercent / 100);
    const propFirmFee = monthlyProfit * ((100 - profitSplitPercent) / 100);
    const netEarnings = monthlyProfit - propFirmFee;
    
    // Calculate account management fee based on selected plan
    const accountManagementPercent = getAccountManagementPercent();
    const accountManagementFee = netEarnings * ((100 - accountManagementPercent) / 100);
    const finalEarnings = netEarnings - accountManagementFee;
    const yearlyEarnings = finalEarnings * 12;

    // Update main results
    document.getElementById('monthlyProfit').textContent = '$' + monthlyProfit.toLocaleString();
    document.getElementById('netEarnings').textContent = '$' + netEarnings.toLocaleString();
    document.getElementById('finalEarnings').textContent = '$' + finalEarnings.toLocaleString();
    document.getElementById('yearlyEarnings').textContent = '$' + yearlyEarnings.toLocaleString();
    document.getElementById('totalCapital').textContent = '$' + totalCapital.toLocaleString();

    // Update breakdown
    const avgAccountSize = accountCount > 0 ? totalCapital / accountCount : 0;
    document.getElementById('breakdownAccountSize').textContent = '$' + avgAccountSize.toLocaleString();
    document.getElementById('breakdownAccountCount').textContent = accountCount;
    document.getElementById('breakdownTotalCapital').textContent = '$' + totalCapital.toLocaleString();
    document.getElementById('breakdownProfitPercent').textContent = profitPercent + '%';
    document.getElementById('breakdownMonthlyProfit').textContent = '$' + monthlyProfit.toLocaleString();
    document.getElementById('breakdownWithdrawalFee').textContent = '$' + propFirmFee.toLocaleString();
    document.getElementById('breakdownNetEarnings').textContent = '$' + netEarnings.toLocaleString();
    document.getElementById('breakdownAccountFee').textContent = '$' + accountManagementFee.toLocaleString();
    document.getElementById('breakdownFinalEarnings').textContent = '$' + finalEarnings.toLocaleString();

    // Update plan recommendation
    updatePlanRecommendation(finalEarnings, accountCount, totalCapital);
}

function getAccountManagementPercent() {
    // Get the selected plan from the dropdown
    const selectedPlan = document.getElementById('erosPlan').value;
    
    switch(selectedPlan) {
        case 'starter':
            return 70; // Eros Starter - user keeps 70%
        case 'pro':
            return 80; // Eros Pro - user keeps 80%
        case 'diablo':
            return 90; // Eros Diablo - user keeps 90%
        default:
            return 80; // Default to Pro plan
    }
}

function updatePlanRecommendation(monthlyEarnings, accountCount, totalCapital) {
    const planElement = document.getElementById('planRecommendation');
    
    if (accountCount === 0) {
        planElement.innerHTML = `
            <div class="text-center text-muted">
                <i class="fas fa-info-circle fa-2x mb-3"></i>
                <p class="mb-0">Add accounts to see your selected plan details</p>
            </div>
        `;
        return;
    }

    // Get the selected plan from the dropdown
    const selectedPlan = document.getElementById('erosPlan').value;
    let planName, planPrice, planFeatures, planColor, planIcon, planBenefits, profitShare, accountManagementPercent;

    switch(selectedPlan) {
        case 'starter':
            planName = "Eros Starter";
            planPrice = "$97/month";
            planIcon = "fas fa-seedling";
            planColor = "primary";
            planFeatures = "Designed for traders ready to pass their first challenge, earn steady payouts, and build long-term account growth with less risk.";
            profitShare = "70/30 Profit Share (you keep 70%)";
            accountManagementPercent = 70;
            planBenefits = [
                "1 Prop Account Management",
                "1 Prop Challenge Management",
                "Customer Support",
                "$1M PROP FUNDING ROADMAP",
                "Eros Prop EA"
            ];
            break;
        case 'pro':
            planName = "Eros Pro";
            planPrice = "$197/month";
            planIcon = "fas fa-chart-line";
            planColor = "success";
            planFeatures = "Go pro, scale bigger, and earn more, manage multiple accounts, follow the $2M roadmap, and keep the lion's share of your profits.";
            profitShare = "80/20 Profit Share (you keep 80%)";
            accountManagementPercent = 80;
            planBenefits = [
                "Up to 5 Prop Account Management",
                "Up to 5 Prop Challenge Management",
                "Premium Customer Support",
                "$2M PROP FUNDING ROADMAP",
                "Real Capital Account Management",
                "Eros Prop EA + Eros Gold EA"
            ];
            break;
        case 'diablo':
            planName = "Eros Diablo";
            planPrice = "$497/month";
            planIcon = "fas fa-crown";
            planColor = "danger";
            planFeatures = "Maximum funding, maximum accounts, maximum freedom.";
            profitShare = "90/10 Profit Share (you keep 90%)";
            accountManagementPercent = 90;
            planBenefits = [
                "Up to 20 Prop Accounts Management",
                "Up to 20 Prop Challenge Management",
                "Premium Customer Support",
                "+$6M PROP FUNDING ROADMAP",
                "Real Capital Account Management",
                "Eros Prop EA + Eros Gold EA + EA ELITE EA v2.3"
            ];
            break;
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
                Based on your potential final earnings of $${monthlyEarnings.toLocaleString()}
            </small>
            <small class="text-info d-block mt-1">
                <i class="fas fa-info-circle me-1"></i>
                Account Management: You keep ${accountManagementPercent}% of net earnings
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

function updateRiskManagement() {
    const riskManagement = document.getElementById('riskManagement').value;
    const profitInput = document.getElementById('profitPercent');
    const profitSplitInput = document.getElementById('profitSplit');
    
    switch(riskManagement) {
        case '0.5':
            profitInput.value = 3;
            profitSplitInput.value = 100;
            break;
        case '1':
            profitInput.value = 5;
            profitSplitInput.value = 90;
            break;
        case '2':
            profitInput.value = 8;
            profitSplitInput.value = 80;
            break;
    }
    calculateEarnings();
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addBtn').addEventListener('click', addAccount);
    document.getElementById('riskManagement').addEventListener('change', updateRiskManagement);
    document.getElementById('profitPercent').addEventListener('input', calculateEarnings);
    document.getElementById('profitSplit').addEventListener('change', calculateEarnings);
    document.getElementById('erosPlan').addEventListener('change', calculateEarnings);
    
    // Initial calculation
    updateDisplay();
    calculateEarnings();
});
</script>

<style>
/* Royal Blue Template Styles */
.bg-gradient-primary {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
}

.hero-particles {
    background-image: 
        radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-fade-in-delay {
    animation: fadeIn 1s ease-out 0.2s both;
}

/* Cards */
.card {
    border-radius: 16px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(30, 64, 175, 0.1) !important;
}

/* Buttons */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #1e40af, #3b82f6);
    border: none;
}

.btn-outline-primary {
    border: 2px solid #1e40af;
    color: #1e40af;
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #1e40af, #3b82f6);
    border-color: #1e40af;
}

.btn-success {
    background: linear-gradient(135deg, #059669, #10b981);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #047857, #059669);
}

/* Form Controls */
.form-control, .form-select {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #1e40af;
    box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.25);
}

/* Account Cards */
.account-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 1px solid rgba(30, 64, 175, 0.1);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.account-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.15);
    border-color: rgba(30, 64, 175, 0.2);
}

/* Results Cards */
.results-card {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    border: 1px solid rgba(30, 64, 175, 0.2);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.results-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(30, 64, 175, 0.2);
}

/* Plan Cards */
.plan-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 1px solid rgba(30, 64, 175, 0.1);
    border-radius: 16px;
    transition: all 0.3s ease;
}

.plan-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(30, 64, 175, 0.15);
    border-color: rgba(30, 64, 175, 0.3);
}

/* Progress Bars */
.progress {
    border-radius: 10px;
    background-color: rgba(30, 64, 175, 0.1);
}

.progress-bar {
    border-radius: 10px;
    background: linear-gradient(90deg, #1e40af, #3b82f6);
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .account-card {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        color: white;
    }
    
    .results-card {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        color: white;
    }
    
    .plan-card {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        color: white;
    }
}
</style>
@endsection