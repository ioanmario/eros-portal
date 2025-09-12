@extends('layouts.app')

@section('title', 'Support Center')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="support-hero bg-gradient-primary text-white rounded-4 p-5 position-relative overflow-hidden">
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
                                <i class="fas fa-headset me-3"></i>
                                Support Center
                            </h1>
                            <p class="lead mb-4 animate-fade-in-delay">Get instant help with your account, trading, or technical issues. Our team is here to assist you 24/7.</p>
                            <div class="d-flex flex-wrap gap-3 animate-fade-in-delay-2">
                                <a href="{{ route('support.create') }}" class="btn btn-light btn-lg px-4 py-3 shadow-sm animate-bounce">
                                    <i class="fas fa-plus me-2"></i>Create New Ticket
                                </a>
                                <a href="mailto:support@eros-portal.com" class="btn btn-outline-light btn-lg px-4 py-3">
                                    <i class="fas fa-envelope me-2"></i>Email Support
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 text-center">
                            <div class="support-illustration animate-float">
                                <i class="fas fa-comments fa-5x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Contact Options -->
    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <div class="contact-card card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="contact-icon mb-3">
                        <i class="fas fa-envelope fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title fw-bold">Email Support</h5>
                    <p class="card-text text-muted mb-4">Get help via email within 24 hours</p>
                    <a href="mailto:support@eros-portal.com" class="btn btn-outline-primary btn-lg px-4">
                        <i class="fas fa-envelope me-2"></i>support@eros-portal.com
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="contact-card card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="contact-icon mb-3">
                        <i class="fab fa-telegram fa-3x text-info"></i>
                    </div>
                    <h5 class="card-title fw-bold">Telegram Support</h5>
                    <p class="card-text text-muted mb-4">Get instant help via Telegram</p>
                    <a href="https://t.me/eros_support" target="_blank" class="btn btn-outline-info btn-lg px-4">
                        <i class="fab fa-telegram me-2"></i>@eros_support
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-ticket-alt fa-2x text-primary"></i>
                    </div>
                    <h3 class="fw-bold text-primary mb-2">{{ $stats['total'] }}</h3>
                    <p class="text-muted mb-0 fw-semibold">Total Tickets</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                    <h3 class="fw-bold text-warning mb-2">{{ $stats['open'] }}</h3>
                    <p class="text-muted mb-0 fw-semibold">Open Tickets</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <h3 class="fw-bold text-success mb-2">{{ $stats['resolved'] }}</h3>
                    <p class="text-muted mb-0 fw-semibold">Resolved</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-archive fa-2x text-secondary"></i>
                    </div>
                    <h3 class="fw-bold text-secondary mb-2">{{ $stats['closed'] }}</h3>
                    <p class="text-muted mb-0 fw-semibold">Closed</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="row">
        <div class="col-12">
            <div class="tickets-card card border-0 shadow-sm">
                <div class="card-header bg-gradient-light border-0 py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-list me-2 text-primary"></i>
                            Your Support Tickets
                        </h5>
                        <span class="badge bg-primary px-3 py-2">{{ $stats['total'] }} Total</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($tickets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 tickets-table">
                                <thead class="table-header">
                                    <tr>
                                        <th class="border-0 py-4 px-4 fw-bold text-uppercase small">Ticket #</th>
                                        <th class="border-0 py-4 px-4 fw-bold text-uppercase small">Subject</th>
                                        <th class="border-0 py-4 px-4 fw-bold text-uppercase small">Category</th>
                                        <th class="border-0 py-4 px-4 fw-bold text-uppercase small">Priority</th>
                                        <th class="border-0 py-4 px-4 fw-bold text-uppercase small">Status</th>
                                        <th class="border-0 py-4 px-4 fw-bold text-uppercase small">Assigned To</th>
                                        <th class="border-0 py-4 px-4 fw-bold text-uppercase small">Created</th>
                                        <th class="border-0 py-4 px-4 fw-bold text-uppercase small">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr class="ticket-row">
                                            <td class="py-4 px-4">
                                                <span class="ticket-number fw-bold text-primary">{{ $ticket->ticket_number }}</span>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="ticket-icon me-3">
                                                        <i class="{{ $ticket->category_icon }} text-muted"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold text-dark">{{ Str::limit($ticket->subject, 40) }}</div>
                                                        @if($ticket->messages->count() > 0)
                                                            <small class="text-muted">
                                                                <i class="fas fa-comment me-1"></i>{{ $ticket->messages->count() }} message(s)
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <span class="badge category-badge bg-light text-dark px-3 py-2">
                                                    {{ ucfirst($ticket->category) }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4">
                                                <span class="badge priority-badge bg-{{ $ticket->priority_color }} text-white px-3 py-2">
                                                    <i class="fas fa-flag me-1"></i>{{ ucfirst($ticket->priority) }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4">
                                                <span class="badge status-badge bg-{{ $ticket->status_color }} text-white px-3 py-2">
                                                    <i class="fas fa-circle me-1"></i>{{ ucfirst($ticket->status) }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4">
                                                @if($ticket->assignedTo)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                            {{ substr($ticket->assignedTo->name, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold text-dark">{{ $ticket->assignedTo->name }}</div>
                                                            <small class="text-muted">Admin</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">
                                                        <i class="fas fa-user-slash me-1"></i>Unassigned
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="text-dark fw-semibold">{{ $ticket->created_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $ticket->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td class="py-4 px-4">
                                                <a href="{{ route('support.show', $ticket) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye me-1"></i>View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white border-0 py-3">
                            {{ $tickets->links() }}
                        </div>
                    @else
                        <div class="empty-state text-center py-5">
                            <div class="empty-icon mb-4">
                                <i class="fas fa-ticket-alt fa-5x text-muted opacity-50"></i>
                            </div>
                            <h4 class="text-muted fw-bold mb-3">No Support Tickets Yet</h4>
                            <p class="text-muted mb-4 lead">You haven't created any support tickets. Need help? Create your first ticket!</p>
                            <a href="{{ route('support.create') }}" class="btn btn-primary btn-lg px-4 py-3">
                                <i class="fas fa-plus me-2"></i>Create Your First Ticket
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Hero Section */
.support-hero {
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

.support-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.support-illustration {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

/* Contact Cards */
.contact-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.contact-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
    border-color: rgba(0, 123, 255, 0.2);
}

.contact-icon {
    transition: all 0.3s ease;
}

.contact-card:hover .contact-icon {
    transform: scale(1.1);
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

/* Tickets Table */
.tickets-card {
    border: 1px solid rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.table-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.tickets-table {
    font-size: 0.95rem;
}

.ticket-row {
    transition: all 0.2s ease;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.ticket-row:hover {
    background: linear-gradient(135deg, rgba(0, 123, 255, 0.02) 0%, rgba(102, 126, 234, 0.02) 100%);
    transform: translateX(2px);
}

.ticket-number {
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
}

.ticket-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 123, 255, 0.1);
    border-radius: 10px;
    font-size: 1.1rem;
}

/* Badges */
.category-badge, .priority-badge, .status-badge {
    font-size: 0.8rem;
    font-weight: 600;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.priority-badge {
    position: relative;
    overflow: hidden;
}

.priority-badge::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.priority-badge:hover::before {
    left: 100%;
}

/* Avatar */
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.avatar-sm:hover {
    transform: scale(1.1);
}

/* Empty State */
.empty-state {
    padding: 4rem 2rem;
}

.empty-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 0.8; }
}

/* Buttons */
.btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 600;
    border-radius: 8px;
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
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .support-hero {
        padding: 2rem !important;
    }
    
    .support-hero h1 {
        font-size: 2rem !important;
    }
    
    .tickets-table {
        font-size: 0.85rem;
    }
    
    .ticket-icon {
        width: 30px;
        height: 30px;
        font-size: 0.9rem;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .support-hero {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
    }
    
    .table-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
    }
    
    .ticket-row:hover {
        background: linear-gradient(135deg, rgba(30, 64, 175, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
    }
}
</style>
@endsection