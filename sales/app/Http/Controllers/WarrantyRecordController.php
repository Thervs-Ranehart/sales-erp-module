<?php

namespace App\Http\Controllers;

use App\Models\WarrantyRecord;
use Illuminate\Http\Request;

class WarrantyRecordController extends Controller
{
    /**
     * Return a warranty record with relations for AJAX modal population.
     */
    public function show(Request $request, $warrantyId)
    {
        $warranty = WarrantyRecord::with(['product', 'order', 'customer'])
            ->withCount('warrantyClaims')
            ->find($warrantyId);

        if (! $warranty) {
            return response()->json(['message' => 'Warranty record not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'warranty' => [
                'warranty_id' => $warranty->warranty_id,
                'warranty_number' => $warranty->warranty_number,
                'warranty_status' => $warranty->currentStatus(),

                'warranty_start' => optional($warranty->warranty_start)->format('Y-m-d'),
                'warranty_end' => optional($warranty->warranty_end)->format('Y-m-d'),
                'created_at' => optional($warranty->created_at)->format('Y-m-d H:i'),
                'claim_count' => $warranty->warranty_claims_count,
                'product' => [
                    'product_name' => optional($warranty->product)->product_name,
                ],
                'customer' => [
                    'name' => optional($warranty->customer)->full_name,
                    'email' => optional($warranty->customer)->email,
                ],
                'order' => [
                    'order_number' => optional($warranty->order)->order_number,
                    'order_date' => optional($warranty->order?->order_date)->format('Y-m-d'),
                ],
            ],
        ]);
    }
}
