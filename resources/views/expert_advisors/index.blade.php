@extends('layouts.app')

@section('title', 'Expert Advisors')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="ea-hero bg-gradient-primary text-white rounded-4 p-5 position-relative overflow-hidden">
                <div class="hero-particles position-absolute top-0 start-0 w-100 h-100"></div>
                <div class="hero-shapes position-absolute top-0 start-0 w-100 h-100">
                    <div class="shape shape-1"></div>
                    <div class="shape shape-2"></div>
                    <div class="shape shape-3"></div>
                </div>
                <div class="position-relative">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h1 class="display-5 fw-bold mb-3 animate-fade-in">
                                <i class="fas fa-robot me-3"></i>
                                Eros Expert Advisors
                            </h1>
                            <p class="lead mb-4 animate-fade-in-delay">Professional trading plans with advanced EAs designed for prop firm success. Scale from $1M to $6M funding with our proven algorithms.</p>
                            <div class="d-flex flex-wrap gap-3 animate-fade-in-delay-2">
                                <button class="btn btn-light btn-lg px-4 py-3 shadow-sm animate-bounce" onclick="scrollToEAs()">
                                    <i class="fas fa-arrow-down me-2"></i>Explore EAs
                                </button>
                                <button class="btn btn-outline-light btn-lg px-4 py-3" onclick="showDemoModal()">
                                    <i class="fas fa-play me-2"></i>Watch Demo
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-4 text-center">
                            <div class="ea-illustration animate-float">
                                <i class="fas fa-chart-line fa-5x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="row mb-5">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-robot fa-2x text-primary"></i>
                    </div>
                    <h3 class="fw-bold text-primary mb-2">3</h3>
                    <p class="text-muted mb-0 fw-semibold">Trading Plans</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-chart-line fa-2x text-success"></i>
                    </div>
                    <h3 class="fw-bold text-success mb-2">90%</h3>
                    <p class="text-muted mb-0 fw-semibold">Max Profit Share</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                    <h3 class="fw-bold text-warning mb-2">$6M</h3>
                    <p class="text-muted mb-0 fw-semibold">Max Funding</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                    <h3 class="fw-bold text-info mb-2">20</h3>
                    <p class="text-muted mb-0 fw-semibold">Max Accounts</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label fw-semibold">Search EAs</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="search" placeholder="Search by name or strategy...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="strategy" class="form-label fw-semibold">Plan Type</label>
                            <select class="form-select" id="strategy">
                                <option value="">All Plans</option>
                                <option value="starter">Eros Starter</option>
                                <option value="pro">Eros Pro</option>
                                <option value="diablo">Eros Diablo</option>
                                <option value="prop">Eros Prop EA</option>
                                <option value="gold">Eros Gold EA</option>
                                <option value="elite">EA ELITE EA v2.3</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="risk" class="form-label fw-semibold">Plan Level</label>
                            <select class="form-select" id="risk">
                                <option value="">All Levels</option>
                                <option value="low">Entry Level</option>
                                <option value="medium">Professional</option>
                                <option value="high">Premium/Elite</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">&nbsp;</label>
                            <button class="btn btn-primary w-100" onclick="filterEAs()">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Eros Trading Plans -->
    <div class="row" id="eaGrid">
        <!-- Eros Starter Plan -->
        <div class="col-lg-4 col-md-6 mb-4" data-strategy="starter" data-risk="low">
            <div class="ea-card card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-success text-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-rocket me-2"></i>Eros Starter
                        </h5>
                        <span class="badge bg-success text-white">Entry Level</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="ea-stats mb-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-value text-success fw-bold">70%</div>
                                <div class="stat-label small text-muted">Profit Share</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-value text-primary fw-bold">$1M</div>
                                <div class="stat-label small text-muted">Funding Roadmap</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-value text-info fw-bold">1</div>
                                <div class="stat-label small text-muted">Prop Account</div>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Designed for traders ready to pass their first challenge, earn steady payouts, and build long-term account growth with less risk.</p>
                    <div class="features mb-3">
                        <h6 class="fw-semibold mb-2">What's Included:</h6>
                        <ul class="list-unstyled small">
                            <li><i class="fas fa-check text-success me-2"></i>1 Prop Account Management</li>
                            <li><i class="fas fa-check text-success me-2"></i>1 Prop Challenge Management</li>
                            <li><i class="fas fa-check text-success me-2"></i>Customer Support</li>
                            <li><i class="fas fa-check text-success me-2"></i>$1M PROP FUNDING ROADMAP</li>
                            <li><i class="fas fa-check text-success me-2"></i>Eros Prop EA</li>
                        </ul>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="price">
                            <span class="h4 text-primary fw-bold">$97</span>
                            <small class="text-muted d-block">Per Month</small>
                        </div>
                        <button class="btn btn-primary">
                            <i class="fas fa-play me-2"></i>Get Started
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Eros Pro Plan -->
        <div class="col-lg-4 col-md-6 mb-4" data-strategy="pro" data-risk="medium">
            <div class="ea-card card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-primary text-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-crown me-2"></i>Eros Pro
                        </h5>
                        <span class="badge bg-warning text-dark">Most Popular</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="ea-stats mb-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-value text-success fw-bold">80%</div>
                                <div class="stat-label small text-muted">Profit Share</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-value text-primary fw-bold">$2M</div>
                                <div class="stat-label small text-muted">Funding Roadmap</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-value text-info fw-bold">5</div>
                                <div class="stat-label small text-muted">Prop Accounts</div>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Go pro, scale bigger, and earn more, manage multiple accounts, follow the $2M roadmap, and keep the lion's share of your profits.</p>
                    <div class="features mb-3">
                        <h6 class="fw-semibold mb-2">What's Included:</h6>
                        <ul class="list-unstyled small">
                            <li><i class="fas fa-check text-success me-2"></i>Up to 5 Prop Account Management</li>
                            <li><i class="fas fa-check text-success me-2"></i>Up to 5 Prop Challenge Management</li>
                            <li><i class="fas fa-check text-success me-2"></i>Premium Customer Support</li>
                            <li><i class="fas fa-check text-success me-2"></i>$2M PROP FUNDING ROADMAP</li>
                            <li><i class="fas fa-check text-success me-2"></i>Real Capital Account Management</li>
                            <li><i class="fas fa-check text-success me-2"></i>Eros Prop EA + Eros Gold EA</li>
                        </ul>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="price">
                            <span class="h4 text-primary fw-bold">$197</span>
                            <small class="text-muted d-block">Per Month</small>
                        </div>
                        <button class="btn btn-primary">
                            <i class="fas fa-play me-2"></i>Get Started
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Eros Diablo Plan -->
        <div class="col-lg-4 col-md-6 mb-4" data-strategy="diablo" data-risk="high">
            <div class="ea-card card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-danger text-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-fire me-2"></i>Eros Diablo
                        </h5>
                        <span class="badge bg-danger text-white">Premium</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="ea-stats mb-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-value text-success fw-bold">90%</div>
                                <div class="stat-label small text-muted">Profit Share</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-value text-primary fw-bold">$6M</div>
                                <div class="stat-label small text-muted">Funding Roadmap</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-value text-info fw-bold">20</div>
                                <div class="stat-label small text-muted">Prop Accounts</div>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Maximum funding, maximum accounts, maximum freedom. The ultimate trading experience with all EAs included.</p>
                    <div class="features mb-3">
                        <h6 class="fw-semibold mb-2">What's Included:</h6>
                        <ul class="list-unstyled small">
                            <li><i class="fas fa-check text-success me-2"></i>Up to 20 Prop Accounts Management</li>
                            <li><i class="fas fa-check text-success me-2"></i>Up to 20 Prop Challenge Management</li>
                            <li><i class="fas fa-check text-success me-2"></i>Premium Customer Support</li>
                            <li><i class="fas fa-check text-success me-2"></i>+$6M PROP FUNDING ROADMAP</li>
                            <li><i class="fas fa-check text-success me-2"></i>Real Capital Account Management</li>
                            <li><i class="fas fa-check text-success me-2"></i>Eros Prop EA + Eros Gold EA + EA ELITE EA v2.3</li>
                        </ul>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="price">
                            <span class="h4 text-primary fw-bold">$497</span>
                            <small class="text-muted d-block">Per Month</small>
                        </div>
                        <button class="btn btn-primary">
                            <i class="fas fa-play me-2"></i>Get Started
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Eros Expert Advisors Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">
                    <i class="fas fa-robot me-2 text-primary"></i>
                    Our Expert Advisors
                </h2>
                <p class="lead text-muted">Advanced trading algorithms designed for prop firm success</p>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Eros Prop EA -->
        <div class="col-lg-4 col-md-6 mb-4" data-strategy="prop" data-risk="medium">
            <div class="ea-card card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-info text-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-chart-line me-2"></i>Eros Prop EA
                        </h5>
                        <span class="badge bg-info text-white">Core EA</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="ea-stats mb-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-value text-success fw-bold">85%</div>
                                <div class="stat-label small text-muted">Win Rate</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-value text-primary fw-bold">3.2%</div>
                                <div class="stat-label small text-muted">Monthly Return</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-value text-info fw-bold">0.8</div>
                                <div class="stat-label small text-muted">Max DD</div>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Our flagship EA designed specifically for prop firm challenges. Optimized for consistent performance and risk management.</p>
                    <div class="features mb-3">
                        <h6 class="fw-semibold mb-2">Key Features:</h6>
                        <ul class="list-unstyled small">
                            <li><i class="fas fa-check text-success me-2"></i>Prop Firm Optimized</li>
                            <li><i class="fas fa-check text-success me-2"></i>Risk Management</li>
                            <li><i class="fas fa-check text-success me-2"></i>News Filter</li>
                            <li><i class="fas fa-check text-success me-2"></i>Multi-Timeframe</li>
                        </ul>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="price">
                            <span class="h4 text-primary fw-bold">Included</span>
                            <small class="text-muted d-block">In All Plans</small>
                        </div>
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-info-circle me-2"></i>Learn More
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Eros Gold EA -->
        <div class="col-lg-4 col-md-6 mb-4" data-strategy="gold" data-risk="medium">
            <div class="ea-card card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-warning text-dark border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-medal me-2"></i>Eros Gold EA
                        </h5>
                        <span class="badge bg-warning text-dark">Premium</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="ea-stats mb-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-value text-success fw-bold">88%</div>
                                <div class="stat-label small text-muted">Win Rate</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-value text-primary fw-bold">4.1%</div>
                                <div class="stat-label small text-muted">Monthly Return</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-value text-info fw-bold">1.1</div>
                                <div class="stat-label small text-muted">Max DD</div>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Advanced EA with enhanced algorithms for higher returns. Perfect for scaling your prop firm accounts.</p>
                    <div class="features mb-3">
                        <h6 class="fw-semibold mb-2">Key Features:</h6>
                        <ul class="list-unstyled small">
                            <li><i class="fas fa-check text-success me-2"></i>Enhanced Algorithms</li>
                            <li><i class="fas fa-check text-success me-2"></i>Higher Returns</li>
                            <li><i class="fas fa-check text-success me-2"></i>Advanced Risk Management</li>
                            <li><i class="fas fa-check text-success me-2"></i>Multi-Currency Support</li>
                        </ul>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="price">
                            <span class="h4 text-primary fw-bold">Pro+</span>
                            <small class="text-muted d-block">Pro & Diablo</small>
                        </div>
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-info-circle me-2"></i>Learn More
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- EA ELITE EA v2.3 -->
        <div class="col-lg-4 col-md-6 mb-4" data-strategy="elite" data-risk="high">
            <div class="ea-card card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-secondary text-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-crown me-2"></i>EA ELITE EA v2.3
                        </h5>
                        <span class="badge bg-danger text-white">Elite</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="ea-stats mb-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-value text-success fw-bold">92%</div>
                                <div class="stat-label small text-muted">Win Rate</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-value text-primary fw-bold">5.8%</div>
                                <div class="stat-label small text-muted">Monthly Return</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-value text-info fw-bold">1.5</div>
                                <div class="stat-label small text-muted">Max DD</div>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Our most advanced EA with cutting-edge AI algorithms. Maximum performance for experienced traders.</p>
                    <div class="features mb-3">
                        <h6 class="fw-semibold mb-2">Key Features:</h6>
                        <ul class="list-unstyled small">
                            <li><i class="fas fa-check text-success me-2"></i>AI-Powered</li>
                            <li><i class="fas fa-check text-success me-2"></i>Maximum Performance</li>
                            <li><i class="fas fa-check text-success me-2"></i>Advanced ML</li>
                            <li><i class="fas fa-check text-success me-2"></i>Elite Only</li>
                        </ul>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="price">
                            <span class="h4 text-primary fw-bold">Diablo</span>
                            <small class="text-muted d-block">Diablo Only</small>
                        </div>
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-info-circle me-2"></i>Learn More
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="cta-card card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <h3 class="fw-bold mb-3">Ready to Scale Your Prop Firm Journey?</h3>
                    <p class="lead text-muted mb-4">Join successful traders who use Eros EAs to pass challenges and earn consistent payouts.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-rocket me-2"></i>Get Started Now
                        </button>
                        <button class="btn btn-outline-primary btn-lg px-4">
                            <i class="fas fa-phone me-2"></i>Contact Support
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Demo Modal -->
<div class="modal fade" id="demoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-play me-2"></i>Expert Advisor Demo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="demo-video mb-4">
                    <i class="fas fa-video fa-4x text-muted mb-3"></i>
                    <h6 class="text-muted">Demo Video Coming Soon</h6>
                    <p class="text-muted">Watch our expert advisors in action with real market data.</p>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="feature-item">
                            <i class="fas fa-chart-bar fa-2x text-primary mb-2"></i>
                            <h6>Live Performance</h6>
                            <small class="text-muted">Real-time trading results</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-item">
                            <i class="fas fa-cog fa-2x text-success mb-2"></i>
                            <h6>Easy Setup</h6>
                            <small class="text-muted">One-click installation</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-item">
                            <i class="fas fa-shield-alt fa-2x text-warning mb-2"></i>
                            <h6>Risk Management</h6>
                            <small class="text-muted">Built-in safety features</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Hero Section */
