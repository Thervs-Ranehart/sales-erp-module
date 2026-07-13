<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CommunicationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerFollowUpsController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status');

        $baseQuery = CommunicationLog::query()->with(['customer'])
            ->whereNotNull('follow_up_date');

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($status) {
            $baseQuery->where('communication_status', $status);
        }

        $followUps = (clone $baseQuery)->orderBy('follow_up_date')->paginate(10)->withQueryString();

        $today = now()->toDateString();
        $todayCount = (clone $baseQuery)->whereDate('follow_up_date', $today)->count();
        $pendingCount = (clone $baseQuery)->where('communication_status', 'Pending')->count();
        $overdueCount = (clone $baseQuery)->whereDate('follow_up_date', '<', now()->toDateString())
            ->where('communication_status', '!=', 'Completed')
            ->count();
        $completedCount = (clone $baseQuery)->where('communication_status', 'Completed')->count();

        return view('crm.customer-followups', [
            'followUps' => $followUps,
            'customers' => Customer::orderBy('first_name')->get(),
            'todayCount' => $todayCount,
            'pendingCount' => $pendingCount,
            'overdueCount' => $overdueCount,
            'completedCount' => $completedCount,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,customer_id'],
            'communication_channel' => ['required', 'string', 'max:100'],
            'subject' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'follow_up_date' => ['required', 'date'],
            'communication_status' => ['required', 'string', 'max:100'],
        ]);

        CommunicationLog::create([
            'customer_id' => $data['customer_id'],
            'employee_id' => $this->resolveEmployeeId(),
            'communication_date' => now(),
            'communication_channel' => $data['communication_channel'],
            'subject' => $data['subject'],
            'notes' => $data['notes'] ?? null,
            'follow_up_date' => $data['follow_up_date'],
            'communication_status' => $data['communication_status'],
        ]);

        return redirect()->route('crm.followups')->with('success', 'Follow-up created successfully.');
    }

    public function updateStatus(Request $request, CommunicationLog $log)
    {
        $data = $request->validate([
            'communication_status' => ['required', 'string', 'max:255'],
        ]);

        $log->update(['communication_status' => $data['communication_status']]);

        return back()->with('success', 'Follow-up status updated.');
    }

    public function destroy(CommunicationLog $log)
    {
        $log->delete();

        return redirect()->route('crm.followups')->with('success', 'Follow-up deleted.');
    }

    private function resolveEmployeeId(): int
    {
        $employeeId = DB::table('employees')->min('employee_id');

        if ($employeeId) {
            return (int) $employeeId;
        }

        return (int) DB::table('employees')->insertGetId([
            'username' => 'crm.system',
            'password_hash' => bcrypt('password'),
            'first_name' => 'CRM',
            'last_name' => 'System',
            'department' => 'CRM',
            'role' => 'Staff',
            'employee_status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
