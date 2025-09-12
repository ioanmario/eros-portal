<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with(['user', 'assignedTo', 'messages'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::whereIn('status', ['open', 'in_progress'])->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
            'urgent' => SupportTicket::where('priority', 'urgent')->whereIn('status', ['open', 'in_progress'])->count(),
        ];

        $admins = User::role('admin')->get();

        return view('admin.support.index', compact('tickets', 'stats', 'admins'));
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load(['user', 'assignedTo', 'messages.user']);
        $admins = User::role('admin')->get();

        return view('admin.support.show', compact('ticket', 'admins'));
    }

    public function assign(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $ticket->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'in_progress',
        ]);

        return redirect()->back()->with('success', 'Ticket assigned successfully!');
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $updateData = ['status' => $request->status];

        if ($request->status === 'resolved') {
            $updateData['resolved_at'] = now();
        } elseif ($request->status === 'closed') {
            $updateData['closed_at'] = now();
        }

        $ticket->update($updateData);

        return redirect()->back()->with('success', 'Ticket status updated successfully!');
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
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
            'is_admin' => true,
            'attachments' => $attachments,
        ]);

        // Update ticket status to in_progress if it was open
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return redirect()->back()->with('success', 'Admin reply sent successfully!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:assign,close,resolve,delete',
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:support_tickets,id',
        ]);

        $tickets = SupportTicket::whereIn('id', $request->ticket_ids);

        switch ($request->action) {
            case 'assign':
                if ($request->has('assigned_to')) {
                    $tickets->update([
                        'assigned_to' => $request->assigned_to,
                        'status' => 'in_progress',
                    ]);
                }
                break;
            case 'close':
                $tickets->update([
                    'status' => 'closed',
                    'closed_at' => now(),
                ]);
                break;
            case 'resolve':
                $tickets->update([
                    'status' => 'resolved',
                    'resolved_at' => now(),
                ]);
                break;
            case 'delete':
                $tickets->delete();
                break;
        }

        return redirect()->back()->with('success', 'Bulk action completed successfully!');
    }
}
