<?php

namespace App\Http\Controllers;

use App\Models\ResolutionTracking;
use App\Models\SatisfactionMonitoring;
use App\Models\ServiceContract;
use App\Models\SupportTicket;
use App\Models\WarrantyRecord;
use App\Models\Notification;


class SupportTicketController extends Controller
{
    public function index()
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
        $totalSatisfaction = (int) $satisfactionQuery->count();
        $avgRating = (float) $satisfactionQuery->avg('rating');
        $fiveStarCount = (int) $satisfactionQuery->where('rating', 5)->count();
        $fiveStarPct = $totalSatisfaction > 0 ? round(($fiveStarCount / $totalSatisfaction) * 100, 0) : 0;
        $minRatingCount = (int) $satisfactionQuery->whereIn('rating', [1, 2])->count();
        $minRatingPct = $totalSatisfaction > 0 ? round(($minRatingCount / $totalSatisfaction) * 100, 0) : 0;

        $openTicketsCount = (int) SupportTicket::query()->whereRaw('lower(status) = ?', ['pending'])->count();
        $pendingTicketsCount = (int) SupportTicket::query()->whereRaw('lower(status) = ?', ['pending'])->count();

        $activeWarrantyCount = (int) WarrantyRecord::query()->whereRaw('lower(warranty_status) = ?', ['active'])->count();
        $resolvedCaseCount = (int) SupportTicket::query()->whereRaw('lower(status) = ?', ['resolved'])->count();


        $recentNotifications = Notification::query()
            ->orderByDesc('created_at')
            ->with([])
            ->take(4)
            ->get();

        $notificationsCount = (int) (clone $recentNotifications)->count();


        $recentSatisfaction = SatisfactionMonitoring::query()
            ->with(['supportTicket'])
            ->orderByDesc('submitted_at')
            ->take(4)
            ->get();


        // Customer Satisfaction KPI: CSAT percentage
        $customerSatisfactionPct = $fiveStarPct;

        return view('support.index', [
            'ticketCount' => $openTicketsCount,
            'openTicketsCount' => $openTicketsCount,
            'pendingTicketsCount' => $pendingTicketsCount,
            'activeWarrantyCount' => $activeWarrantyCount,
            'satisfactionAvg' => $totalSatisfaction > 0 ? round($avgRating, 1) : 0,
            'fiveStarPct' => $fiveStarPct,
            'minRatingPct' => $minRatingPct,
            'customerSatisfactionPct' => $customerSatisfactionPct,
            'notificationsCount' => $notificationsCount,
            'resolvedCaseCount' => $resolvedCaseCount,
            'totalSatisfaction' => $totalSatisfaction,
            'recentTickets' => $recentTickets,
            'recentWarrantyRecords' => $recentWarrantyRecords,
            'recentServiceRequests' => $recentServiceRequests,
            'recentResolutionTrackings' => $recentResolutionTrackings,
            'recentSatisfaction' => $recentSatisfaction,
            'recentNotifications' => $recentNotifications,
        ]);

    }
}

