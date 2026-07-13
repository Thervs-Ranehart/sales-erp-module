<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\CustomerSegment;
use Illuminate\Http\Request;

class CustomerSegmentationController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $segment = $request->query('segment');
        $frequency = $request->query('frequency');

        $query = CustomerSegment::query()->with(['customer']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('segment_name', 'like', "%{$search}%")
                    ->orWhere('spending_category', 'like', "%{$search}%")
                    ->orWhere('purchase_frequency', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($segment && $segment !== 'All Segments') {
            $query->where('segment_name', $segment);
        }

        if ($frequency) {
            $query->where('purchase_frequency', $frequency);
        }

        $records = $query->orderByDesc('last_updated')->paginate(10)->withQueryString();

        return view('crm.customer-segmentation', [
            'segments' => $records,
            'search' => $search,
            'segment' => $segment,
            'frequency' => $frequency,
            'highValueCount' => CustomerSegment::where('segment_name', 'High-Value')->count(),
            'regularCount' => CustomerSegment::where('segment_name', 'Regular')->count(),
            'atRiskCount' => CustomerSegment::where('segment_name', 'At-Risk')->count(),
        ]);
    }
}
