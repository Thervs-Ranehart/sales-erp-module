<?php

namespace App\Http\Controllers;

use App\Models\WarrantyClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WarrantyClaimController extends Controller
{
    /**
     * Return a warranty claim with relations for AJAX modal population.
     */
    public function show(Request $request, $claimId)
    {
        $claim = WarrantyClaim::query()
            ->with([
                'warrantyRecord.product',
                'warrantyRecord.customer',
                'supportTicket.customer',
                'supportTicket.product',
                'supportTicket.latestAssignment.employee',
            ])
            ->findOrFail($claimId);

        $assignedStaff = $claim->supportTicket?->latestAssignment?->employee;

        return response()->json([
            'claim' => [
                'claim_id' => $claim->claim_id,
                'warranty_id' => $claim->warranty_id,
                'ticket_id' => $claim->ticket_id,
                'ticket_number' => $claim->ticket_id ? 'TK-'.$claim->ticket_id : null,
                'claim_reason' => $claim->claim_reason,
                'claim_status' => $claim->claim_status,
                'claim_date' => optional($claim->claim_date)->format('Y-m-d H:i'),
                'approved_date' => optional($claim->approved_date)->format('Y-m-d H:i'),
            ],
            'warranty' => [
                'warranty_number' => optional($claim->warrantyRecord)->warranty_number,
                'warranty_status' => optional($claim->warrantyRecord)->warranty_status,
                'warranty_start' => $claim->warrantyRecord?->warranty_start?->format('Y-m-d'),
                'warranty_end' => $claim->warrantyRecord?->warranty_end?->format('Y-m-d'),
                'product' => [
                    'product_name' => optional(optional($claim->warrantyRecord)->product)->product_name,
                ],
                'customer' => [
                    'name' => optional($claim->warrantyRecord?->customer)->full_name,
                    'email' => optional($claim->warrantyRecord?->customer)->email,
                ],
            ],
            'ticket' => [
                'ticket_id' => optional($claim->supportTicket)->ticket_id,
                'subject' => optional($claim->supportTicket)->subject,
                'status' => optional($claim->supportTicket)->status,
                'priority' => optional($claim->supportTicket)->priority,
            ],
            'assignedEmployee' => [
                'employee_id' => optional($assignedStaff)->employee_id,
                'name' => optional($assignedStaff)->full_name,
                'department' => optional($assignedStaff)->department,
            ],
            'availableStatuses' => ['Pending', 'Approved', 'Rejected', 'Completed'],
        ]);
    }

    public function updateStatus(Request $request, $claimId)
    {
        $validator = Validator::make($request->all(), [
            'claim_status' => ['required', 'in:Pending,Approved,Rejected,Completed'],
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        $claim = WarrantyClaim::query()->findOrFail($claimId);
        $status = $request->string('claim_status')->toString();

        DB::transaction(function () use ($claim, $status): void {
            $claim->claim_status = $status;

            // Preserve the first approval timestamp as an audit record even if the claim is later rejected or completed.
            if ($status === 'Approved' && $claim->approved_date === null) {
                $claim->approved_date = now();
            }

            $claim->save();
        });

        if (! $request->expectsJson()) {
            return redirect()->route('support.warranty-claims')->with('success', 'Claim status updated successfully.');
        }

        return response()->json([
            'message' => 'Claim status updated successfully.',
            'status' => $claim->claim_status,
            'claim_id' => $claim->claim_id,
            'approved_date' => optional($claim->approved_date)->format('Y-m-d H:i'),
        ]);
    }
}
