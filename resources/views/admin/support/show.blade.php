@extends('layouts.app')

@section('title', 'Admin - Ticket #' . $ticket->ticket_number)

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <a href="{{ route('admin.support.index') }}" class="btn btn-outline-secondary me-3">
                        <i class="fas fa-arrow-left me-2"></i>Back to Tickets
                    </a>
                    <div>
                        <h1 class="h3 mb-0 fw-bold">
                            <i class="fas fa-ticket-alt me-2 text-primary"></i>
                            Ticket #{{ $ticket->ticket_number }}
                        </h1>
                        <p class="text-muted mb-0">{{ $ticket->subject }}</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-{{ $ticket->priority_color }} text-white px-3 py-2">
                        <i class="fas fa-flag me-1"></i>{{ ucfirst($ticket->priority) }}
                    </span>
                    <span class="badge bg-{{ $ticket->status_color }} text-white px-3 py-2">
                        <i class="fas fa-circle me-1"></i>{{ ucfirst($ticket->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Ticket Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-semibold">Ticket Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>User:</strong>
                            <div class="d-flex align-items-center mt-1">
                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                    {{ substr($ticket->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $ticket->user->name }}</div>
                                    <small class="text-muted">{{ $ticket->user->email }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong>Category:</strong>
                            <span class="badge bg-light text-dark ms-2">
                                <i class="{{ $ticket->category_icon }} me-1"></i>{{ ucfirst($ticket->category) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Created:</strong>
                            <span class="ms-2">{{ $ticket->created_at->format('M d, Y \a\t h:i A') }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Last Updated:</strong>
                            <span class="ms-2">{{ $ticket->updated_at->format('M d, Y \a\t h:i A') }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Description:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {!! nl2br(e($ticket->description)) !!}
                        </div>
                    </div>

                    @if($ticket->attachments && count($ticket->attachments) > 0)
                        <div class="mb-0">
                            <strong>Attachments:</strong>
                            <div class="mt-2">
                                @foreach($ticket->attachments as $index => $attachment)
                                    <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-paperclip me-2 text-muted"></i>
                                            <span>{{ $attachment['name'] }}</span>
                                            <small class="text-muted ms-2">({{ number_format($attachment['size'] / 1024, 1) }} KB)</small>
                                        </div>
                                        <a href="{{ route('support.download', ['ticket' => $ticket, 'attachment' => $index]) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i>Download
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Messages -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-comments me-2"></i>
                        Conversation ({{ $ticket->messages->count() }} message{{ $ticket->messages->count() !== 1 ? 's' : '' }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($ticket->messages->count() > 0)
                        <div class="messages-container" style="max-height: 500px; overflow-y: auto;">
                            @foreach($ticket->messages as $message)
                                <div class="message mb-4 {{ $message->is_admin ? 'admin-message' : 'user-message' }}">
                                    <div class="d-flex {{ $message->is_admin ? 'justify-content-end' : 'justify-content-start' }}">
                                        <div class="message-bubble {{ $message->is_admin ? 'bg-primary text-white' : 'bg-light' }}" 
                                             style="max-width: 70%; padding: 12px 16px; border-radius: 18px;">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="avatar-sm {{ $message->is_admin ? 'bg-white text-primary' : 'bg-primary text-white' }} rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ substr($message->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $message->user->name }}</div>
                                                    <small class="{{ $message->is_admin ? 'text-white-50' : 'text-muted' }}">
                                                        {{ $message->created_at->format('M d, Y \a\t h:i A') }}
                                                        @if($message->is_admin)
                                                            <span class="badge bg-white text-primary ms-1">Admin</span>
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="message-content">
                                                {!! nl2br(e($message->message)) !!}
                                            </div>
                                            
                                            @if($message->attachments && count($message->attachments) > 0)
                                                <div class="mt-2">
                                                    @foreach($message->attachments as $index => $attachment)
                                                        <div class="d-flex align-items-center justify-content-between p-2 {{ $message->is_admin ? 'bg-white bg-opacity-25' : 'bg-white' }} rounded mb-1">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-paperclip me-2"></i>
                                                                <span>{{ $attachment['name'] }}</span>
                                                                <small class="text-muted ms-2">({{ number_format($attachment['size'] / 1024, 1) }} KB)</small>
                                                            </div>
                                                            <a href="{{ route('support.download', ['ticket' => $ticket, 'attachment' => $index]) }}" 
                                                               class="btn btn-sm {{ $message->is_admin ? 'btn-outline-light' : 'btn-outline-primary' }}">
                                                                <i class="fas fa-download me-1"></i>Download
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No messages yet</h6>
                            <p class="text-muted">Start the conversation by replying to this ticket.</p>
                        </div>
                    @endif

                    <!-- Admin Reply Form -->
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="fw-semibold mb-3">Admin Reply</h6>
                        <form action="{{ route('admin.support.reply', $ticket) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          name="message" 
                                          rows="4" 
                                          placeholder="Type your admin reply here..."
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="attachments" class="form-label">Attachments (Optional)</label>
                                <input type="file" 
                                       class="form-control @error('attachments.*') is-invalid @enderror" 
                                       name="attachments[]" 
                                       multiple
                                       accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt">
                                @error('attachments.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Supported formats: JPG, PNG, GIF, PDF, DOC, DOCX, TXT (Max 10MB each)
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Send Admin Reply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Admin Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-cog me-2 text-primary"></i>
                        Admin Actions
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Assign Ticket -->
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2">Assign Ticket</h6>
                        <form action="{{ route('admin.support.assign', $ticket) }}" method="POST">
                            @csrf
                            <div class="d-flex gap-2">
                                <select class="form-select" name="assigned_to" required>
                                    <option value="">Select Admin</option>
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->id }}" 
                                                {{ $ticket->assigned_to == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Update Status -->
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2">Update Status</h6>
                        <form action="{{ route('admin.support.status', $ticket) }}" method="POST">
                            @csrf
                            <div class="d-flex gap-2">
                                <select class="form-select" name="status" required>
                                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Quick Actions -->
                    <div class="d-grid gap-2">
                        <button onclick="window.print()" class="btn btn-outline-secondary">
                            <i class="fas fa-print me-2"></i>Print Ticket
                        </button>
                        <a href="mailto:{{ $ticket->user->email }}?subject=Re: {{ $ticket->subject }} (Ticket #{{ $ticket->ticket_number }})" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i>Email User
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-user me-2 text-primary"></i>
                        User Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Name:</strong>
                        <div class="mt-1">{{ $ticket->user->name }}</div>
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong>
                        <div class="mt-1">
                            <a href="mailto:{{ $ticket->user->email }}" class="text-decoration-none">
                                {{ $ticket->user->email }}
                            </a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Member Since:</strong>
                        <div class="mt-1">{{ $ticket->user->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="mb-3">
                        <strong>Total Tickets:</strong>
                        <div class="mt-1">{{ $ticket->user->supportTickets()->count() }}</div>
                    </div>
                    @if($ticket->user->isAffiliate())
                        <div class="mb-0">
                            <strong>Affiliate Status:</strong>
                            <div class="mt-1">
                                <span class="badge bg-success text-white">Active Affiliate</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    font-weight: 600;
}

.message-bubble {
    word-wrap: break-word;
}

.messages-container {
    scrollbar-width: thin;
    scrollbar-color: #dee2e6 #f8f9fa;
}

.messages-container::-webkit-scrollbar {
    width: 6px;
}

.messages-container::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 3px;
}

.messages-container::-webkit-scrollbar-thumb {
    background: #dee2e6;
    border-radius: 3px;
}

.messages-container::-webkit-scrollbar-thumb:hover {
    background: #adb5bd;
}

@media print {
    .btn, .card-header, .border-top {
        display: none !important;
    }
}
</style>
@endsection
