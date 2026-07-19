<?php

namespace App\Http\Controllers;

use App\Models\WarrantyClaim;
use Illuminate\Http\Request;
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
                'warrantyRecord.order.customer',
                'supportTicket.customer',
                'supportTicket.product',
            ])
            ->findOrFail($claimId);

        $assignedStaff = $claim->supportTicket
            ? $claim->supportTicket->ticketAssignments->first()?->employee
            : null;

        return response()->json([
            'claim' => [
                'claim_id' => $claim->claim_id,
                'warranty_id' => $claim->warranty_id,
                'ticket_id' => $claim->ticket_id,
                'claim_reason' => $claim->claim_reason,
                'claim_status' => $claim->claim_status,
                'claim_date' => optional($claim->claim_date)->format('Y-m-d H:i'),
                'approved_date' => optional($claim->approved_date)->format('Y-m-d H:i'),
            ],
            'warranty' => [
                'warranty_number' => optional($claim->warrantyRecord)->warranty_number,
                'warranty_status' => optional($claim->warrantyRecord)->warranty_status,
                'warranty_start' => optional($claim->warrantyRecord->warranty_start)->format('Y-m-d'),
                'warranty_end' => optional($claim->warrantyRecord->warranty_end)->format('Y-m-d'),
                'product' => [
                    'product_name' => optional(optional($claim->warrantyRecord)->product)->product_name,
                    'sku' => optional(optional($claim->warrantyRecord)->product)->sku,
                ],
                'customer' => [
                    'customer_name' => optional(optional(optional($claim->warrantyRecord)->order)->customer)->customer_name,
                    'email' => optional(optional(optional($claim->warrantyRecord)->order)->customer)->email,
                ],
            ],
            'ticket' => [
                'subject' => optional($claim->supportTicket)->subject,
                'status' => optional($claim->supportTicket)->status,
                'priority' => optional($claim->supportTicket)->priority,
            ],
            'assignedEmployee' => [
                'employee_id' => optional($assignedStaff)->employee_id,
                'employee_name' => optional($assignedStaff)->employee_name,
            ],
            'availableStatuses' => ['Pending', 'Approved', 'Rejected', 'Completed'],
        ]);
    }

    public function updateStatus(Request $request, $claimId)
    {
        $validator = Validator::make($request->all(), [
            'claim_status' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $claim = WarrantyClaim::query()->findOrFail($claimId);
        $claim->claim_status = $request->input('claim_status');
        if (strtolower((string) $claim->claim_status) === 'approved' && !$claim->approved_date) {
            $claim->approved_date = now();
        }

        $claim->save();

        return response()->json([
            'message' => 'Claim status updated successfully.',
            'status' => $claim->claim_status,
            'claim_id' => $claim->claim_id,
        ]);
    }
}