.ea-hero {
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

.animate-float {
    animation: float 3s ease-in-out infinite;
}

.animate-bounce {
    animation: bounce 2s infinite;
}

.ea-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.ea-illustration {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

/* Stats Cards */
.stats-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.stats-card:hover::before {
    transform: scaleX(1);
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
}

.stats-icon {
    transition: all 0.3s ease;
}

.stats-card:hover .stats-icon {
    transform: scale(1.1) rotate(5deg);
}

/* EA Cards */
.ea-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.ea-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}

.ea-stats .stat-value {
    font-size: 1.5rem;
    line-height: 1;
}

.ea-stats .stat-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.features ul li {
    padding: 0.25rem 0;
}

/* CTA Card */
.cta-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

/* Buttons */
.btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 600;
    border-radius: 10px;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #1e40af, #3b82f6);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1e3a8a, #1e40af);
}

/* Cards */
.card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 12px;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .ea-hero {
        padding: 2rem !important;
    }
    
    .ea-hero h1 {
        font-size: 2rem !important;
    }
    
    .ea-stats .stat-value {
        font-size: 1.2rem;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .ea-hero {
        background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
    }
    
    .cta-card {
        background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
    }
}

/* Animations */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.ea-card {
    animation: slideInUp 0.6s ease-out;
}

