<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SupportTicket;
use App\Models\TicketAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupportTicketController extends Controller
{
    public function show(Request $request, $ticketId)
    {
        $ticket = SupportTicket::with([
            'customer',
            'product',
            'order',
            'serviceContract.customer',
            'latestAssignment.employee',
            'ticketAssignments.employee',
        ])->findOrFail($ticketId);

        $assignedEmployee = $ticket->latestAssignment?->employee;

        return response()->json([
            'ticket' => [
                'ticket_id' => $ticket->ticket_id,
                'ticket_type' => $ticket->ticket_type,
                'subject' => $ticket->subject,
                'description' => $ticket->description,
                'priority' => $ticket->priority,
                'status' => $ticket->status,
                'created_at' => optional($ticket->created_at)->format('Y-m-d H:i'),
                'due_date' => optional($ticket->due_date)->format('Y-m-d H:i'),
                'resolved_at' => optional($ticket->resolved_at)->format('Y-m-d H:i'),
                'closed_at' => optional($ticket->closed_at)->format('Y-m-d H:i'),
                'customer' => [
                    'name' => optional($ticket->customer)->full_name,
                    'email' => optional($ticket->customer)->email,
                ],
                'product' => [
                    'product_name' => optional($ticket->product)->product_name,
                    'sku' => optional($ticket->product)->sku,
                ],
                'service_contract' => $ticket->serviceContract ? [
                    'contract_id' => $ticket->serviceContract->contract_id,
                    'contract_number' => $ticket->serviceContract->contract_number,
                    'customer' => optional($ticket->serviceContract->customer)->full_name,
                    'service_type' => $ticket->serviceContract->service_type,
                    'service_start' => optional($ticket->serviceContract->service_start)->format('Y-m-d'),
                    'service_end' => optional($ticket->serviceContract->service_end)->format('Y-m-d'),
                ] : null,
                'order_id' => $ticket->order_id,
                'order_number' => optional($ticket->order)->order_number,
            ],
            'assignedEmployee' => [
                'employee_id' => optional($assignedEmployee)->employee_id,
                'name' => optional($assignedEmployee)->full_name,
                'department' => optional($assignedEmployee)->department,
                'assigned_at' => optional($ticket->latestAssignment?->assigned_at)->format('Y-m-d H:i'),
            ],
            'assignmentHistory' => $ticket->ticketAssignments->map(fn (TicketAssignment $assignment): array => [
                'employee_id' => $assignment->employee_id,
                'name' => $assignment->employee?->full_name,
                'department' => $assignment->employee?->department,
                'assigned_at' => optional($assignment->assigned_at)->format('Y-m-d H:i'),
                'status' => $assignment->assignment_status,
            ])->values(),
        ]);
    }

    public function assignForm(Request $request, $ticketId)
    {
        $ticket = SupportTicket::with(['customer', 'product', 'latestAssignment.employee', 'ticketAssignments.employee'])->findOrFail($ticketId);

        $employees = Employee::query()
            ->orderBy('first_name')
            ->get(['employee_id', 'first_name', 'last_name', 'department']);

        $currentEmployee = $ticket->latestAssignment?->employee;

        return response()->json([
            'ticket' => [
                'ticket_id' => $ticket->ticket_id,
                'priority' => $ticket->priority,
                'status' => $ticket->status,
                'due_date' => optional($ticket->due_date)->format('Y-m-d'),
                'customer' => $ticket->customer?->full_name,
                'product_name' => $ticket->product?->product_name,
            ],
            'employees' => $employees->map(function (Employee $e) {
                return [
                    'employee_id' => $e->employee_id,
                    'name' => $e->full_name,
                    'department' => $e->department,
                ];
            })->values(),
            'currentEmployeeId' => optional($currentEmployee)->employee_id,
            'assignmentHistory' => $ticket->ticketAssignments->map(fn (TicketAssignment $assignment): array => [
                'name' => $assignment->employee?->full_name,
                'department' => $assignment->employee?->department,
                'assigned_at' => optional($assignment->assigned_at)->format('Y-m-d H:i'),
                'status' => $assignment->assignment_status,
            ])->values(),
        ]);
    }

    public function assign(Request $request, $ticketId)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => ['required', 'integer', 'exists:employees,employee_id'],
        ]);

        if ($validator->fails()) {
            return $this->validationFailure($request, $validator);
        }

        $ticket = SupportTicket::query()->findOrFail($ticketId);

        $assignmentResult = DB::transaction(function () use ($request, $ticket): array {
            $activeAssignment = $ticket->ticketAssignments()
                ->whereRaw('LOWER(assignment_status) IN (?, ?)', ['assigned', 'active'])
                ->lockForUpdate()
                ->first();

            if ($activeAssignment?->employee_id === (int) $request->input('employee_id')) {
                return ['changed' => false, 'assignment' => $activeAssignment];
            }

            $ticket->ticketAssignments()
                ->whereRaw('LOWER(assignment_status) IN (?, ?)', ['assigned', 'active'])
                ->update(['assignment_status' => 'Reassigned']);

            return [
                'changed' => true,
                'assignment' => TicketAssignment::create([
                    'ticket_id' => $ticket->ticket_id,
                    'employee_id' => (int) $request->input('employee_id'),
                    'assigned_at' => now(),
                    'assignment_status' => 'Active',
                ]),
            ];
        });

        $employee = Employee::find($request->input('employee_id'));

        $message = $assignmentResult['changed'] ? 'Ticket assigned successfully.' : 'This employee is already assigned to the ticket.';

        if (! $request->expectsJson()) {
            return redirect()->route('support.tickets')->with('success', $message);
        }

        return response()->json([
            'message' => $message,
            'changed' => $assignmentResult['changed'],
            'assignedEmployee' => [
                'employee_id' => $employee?->employee_id,
                'name' => $employee?->full_name,
            ],
        ]);
    }

    public function updateStatus(Request $request, $ticketId)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:Open,Pending,In Progress,Resolved,Closed,Escalated'],
        ]);

        if ($validator->fails()) {
            return $this->validationFailure($request, $validator);
        }

        $ticket = SupportTicket::query()->findOrFail($ticketId);
        $status = $request->string('status')->toString();

        DB::transaction(function () use ($ticket, $status): void {
            $ticket->status = $status;

            if ($status === 'Resolved') {
                $ticket->resolved_at ??= now();
                $ticket->closed_at = null;
            } elseif ($status === 'Closed') {
                $ticket->resolved_at ??= now();
                $ticket->closed_at ??= now();
            } else {
                $ticket->resolved_at = null;
                $ticket->closed_at = null;
            }

            $ticket->save();
        });

        if (! $request->expectsJson()) {
            return redirect()->route('support.tickets')->with('success', 'Ticket status updated successfully.');
        }

        return response()->json([
            'message' => 'Status updated successfully.',
            'status' => $ticket->status,
            'resolved_at' => optional($ticket->resolved_at)->format('Y-m-d H:i'),
            'closed_at' => optional($ticket->closed_at)->format('Y-m-d H:i'),
        ]);
    }

    private function validationFailure(Request $request, $validator)
    {
        if ($request->expectsJson()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return back()->withErrors($validator)->withInput();
    }
}
