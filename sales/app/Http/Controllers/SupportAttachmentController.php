<?php

namespace App\Http\Controllers;

use App\Models\SupportAttachment;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SupportAttachmentController extends Controller
{
    public function store(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $data = $request->validate([
            'attachment' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,txt'],
        ]);
        $file = $data['attachment'];
        $path = $file->store("support/tickets/{$ticket->ticket_id}", 'public');

        DB::transaction(function () use ($ticket, $file, $path, $request): void {
            $ticket->attachments()->create([
                'uploaded_by' => $request->session()->get('employee_id'),
                'original_name' => $file->getClientOriginalName(),
                'storage_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'created_at' => now(),
            ]);
            $ticket->caseEvents()->create([
                'employee_id' => $request->session()->get('employee_id'),
                'event_type' => 'Attachment Added',
                'description' => "Evidence file {$file->getClientOriginalName()} was attached.",
                'created_at' => now(),
            ]);
        });

        return back()->with('success', 'Ticket attachment uploaded.');
    }

    public function destroy(SupportAttachment $attachment): RedirectResponse
    {
        Storage::disk('public')->delete($attachment->storage_path);
        $attachment->delete();

        return back()->with('success', 'Ticket attachment deleted.');
    }
}
