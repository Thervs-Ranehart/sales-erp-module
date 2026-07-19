<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SupportTicket;
use App\Models\TicketAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupportTicketController extends Controller
{
    public function show(Request $request, $ticketId)
    {
        $ticket = SupportTicket::with([
            'customer',
            'product',
            'ticketAssignments.employee',
        ])->findOrFail($ticketId);

        // Ensure we always return the latest assignment (newest assigned_at)
        // so reassignment doesn't show a previously assigned employee after reload.
        $latestAssignment = $ticket->ticketAssignments
            ->sortByDesc('assigned_at')
            ->first();
        $assignedEmployee = optional($latestAssignment)->employee;


        return response()->json([
            'ticket' => [
                'ticket_id' => $ticket->ticket_id,
                'ticket_type' => $ticket->ticket_type,
                'subject' => $ticket->subject,
                'description' => $ticket->description,
                'priority' => $ticket->priority,
                'status' => $ticket->status,
                'due_date' => optional($ticket->due_date)->format('Y-m-d H:i'),
                'customer' => [
                    'customer_name' => optional($ticket->customer)->customer_name,
                    'email' => optional($ticket->customer)->email,
                ],
                'product' => [
                    'product_name' => optional($ticket->product)->product_name,
                    'sku' => optional($ticket->product)->sku,
                ],
                'order_id' => $ticket->order_id,
            ],
            'assignedEmployee' => [
'employee_id' => optional($assignedEmployee)->employee_id,
                'employee_name' => optional($assignedEmployee)->getFullNameAttribute(),
            ],
        ]);
    }

    public function assignForm(Request $request, $ticketId)
    {
        $ticket = SupportTicket::with(['ticketAssignments.employee'])->findOrFail($ticketId);

        $employees = Employee::query()
            ->orderBy('first_name')
            ->get(['employee_id', 'first_name', 'last_name']);

        // Use latest assignment to pre-select the currently assigned employee.
        $latestAssignment = $ticket->ticketAssignments
            ->sortByDesc('assigned_at')
            ->first();
        $currentEmployee = optional($latestAssignment)->employee;


        return response()->json([
            'ticket' => [
                'ticket_id' => $ticket->ticket_id,
                'priority' => $ticket->priority,
                'status' => $ticket->status,
                'due_date' => optional($ticket->due_date)->format('Y-m-d'),
            ],
            'employees' => $employees->map(function (Employee $e) {
                return [
                    'employee_id' => $e->employee_id,
                    'employee_name' => $e->getFullNameAttribute(),
                ];
            })->values(),
            'currentEmployeeId' => optional($currentEmployee)->employee_id,
        ]);
    }

    public function assign(Request $request, $ticketId)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => ['required', 'integer', 'exists:employees,employee_id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        TicketAssignment::create([
            'ticket_id' => (int) $ticketId,
            'employee_id' => (int) $request->input('employee_id'),
            'assigned_at' => now(),
            'assignment_status' => 'assigned',
        ]);

        $employee = Employee::find($request->input('employee_id'));

        return response()->json([
            'message' => 'Ticket assigned successfully.',
            'assignedEmployee' => [
                'employee_id' => $employee?->employee_id,
                'employee_name' => $employee?->getFullNameAttribute(),
            ],
        ]);
    }

    public function updateStatus(Request $request, $ticketId)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $ticket = SupportTicket::query()->findOrFail($ticketId);
        $ticket->status = $request->input('status');
        $ticket->save();

        return response()->json([
            'message' => 'Status updated successfully.',
            'status' => $ticket->status,
        ]);
    }
}



