@extends('layouts.app')

@section('title', 'Create Support Ticket')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="create-hero bg-gradient-primary text-white rounded-4 p-4 position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 opacity-10">
                    <i class="fas fa-plus-circle" style="font-size: 6rem;"></i>
                </div>
                <div class="d-flex align-items-center">
                    <a href="{{ route('support.index') }}" class="btn btn-light me-4">
                        <i class="fas fa-arrow-left me-2"></i>Back to Support
                    </a>
                    <div>
                        <h1 class="h2 mb-2 fw-bold">
                            <i class="fas fa-plus-circle me-3"></i>
                            Create Support Ticket
                        </h1>
                        <p class="mb-0 opacity-90">Describe your issue and we'll help you resolve it quickly</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Ticket Form -->
            <div class="form-card card border-0 shadow-sm">
                <div class="card-header bg-gradient-light border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-edit me-2 text-primary"></i>
                        Ticket Details
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('support.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Subject -->
                        <div class="form-group mb-4">
                            <label for="subject" class="form-label fw-bold text-dark mb-2">
                                <i class="fas fa-tag me-2 text-primary"></i>Subject <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('subject') is-invalid @enderror" 
                                   id="subject" 
                                   name="subject" 
                                   value="{{ old('subject') }}" 
                                   placeholder="Brief description of your issue"
                                   required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category and Priority -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category" class="form-label fw-bold text-dark mb-2">
                                        <i class="fas fa-folder me-2 text-primary"></i>Category <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg @error('category') is-invalid @enderror" 
                                            id="category" 
                                            name="category" 
                                            required>
                                        <option value="">Select a category</option>
                                        <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>
                                            Technical Issue
                                        </option>
                                        <option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>
                                            Billing & Payments
                                        </option>
                                        <option value="account" {{ old('category') == 'account' ? 'selected' : '' }}>
                                            Account Issues
                                        </option>
                                        <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>
                                            General Question
                                        </option>
                                        <option value="bug_report" {{ old('category') == 'bug_report' ? 'selected' : '' }}>
                                            Bug Report
                                        </option>
                                        <option value="feature_request" {{ old('category') == 'feature_request' ? 'selected' : '' }}>
                                            Feature Request
                                        </option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority" class="form-label fw-bold text-dark mb-2">
                                        <i class="fas fa-flag me-2 text-primary"></i>Priority <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg @error('priority') is-invalid @enderror" 
                                            id="priority" 
                                            name="priority" 
                                            required>
                                        <option value="">Select priority</option>
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>
                                            Low
                                        </option>
                                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>
                                            Medium
                                        </option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>
                                            High
                                        </option>
                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>
                                            Urgent
                                        </option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group mb-4">
                            <label for="description" class="form-label fw-bold text-dark mb-2">
                                <i class="fas fa-align-left me-2 text-primary"></i>Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control form-control-lg @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="6" 
                                      placeholder="Please provide detailed information about your issue..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Please be as specific as possible to help us resolve your issue quickly.
                            </div>
                        </div>

                        <!-- Attachments -->
                        <div class="form-group mb-4">
                            <label for="attachments" class="form-label fw-bold text-dark mb-2">
                                <i class="fas fa-paperclip me-2 text-primary"></i>Attachments
                            </label>
                            <div class="file-upload-area">
                                <input type="file" 
                                       class="form-control form-control-lg @error('attachments.*') is-invalid @enderror" 
                                       id="attachments" 
                                       name="attachments[]" 
                                       multiple
                                       accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt">
                                @error('attachments.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Supported formats: JPG, PNG, GIF, PDF, DOC, DOCX, TXT (Max 10MB each)
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between pt-3 border-top">
                            <a href="{{ route('support.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i>Create Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Help Tips -->
            <div class="tips-card card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-warning border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-lightbulb me-2"></i>
                        Tips for Better Support
                    </h5>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="tip-item mb-3 d-flex align-items-start">
                            <div class="tip-icon me-3">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <span class="fw-semibold">Be specific about the issue</span>
                        </li>
                        <li class="tip-item mb-3 d-flex align-items-start">
                            <div class="tip-icon me-3">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <span class="fw-semibold">Include error messages if any</span>
                        </li>
                        <li class="tip-item mb-3 d-flex align-items-start">
                            <div class="tip-icon me-3">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <span class="fw-semibold">Attach screenshots or files</span>
                        </li>
                        <li class="tip-item mb-3 d-flex align-items-start">
                            <div class="tip-icon me-3">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <span class="fw-semibold">Mention steps to reproduce</span>
                        </li>
                        <li class="tip-item mb-0 d-flex align-items-start">
                            <div class="tip-icon me-3">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <span class="fw-semibold">Provide your account details</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="contact-card card border-0 shadow-sm">
                <div class="card-header bg-gradient-info border-0 py-4">
                    <h5 class="mb-0 fw-bold text-white">
                        <i class="fas fa-phone me-2"></i>
                        Other Ways to Contact
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="contact-item mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="contact-icon me-3">
                                <i class="fas fa-envelope text-primary"></i>
                            </div>
                            <strong class="text-dark">Email Support</strong>
                        </div>
                        <a href="mailto:support@eros-portal.com" class="text-decoration-none fw-semibold text-primary">
                            support@eros-portal.com
                        </a>
                        <small class="d-block text-muted mt-1">
                            <i class="fas fa-clock me-1"></i>Response within 24 hours
                        </small>
                    </div>
                    
                    <div class="contact-item mb-0">
                        <div class="d-flex align-items-center mb-3">
                            <div class="contact-icon me-3">
                                <i class="fab fa-telegram text-info"></i>
                            </div>
                            <strong class="text-dark">Telegram Support</strong>
                        </div>
                        <a href="https://t.me/eros_support" target="_blank" class="text-decoration-none fw-semibold text-info">
                            @eros_support
                        </a>
                        <small class="d-block text-muted mt-1">
                            <i class="fas fa-bolt me-1"></i>Instant response
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Hero Section */
.create-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.create-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

/* Form Card */
.form-card {
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.form-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
}

/* Form Groups */
.form-group {
    position: relative;
}

.form-group .form-label {
    transition: all 0.3s ease;
}

.form-group:focus-within .form-label {
    color: #667eea !important;
    transform: translateY(-2px);
}

/* Form Controls */
.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 500;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    transform: translateY(-1px);
}

.form-control-lg, .form-select-lg {
    padding: 0.75rem 1rem;
    font-size: 1.1rem;
}

/* File Upload Area */
.file-upload-area {
    position: relative;
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    padding: 1rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.file-upload-area:hover {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.05);
}

.file-upload-area input[type="file"] {
    border: none;
    background: transparent;
}

/* Tips Card */
.tips-card {
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.tips-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
}

.tip-item {
    transition: all 0.3s ease;
    padding: 0.5rem;
    border-radius: 8px;
}

.tip-item:hover {
    background: rgba(40, 167, 69, 0.1);
    transform: translateX(5px);
}

.tip-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(40, 167, 69, 0.1);
    border-radius: 50%;
    font-size: 0.9rem;
}

