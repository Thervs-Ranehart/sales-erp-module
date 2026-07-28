<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $employeeId = (int) $request->session()->get('employee_id', 0);
        $search = trim((string) $request->query('search', ''));

        $notifications = Notification::query()
            ->where('employee_id', $employeeId)
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($notificationQuery) use ($search): void {
                    $notificationQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%")
                        ->orWhere('notification_type', 'like', "%{$search}%");
                });
            })
            ->latest('created_at')
            ->get();

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $notifications->where('is_read', false)->count(),
            'readCount' => $notifications->where('is_read', true)->count(),
            'search' => $search,
        ]);
    }

    public function markRead(Request $request, Notification $notification): RedirectResponse
    {
        abort_unless($notification->employee_id === (int) $request->session()->get('employee_id', 0), 404);

        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        Notification::query()
            ->where('employee_id', (int) $request->session()->get('employee_id', 0))
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
