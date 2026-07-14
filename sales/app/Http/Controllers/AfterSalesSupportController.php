<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\WarrantyRecord;
use App\Models\ServiceContract;
use App\Models\ResolutionTracking;
use App\Models\SatisfactionMonitoring;
use App\Models\Notification;
use App\Models\WarrantyClaim;
use Illuminate\Http\Request;

class AfterSalesSupportController extends Controller
{
    public function index(Request $request)
    {
        $recentTickets = SupportTicket::query()
            ->with(['customer', 'product'])
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        $recentWarrantyRecords = WarrantyRecord::query()
            ->with(['product', 'order', 'order.customer'])
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        $recentServiceRequests = ServiceContract::query()
            ->with(['customer', 'product'])
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        $recentResolutionTrackings = ResolutionTracking::query()
            ->with(['supportTicket', 'employee'])
            ->orderByDesc('resolved_at')
            ->take(3)
            ->get();

        $satisfactionQuery = SatisfactionMonitoring::query();
        $avgRating = (float) $satisfactionQuery->avg('rating');
        $totalSatisfaction = (int) $satisfactionQuery->count();
        $fiveStarCount = (int) $satisfactionQuery->where('rating', 5)->count();
        $fiveStarPct = $totalSatisfaction > 0 ? round(($fiveStarCount / $totalSatisfaction) * 100, 0) : 0;

        $minRatingCount = (int) $satisfactionQuery->whereIn('rating', [1, 2])->count();
        $minRatingPct = $totalSatisfaction > 0 ? round(($minRatingCount / $totalSatisfaction) * 100, 0) : 0;

        $ticketCount = (int) SupportTicket::query()->count();
        $activeWarrantyCount = (int) WarrantyRecord::query()->whereRaw('lower(warranty_status) = ?', ['active'])->count();
        $resolvedCaseCount = (int) SupportTicket::query()->whereRaw('lower(status) = ?', ['resolved'])->count();

        $notificationsCount = class_exists(\App\Models\Notification::class)
            ? (int) Notification::query()->count()
            : 0;

        $recentSatisfaction = SatisfactionMonitoring::query()
            ->with(['supportTicket'])
            ->orderByDesc('submitted_at')
            ->take(4)
            ->get();

        return view('support.index', [
            'ticketCount' => $ticketCount,
            'activeWarrantyCount' => $activeWarrantyCount,
            'satisfactionAvg' => $totalSatisfaction > 0 ? round($avgRating, 1) : 0,
            'fiveStarPct' => $fiveStarPct,
            'minRatingPct' => $minRatingPct,
            'notificationsCount' => $notificationsCount,
            'resolvedCaseCount' => $resolvedCaseCount,
            'totalSatisfaction' => $totalSatisfaction,
            'recentTickets' => $recentTickets,
            'recentWarrantyRecords' => $recentWarrantyRecords,
            'recentServiceRequests' => $recentServiceRequests,
            'recentResolutionTrackings' => $recentResolutionTrackings,
            'recentSatisfaction' => $recentSatisfaction,
        ]);

    }

    public function ticketsIndex(Request $request)