/* Contact Card */
.contact-card {
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.contact-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
}

.contact-item {
    transition: all 0.3s ease;
    padding: 0.75rem;
    border-radius: 8px;
}

.contact-item:hover {
    background: rgba(0, 123, 255, 0.05);
    transform: translateX(5px);
}

.contact-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 123, 255, 0.1);
    border-radius: 10px;
    font-size: 1.2rem;
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}

.btn-outline-secondary {
    border: 2px solid #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    border-color: #6c757d;
    color: white;
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

/* Form Text */
.form-text {
    font-size: 0.9rem;
    color: #6c757d;
    margin-top: 0.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .create-hero {
        padding: 1.5rem !important;
    }
    
    .create-hero h1 {
        font-size: 1.5rem !important;
    }
    
    .form-control-lg, .form-select-lg {
        font-size: 1rem;
        padding: 0.6rem 0.8rem;
    }
    
    .btn-lg {
        padding: 0.6rem 1.2rem;
        font-size: 1rem;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .create-hero {
        background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
    }
    
    .form-control, .form-select {
        background: #2d3748;
        border-color: #4a5568;
        color: #e2e8f0;
    }
    
    .form-control:focus, .form-select:focus {
        background: #2d3748;
        border-color: #667eea;
        color: #e2e8f0;
    }
    
    .file-upload-area {
        background: #2d3748;
        border-color: #4a5568;
    }
    
    .file-upload-area:hover {
        background: rgba(102, 126, 234, 0.1);
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

.form-group {
    animation: slideInUp 0.6s ease-out;
}

.form-group:nth-child(1) { animation-delay: 0.1s; }
.form-group:nth-child(2) { animation-delay: 0.2s; }
.form-group:nth-child(3) { animation-delay: 0.3s; }
.form-group:nth-child(4) { animation-delay: 0.4s; }
.form-group:nth-child(5) { animation-delay: 0.5s; }
</style>
@endsection
