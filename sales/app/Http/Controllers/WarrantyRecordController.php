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
        $warranty = WarrantyRecord::with(['product', 'order.customer'])
            ->findOrFail($warrantyId);

        return response()->json([
            'warranty' => [
                'warranty_id' => $warranty->warranty_id,
                'warranty_number' => $warranty->warranty_number,
                'warranty_status' => $warranty->warranty_status,
                'coverage_type' => $warranty->coverage_type,

                'warranty_start' => optional($warranty->warranty_start)->format('Y-m-d'),
                'warranty_end' => optional($warranty->warranty_end)->format('Y-m-d'),
                'product' => [
                    'product_name' => optional($warranty->product)->product_name,
                    'sku' => optional($warranty->product)->sku,
                ],
                'customer' => [
                    'customer_name' => optional(optional($warranty->order)->customer)->customer_name,
                    'email' => optional(optional($warranty->order)->customer)->email,
                ],
                'order' => [
                    'order_number' => optional($warranty->order)->order_number,
                ],
            ],
        ]);
    }
}