    {
        $search = $request->query('search');
        $status = $request->query('status');
        $priority = $request->query('priority');

        $perPage = (int) $request->query('per_page', 10);
        $perPage = $perPage > 0 ? $perPage : 10;

        $query = SupportTicket::query()
            ->with(['customer', 'product', 'ticketAssignments.employee']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_type', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('priority', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->where('customer_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('product', function ($q3) use ($search) {
                        $q3->where('product_name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    });
            });
        }

        if (!empty($status) && strtolower($status) !== 'all') {
            $query->where('status', $status);
        }

        if (!empty($priority) && strtolower($priority) !== 'all') {
            $query->where('priority', $priority);
        }

        $tickets = $query
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->appends($request->query());

        return view('support.tickets', [
            'tickets' => $tickets,
            'search' => $search,
            'status' => $status,
            'priority' => $priority,
        ]);
    }

    public function warrantyRecordsIndex(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $product = $request->query('product');

        $perPage = (int) $request->query('per_page', 10);
        $perPage = $perPage > 0 ? $perPage : 10;

        $query = \App\Models\WarrantyRecord::query()
            ->with(['product', 'order.customer']);


        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('warranty_number', 'like', "%{$search}%")
                    ->orWhere('warranty_status', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q2) use ($search) {
                        $q2->where('product_name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    })
                    ->orWhereHas('order', function ($q3) use ($search) {
                        $q3->where('order_number', 'like', "%{$search}%")
                            ->orWhereHas('customer', function ($q4) use ($search) {
                                $q4->where('customer_name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if (!empty($status) && strtolower($status) !== 'all') {
            $query->where('warranty_status', $status);
        }

        if (!empty($product) && strtolower($product) !== 'all') {
            $query->whereHas('product', function ($q) use ($product) {
                $q->where('product_name', $product);
            });
        }

        $warrantyRecords = $query
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->appends($request->query());

        return view('support.warranty-records', [
            'warrantyRecords' => $warrantyRecords,
            'search' => $search,
            'status' => $status,
            'product' => $product,
        ]);
    }


    public function warrantyClaimsIndex(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $product = $request->query('product'); // not used by current UI, but safe if later added
        $customer = $request->query('customer');

        // Summary card statistics (DB computed; Blade must not query)
        $pendingClaims = (int) WarrantyClaim::query()->whereRaw('lower(claim_status) = ?', ['pending'])->count();
        $approvedClaims = (int) WarrantyClaim::query()->whereRaw('lower(claim_status) = ?', ['approved'])->count();
        $rejectedClaims = (int) WarrantyClaim::query()->whereRaw('lower(claim_status) = ?', ['rejected'])->count();
        $completedClaims = (int) WarrantyClaim::query()->whereRaw('lower(claim_status) = ?', ['completed'])->count();

        $perPage = (int) $request->query('per_page', 10);
        $perPage = $perPage > 0 ? $perPage : 10;

        $query = \App\Models\WarrantyClaim::query()
            ->with([
                'warrantyRecord.product',
                'warrantyRecord.order.customer',
                'supportTicket.customer',
                'supportTicket.product',
            ]);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('claim_id', 'like', "%{$search}%")
                    ->orWhere('claim_reason', 'like', "%{$search}%")
                    ->orWhere('claim_status', 'like', "%{$search}%")
                    ->orWhereHas('warrantyRecord', function ($q2) use ($search) {
                        $q2->where('warranty_number', 'like', "%{$search}%")
                            ->orWhereHas('product', function ($q3) use ($search) {
                                $q3->where('product_name', 'like', "%{$search}%")
                                    ->orWhere('sku', 'like', "%{$search}%");
                            });
                    })
                    ->orWhereHas('supportTicket', function ($q4) use ($search) {
                        $q4->where('subject', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%")
                            ->orWhereHas('customer', function ($q5) use ($search) {
                                $q5->where('customer_name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if (!empty($status) && strtolower($status) !== 'all') {
            $query->where('claim_status', $status);
        }

        if (!empty($customer) && strtolower($customer) !== 'all') {
            $query->whereHas('warrantyRecord.order.customer', function ($q) use ($customer) {
                $q->where('customer_name', $customer);
            });
        }

        $warrantyClaims = $query
            ->orderByDesc('claim_date')
            ->paginate($perPage)
            ->appends($request->query());

        return view('support.warranty-claims', [
            'warrantyClaims' => $warrantyClaims,
            'search' => $search,
            'status' => $status,
            'customer' => $customer,
            'pendingClaims' => $pendingClaims,
            'approvedClaims' => $approvedClaims,
            'rejectedClaims' => $rejectedClaims,
            'completedClaims' => $completedClaims,
        ]);
    }

    public function serviceContractsIndex(Request $request)

    {
        $search = $request->query('search');
        $status = $request->query('status');
        $customer = $request->query('customer');

        // Summary card statistics
        $activeCoverageCount = (int) ServiceContract::query()->whereRaw('lower(contract_status) = ?', ['active'])->count();
        $expiringSoonCount = (int) ServiceContract::query()->whereRaw('lower(contract_status) = ?', ['expiring'])->count();
        $expiredCount = (int) ServiceContract::query()->whereRaw('lower(contract_status) = ?', ['expired'])->count();

        $totalContracts = (int) ServiceContract::query()->count();
        $coverageVerificationRatePct = $totalContracts > 0
            ? round(($activeCoverageCount / $totalContracts) * 100, 0)
            : 0;

        $perPage = (int) $request->query('per_page', 10);
        $perPage = $perPage > 0 ? $perPage : 10;

        $query = \App\Models\ServiceContract::query()
            ->with(['customer', 'product']);


        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                    ->orWhere('service_type', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q2) use ($search) {
                        $q2->where('product_name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    })
                    ->orWhereHas('customer', function ($q3) use ($search) {
                        $q3->where('customer_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if (!empty($status) && strtolower($status) !== 'all') {
            $query->where('contract_status', $status);
        }

        if (!empty($customer) && strtolower($customer) !== 'all') {
            $query->whereHas('customer', function ($q) use ($customer) {
                $q->where('customer_name', $customer);
            });
        }

        $serviceContracts = $query
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->appends($request->query());

        return view('support.service-contracts', [
            'serviceContracts' => $serviceContracts,
            'search' => $search,
            'status' => $status,
            'customer' => $customer,
            'activeCoverageCount' => $activeCoverageCount,
            'expiringSoonCount' => $expiringSoonCount,
            'expiredCount' => $expiredCount,
            'coverageVerificationRatePct' => $coverageVerificationRatePct,
        ]);
    }


    public function serviceRequestsIndex(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) $request->query('per_page', 10);
        $perPage = $perPage > 0 ? $perPage : 10;

        $query = \App\Models\ServiceRequest::query()
            ->with([
                'supportTicket.customer',
                'supportTicket.product',
            ]);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('request_id', 'like', "%{$search}%")
                    ->orWhere('request_type', 'like', "%{$search}%")
                    ->orWhere('service_status', 'like', "%{$search}%")
                    ->orWhereHas('supportTicket', function ($q2) use ($search) {
                        $q2->where('subject', 'like', "%{$search}%")
                            ->orWhereHas('customer', function ($q3) use ($search) {
                                $q3->where('customer_name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if (!empty($status) && strtolower($status) !== 'all') {
            $query->where('service_status', $status);
        }

        $serviceRequests = $query
            ->orderByDesc('scheduled_date')
            ->paginate($perPage)
            ->appends($request->query());

        // Summary card statistics
        $pendingServiceRequestsCount = (int) \App\Models\ServiceRequest::query()->whereRaw('lower(service_status) = ?', ['pending'])->count();
        $scheduledServiceRequestsCount = (int) \App\Models\ServiceRequest::query()->whereRaw('lower(service_status) = ?', ['scheduled'])->count();
        $inProgressServiceRequestsCount = (int) \App\Models\ServiceRequest::query()->whereRaw('lower(service_status) = ?', ['in progress'])->count();
        $completedServiceRequestsCount = (int) \App\Models\ServiceRequest::query()->whereRaw('lower(service_status) = ?', ['completed'])->count();

        return view('support.service-requests', [
            'serviceRequests' => $serviceRequests,
            'search' => $search,
            'status' => $status,
            'pendingServiceRequestsCount' => $pendingServiceRequestsCount,
            'scheduledServiceRequestsCount' => $scheduledServiceRequestsCount,
            'inProgressServiceRequestsCount' => $inProgressServiceRequestsCount,
            'completedServiceRequestsCount' => $completedServiceRequestsCount,
        ]);
    }

    public function resolutionTrackingIndex(Request $request)

    {
        $search = $request->query('search');
        $status = $request->query('status');

        $perPage = (int) $request->query('per_page', 10);
        $perPage = $perPage > 0 ? $perPage : 10;

        $query = \App\Models\ResolutionTracking::query()
            ->with(['supportTicket', 'employee']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('resolution_summary', 'like', "%{$search}%")
                    ->orWhere('root_cause', 'like', "%{$search}%")
                    ->orWhere('corrective_action', 'like', "%{$search}%")
                    ->orWhereHas('supportTicket', function ($q2) use ($search) {
                        $q2->where('ticket_type', 'like', "%{$search}%")
                            ->orWhere('subject', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%");
                    });
            });
        }

        // No dedicated status column in schema; map to support_ticket.status when provided.
        if (!empty($status) && strtolower($status) !== 'all') {
            $query->whereHas('supportTicket', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        $resolutionTrackings = $query
            ->orderByDesc('resolved_at')
            ->paginate($perPage)
            ->appends($request->query());

        // Summary cards statistics
        $resolvedThisMonthCount = (int) ResolutionTracking::query()
            ->whereMonth('resolved_at', now()->month)
            ->whereYear('resolved_at', now()->year)
            ->count();

        $qcPassedCount = (int) ResolutionTracking::query()->where('corrective_action', 'like', '%pass%')->count();
        $qcTotalCount = (int) ResolutionTracking::query()->count();
        $qcPassedPct = $qcTotalCount > 0
            ? round(($qcPassedCount / $qcTotalCount) * 100, 0)
            : 0;

        $pendingQcCount = (int) ResolutionTracking::query()->where('corrective_action', 'like', '%pending%')->count();

        // Reopened cases are derived from support ticket status
        $reopenedCasesCount = (int) \App\Models\SupportTicket::query()
            ->whereRaw('lower(status) = ?', ['reopened'])
            ->count();

        return view('support.resolution-tracking', [
            'resolutionTrackings' => $resolutionTrackings,
            'search' => $search,
            'status' => $status,
            'resolvedThisMonthCount' => $resolvedThisMonthCount,
            'qcPassedPct' => $qcPassedPct,
            'pendingQcCount' => $pendingQcCount,
            'reopenedCasesCount' => $reopenedCasesCount,
        ]);
    }


    public function customerSatisfactionIndex(Request $request)
    {
        $search = $request->query('search');
        $rating = $request->query('rating');

        $perPage = (int) $request->query('per_page', 10);
        $perPage = $perPage > 0 ? $perPage : 10;

        $query = \App\Models\SatisfactionMonitoring::query()
            ->with(['supportTicket']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('comments', 'like', "%{$search}%")
                    ->orWhereHas('supportTicket', function ($q2) use ($search) {
                        $q2->where('ticket_type', 'like', "%{$search}%")
                            ->orWhere('subject', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%");
                    });
            });
        }

        if ($rating !== null && $rating !== '' && strtolower((string) $rating) !== 'all') {
            $query->where('rating', (int) $rating);
        }

        $satisfaction = $query
            ->orderByDesc('submitted_at')
            ->paginate($perPage)
            ->appends($request->query());

        // Summary card statistics (DB computed; Blade must not query)
        $allForStats = (clone $query)->get(['rating']);

        $totalCount = (int) $allForStats->count();
        $avg = (float) $allForStats->whereNotNull('rating')->avg('rating');

        $fiveStarCount = (int) $allForStats->where('rating', 5)->count();
        $fiveStarPct = $totalCount > 0 ? round(($fiveStarCount / $totalCount) * 100, 0) : 0;

        $lowestRatingsCount = (int) $allForStats->whereIn('rating', [1, 2])->count();
        $lowestRatingsPct = $totalCount > 0 ? round(($lowestRatingsCount / $totalCount) * 100, 0) : 0;

        // Pass values expected by the summary cards
        $averageRating = $totalCount > 0 ? round($avg, 1) : 0;
        $responsesCount = $totalCount;

        return view('support.customer-satisfaction', [
            'satisfactions' => $satisfaction,
            'search' => $search,
            'rating' => $rating,
            'averageRating' => $averageRating,
            'fiveStarPct' => (int) $fiveStarPct,
            'lowestRatingsPct' => (int) $lowestRatingsPct,
            'responsesCount' => $responsesCount,
        ]);
    }

}