.ea-card:nth-child(1) { animation-delay: 0.1s; }
.ea-card:nth-child(2) { animation-delay: 0.2s; }
.ea-card:nth-child(3) { animation-delay: 0.3s; }
</style>

<script>
function scrollToEAs() {
    document.getElementById('eaGrid').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });
}

function showDemoModal() {
    var modal = new bootstrap.Modal(document.getElementById('demoModal'));
    modal.show();
}

function filterEAs() {
    const search = document.getElementById('search').value.toLowerCase();
    const strategy = document.getElementById('strategy').value;
    const risk = document.getElementById('risk').value;
    
    const cards = document.querySelectorAll('.ea-card');
    
    cards.forEach(card => {
        const cardElement = card.closest('[data-strategy]');
        const cardStrategy = cardElement.getAttribute('data-strategy');
        const cardRisk = cardElement.getAttribute('data-risk');
        const cardText = cardElement.textContent.toLowerCase();
        
        let show = true;
        
        if (search && !cardText.includes(search)) {
            show = false;
        }
        
        if (strategy && cardStrategy !== strategy) {
            show = false;
        }
        
        if (risk && cardRisk !== risk) {
            show = false;
        }
        
        cardElement.style.display = show ? 'block' : 'none';
    });
}

// Search as you type
document.getElementById('search').addEventListener('input', filterEAs);
document.getElementById('strategy').addEventListener('change', filterEAs);
document.getElementById('risk').addEventListener('change', filterEAs);
</script>
@endsection
