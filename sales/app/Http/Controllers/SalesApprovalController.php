<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SalesApproval;
use App\Models\SalesAuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SalesApprovalController extends Controller
{
    public function update(Request $request, SalesApproval $approval): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:Approved,Rejected'],
            'review_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if (! in_array(strtolower((string) $request->session()->get('employee_role')), ['manager', 'administrator', 'admin', 'owner', 'supervisor'], true)) {
            abort(403, 'Manager approval is required.');
        }

        $approval->update([
            'status' => $data['status'],
            'reviewed_by' => $request->session()->get('employee_id') ?? Employee::query()->value('employee_id'),
            'review_notes' => $data['review_notes'] ?? null,
            'reviewed_at' => now(),
        ]);
        SalesAuditLog::record($approval, 'approval_reviewed', null, $approval->fresh()->toArray(), $data['review_notes'] ?? null);

        return back()->with('success', "Request {$data['status']}.");
    }
}
