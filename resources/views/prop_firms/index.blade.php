@extends('layouts.app')

@section('title', 'Prop Firms')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="prop-hero bg-gradient-primary text-white rounded-4 p-5 position-relative overflow-hidden">
                <div class="hero-particles position-absolute top-0 start-0 w-100 h-100"></div>
                <div class="hero-shapes position-absolute top-0 start-0 w-100 h-100">
                    <div class="shape shape-1"></div>
                    <div class="shape shape-2"></div>
                    <div class="shape shape-3"></div>
                </div>
                <div class="position-relative">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h1 class="display-4 fw-bold mb-3 animate-fade-in">
                                <i class="fas fa-building me-3"></i>
                                Prop Trading Firms
                            </h1>
                            <p class="lead mb-4 animate-fade-in-delay">Discover the best proprietary trading firms offering funded accounts, scaling programs, and professional trading opportunities.</p>
                            <div class="hero-stats d-flex flex-wrap gap-4 mb-4 animate-fade-in-delay-2">
                                <div class="stat-item">
                                    <div class="stat-number h3 mb-0">20+</div>
                                    <div class="stat-label">Prop Firms</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number h3 mb-0">$2.4M</div>
                                    <div class="stat-label">Max Funding</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number h3 mb-0">100%</div>
                                    <div class="stat-label">Verified</div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-3 animate-fade-in-delay-3">
                                <button class="btn btn-light btn-lg px-4 py-3 shadow-sm animate-bounce" onclick="scrollToFirms()">
                                    <i class="fas fa-arrow-down me-2"></i>Explore Firms
                                </button>
                                <button class="btn btn-outline-light btn-lg px-4 py-3" onclick="showHowItWorks()">
                                    <i class="fas fa-question-circle me-2"></i>How It Works
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-4 text-center">
                            <div class="prop-illustration animate-float">
                                <i class="fas fa-coins fa-5x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How Prop Firms Work Section -->
    <div class="row mb-5" id="howItWorks">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-light">
                    <h3 class="mb-0">
                        <i class="fas fa-lightbulb me-2 text-warning"></i>
                        How Prop Trading Firms Work
                    </h3>
                </div>
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-lg-8">
                            <h4 class="mb-3">What is a Prop Trading Firm?</h4>
                            <p class="lead text-muted mb-4">
                                A proprietary trading firm (prop firm) provides traders with capital to trade financial markets. 
                                Instead of using your own money, you trade with the firm's capital and share profits according to agreed terms.
                            </p>
                            
                            <h5 class="mb-3">The Process:</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="step-card mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="step-number me-3">1</div>
                                            <div>
                                                <h6 class="mb-1">Evaluation Phase</h6>
                                                <p class="text-muted small mb-0">Pass trading challenges to prove your skills</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="step-card mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="step-number me-3">2</div>
                                            <div>
                                                <h6 class="mb-1">Get Funded</h6>
                                                <p class="text-muted small mb-0">Receive funded account with real capital</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="step-card mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="step-number me-3">3</div>
                                            <div>
                                                <h6 class="mb-1">Trade & Earn</h6>
                                                <p class="text-muted small mb-0">Trade with firm's capital and earn profits</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="step-card mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="step-number me-3">4</div>
                                            <div>
                                                <h6 class="mb-1">Scale Up</h6>
                                                <p class="text-muted small mb-0">Increase account size as you prove consistency</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="benefits-card">
                                <h5 class="mb-3">Key Benefits:</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>No personal capital required</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Access to large trading capital</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Professional trading environment</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Risk management support</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Profit sharing opportunities</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Scaling programs</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Educational resources</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label fw-semibold">Search Firms</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="search" placeholder="Search by name...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="country" class="form-label fw-semibold">Country</label>
                            <select class="form-select" id="country">
                                <option value="">All Countries</option>
                                <option value="us">United States</option>
                                <option value="gb">United Kingdom</option>
                                <option value="cz">Czech Republic</option>
                                <option value="ae">UAE</option>
                                <option value="il">Israel</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="allocation" class="form-label fw-semibold">Max Allocation</label>
                            <select class="form-select" id="allocation">
                                <option value="">All Sizes</option>
                                <option value="100000">Up to $100K</option>
                                <option value="200000">Up to $200K</option>
                                <option value="300000">Up to $300K</option>
                                <option value="500000">Up to $500K</option>
                                <option value="1000000">Up to $1M</option>
                                <option value="2000000">Up to $2M+</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">&nbsp;</label>
                            <button class="btn btn-primary w-100" onclick="filterFirms()">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prop Firms Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-dark text-white">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h5 class="mb-0">FIRM</h5>
                        </div>
                        <div class="col-md-2 text-center">
                            <h5 class="mb-0">COUNTRY</h5>
                        </div>
                        <div class="col-md-2 text-center">
                            <h5 class="mb-0">YEARS</h5>
                        </div>
                        <div class="col-md-2 text-center">
                            <h5 class="mb-0">ASSETS</h5>
                        </div>
                        <div class="col-md-1 text-center">
                            <h5 class="mb-0">MAX ALLOCATION</h5>
                        </div>
                        <div class="col-md-1 text-center">
                            <h5 class="mb-0">ACTIONS</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0" id="firmsTable">
                    @foreach($propFirms as $index => $firm)
                    <div class="firm-row border-bottom p-3" data-country="{{ $firm['flag'] }}" data-allocation="{{ $firm['maxAllocation'] }}" data-rating="{{ $firm['rating'] }}" data-name="{{ strtolower($firm['name']) }}">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="firm-logo me-3">
                                        <div class="logo-circle bg-primary text-white d-flex align-items-center justify-content-center">
                                            {{ substr($firm['name'], 0, 2) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold">{{ $firm['name'] }}</h6>
                                        <small class="text-muted">{{ $firm['description'] }}</small>
                                        <div class="mt-1">
                                            <i class="fas fa-heart text-danger"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="flag-icon">
                                    <i class="fas fa-flag text-muted"></i>
                                </div>
                                <small class="d-block text-muted">{{ $firm['country'] }}</small>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="years-badge bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center">
                                    {{ $firm['years'] }}
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="assets-tags">
                                    @foreach(array_slice($firm['assets'], 0, 4) as $asset)
                                        <span class="badge bg-light text-dark me-1 mb-1">{{ $asset }}</span>
                                    @endforeach
                                    @if(count($firm['assets']) > 4)
                                        <span class="badge bg-secondary">+{{ count($firm['assets']) - 4 }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-1 text-center">
                                <div class="allocation-info">
                                    <strong class="text-primary">${{ number_format($firm['maxAllocation'] / 1000) }}K</strong>
                                    <div class="progress mt-1" style="height: 4px;">
                                        <div class="progress-bar bg-primary" style="width: {{ min(100, ($firm['maxAllocation'] / 2000000) * 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 text-center">
                                <button class="btn btn-sm btn-outline-primary" onclick="showFirmDetails('{{ $firm['name'] }}')">
                                    View
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-primary text-white">
                <div class="card-body p-5 text-center">
                    <h3 class="mb-3">Ready to Start Trading?</h3>
                    <p class="lead mb-4">Choose your preferred prop firm and begin your funded trading journey today.</p>
                    <button class="btn btn-light btn-lg px-4 py-3" onclick="scrollToFirms()">
                        <i class="fas fa-rocket me-2"></i>Get Started
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Firm Details Modal -->
<div class="modal fade" id="firmModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="firmModalTitle">Firm Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="firmModalBody">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
/* Hero Section */
.prop-hero {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    position: relative;
    overflow: hidden;
}

.hero-particles {
    background-image: 
        radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
}

.hero-shapes {
    pointer-events: none;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 100px;
    height: 100px;
    top: 20%;
    right: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 60px;
    height: 60px;
    top: 60%;
    right: 20%;
    animation-delay: 2s;
}

.shape-3 {
    width: 80px;
    height: 80px;
    top: 40%;
    right: 5%;
    animation-delay: 4s;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-fade-in-delay {
    animation: fadeIn 1s ease-out 0.2s both;
}

.animate-fade-in-delay-2 {
    animation: fadeIn 1s ease-out 0.4s both;
}

.animate-fade-in-delay-3 {
    animation: fadeIn 1s ease-out 0.6s both;
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

.animate-bounce {
    animation: bounce 2s infinite;
}

/* Hero Stats */
.hero-stats .stat-item {
    text-align: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: transform 0.3s ease;
}

.hero-stats .stat-item:hover {
    transform: translateY(-5px);
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #fff;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

/* Gradient Backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
}

.bg-gradient-light {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
}

.bg-gradient-dark {
    background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
}

/* Step Cards */
.step-card {
    background: #dbeafe;
    border-radius: 12px;
    padding: 1.5rem;
    border-left: 4px solid #1e40af;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.step-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.step-number {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #1e40af, #3b82f6);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1rem;
    box-shadow: 0 2px 8px rgba(30, 64, 175, 0.3);
}

.benefits-card {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    border-radius: 16px;
    padding: 2rem;
    border: 1px solid rgba(30, 64, 175, 0.1);
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.1);
}

/* Firm Rows */
.firm-row {
    transition: all 0.3s ease;
    position: relative;
}

.firm-row:hover {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(30, 64, 175, 0.1);
    border-radius: 8px;
    margin: 0 8px;
}

.firm-logo .logo-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    font-weight: bold;
    font-size: 1rem;
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
    transition: transform 0.3s ease;
}

.firm-row:hover .logo-circle {
    transform: scale(1.1);
}

.years-badge {
    width: 35px;
    height: 35px;
    font-size: 0.9rem;
    font-weight: bold;
    box-shadow: 0 2px 8px rgba(30, 64, 175, 0.3);
}

.assets-tags .badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.6rem;
    border-radius: 20px;
    transition: all 0.3s ease;
}

.assets-tags .badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.allocation-info .progress {
    border-radius: 10px;
    background-color: rgba(30, 64, 175, 0.1);
}

.allocation-info .progress-bar {
    border-radius: 10px;
    background: linear-gradient(90deg, #1e40af, #3b82f6);
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

/* Cards */
.card {
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
}

.card-header {
    border-radius: 16px 16px 0 0 !important;
    border: none;
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

.input-group-text {
    border-radius: 8px 0 0 8px;
    border: 2px solid #e9ecef;
    border-right: none;
    background: #f8f9fa;
}

/* Responsive */
@media (max-width: 768px) {
    .prop-hero {
        padding: 2rem !important;
    }
    
    .prop-hero h1 {
        font-size: 2.5rem !important;
    }
    
    .hero-stats {
        flex-direction: column;
        gap: 1rem !important;
    }
    
    .firm-row .row > div {
        margin-bottom: 0.5rem;
    }
    
    .firm-row:hover {
        margin: 0 4px;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .prop-hero {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
    }
    
    .benefits-card {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        color: white;
    }
    
    .step-card {
        background: #1e3a8a;
        color: white;
    }
    
    .firm-row:hover {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
    }
}
</style>

<script>
function scrollToFirms() {
    document.getElementById('firmsTable').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });
}

function showHowItWorks() {
    document.getElementById('howItWorks').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });
}

function filterFirms() {
    const search = document.getElementById('search').value.toLowerCase();
    const country = document.getElementById('country').value;
    const allocation = document.getElementById('allocation').value;
    
    const rows = document.querySelectorAll('.firm-row');
    
    rows.forEach(row => {
        const firmName = row.getAttribute('data-name');
        const firmCountry = row.getAttribute('data-country');
        const firmAllocation = parseInt(row.getAttribute('data-allocation'));
        
        let show = true;
        
        if (search && !firmName.includes(search)) {
            show = false;
        }
        
        if (country && firmCountry !== country) {
            show = false;
        }
        
        if (allocation && firmAllocation > parseInt(allocation)) {
            show = false;
        }
        
        row.style.display = show ? 'block' : 'none';
    });
}

function showFirmDetails(firmName) {
    // This would typically fetch detailed information about the firm
    document.getElementById('firmModalTitle').textContent = firmName + ' Details';
    document.getElementById('firmModalBody').innerHTML = `
        <div class="text-center">
            <h4>${firmName}</h4>
            <p class="text-muted">Detailed information about ${firmName} will be displayed here.</p>
            <p>This would include:</p>
            <ul class="list-unstyled">
                <li><i class="fas fa-check text-success me-2"></i>Trading rules and requirements</li>
                <li><i class="fas fa-check text-success me-2"></i>Profit sharing details</li>
                <li><i class="fas fa-check text-success me-2"></i>Account sizes available</li>
                <li><i class="fas fa-check text-success me-2"></i>Evaluation process</li>
                <li><i class="fas fa-check text-success me-2"></i>Scaling program details</li>
            </ul>
        </div>
    `;
    
    var modal = new bootstrap.Modal(document.getElementById('firmModal'));
    modal.show();
}

// Search as you type
document.getElementById('search').addEventListener('input', filterFirms);
document.getElementById('country').addEventListener('change', filterFirms);
document.getElementById('allocation').addEventListener('change', filterFirms);
</script>
@endsection