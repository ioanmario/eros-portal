<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SupportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $tickets = $user->supportTickets()
            ->with(['assignedTo', 'messages'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total' => $tickets->total(),
            'open' => $user->supportTickets()->whereIn('status', ['open', 'in_progress'])->count(),
            'resolved' => $user->supportTickets()->where('status', 'resolved')->count(),
            'closed' => $user->supportTickets()->where('status', 'closed')->count(),
        ];

        return view('support.index', compact('tickets', 'stats'));
    }

    public function create()
    {
        return view('support.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:technical,billing,account,general,bug_report,feature_request',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('support/attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                ];
            }
        }

        $ticket = Auth::user()->supportTickets()->create([
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'category' => $request->category,
            'attachments' => $attachments,
        ]);

        // Send notification email to admin
        $this->sendTicketNotification($ticket, 'new_ticket');

        return redirect()->route('support.show', $ticket)
            ->with('success', 'Support ticket created successfully! Ticket number: ' . $ticket->ticket_number);
    }

    public function show(SupportTicket $ticket)
    {
        // Ensure user can only view their own tickets (unless admin)
        if ($ticket->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access to support ticket.');
        }

        $ticket->load(['user', 'assignedTo', 'messages.user']);

        return view('support.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        // Ensure user can only reply to their own tickets (unless admin)
        if ($ticket->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access to support ticket.');
        }

        $request->validate([
            'message' => 'required|string|min:1',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('support/attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                ];
            }
        }

        $message = $ticket->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin' => Auth::user()->hasRole('admin'),
            'attachments' => $attachments,
        ]);

        // Update ticket status if user replied
        if (!$message->is_admin && $ticket->status === 'resolved') {
            $ticket->update(['status' => 'open']);
        }

        // Send notification email
        $this->sendTicketNotification($ticket, 'new_reply');

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    public function close(SupportTicket $ticket)
    {
        // Only ticket owner or admin can close
        if ($ticket->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access to support ticket.');
        }

        $ticket->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Ticket closed successfully!');
    }

    private function sendTicketNotification(SupportTicket $ticket, string $type)
    {
        try {
            // This would send email notifications
            // For now, we'll just log it
            \Log::info("Support ticket notification: {$type} for ticket {$ticket->ticket_number}");
        } catch (\Exception $e) {
            \Log::error("Failed to send support ticket notification: " . $e->getMessage());
        }
    }

    public function downloadAttachment(SupportTicket $ticket, $attachmentIndex)
    {
        if ($ticket->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access to attachment.');
        }

        $attachments = $ticket->attachments ?? [];
        if (!isset($attachments[$attachmentIndex])) {
            abort(404, 'Attachment not found.');
        }

        $attachment = $attachments[$attachmentIndex];
        $filePath = storage_path('app/public/' . $attachment['path']);

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath, $attachment['name']);
    }
}