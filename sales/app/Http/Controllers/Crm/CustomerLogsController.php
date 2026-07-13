<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CommunicationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerLogsController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $channel = $request->query('channel');
        $status = $request->query('status');

        $query = CommunicationLog::query()->with(['customer']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhere('communication_id', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($channel) {
            $query->where('communication_channel', $channel);
        }

        if ($status) {
            $query->where('communication_status', $status);
        }

        $logs = $query->orderByDesc('communication_date')->paginate(10)->withQueryString();

        $totalConversations = CommunicationLog::count();
        $resolved = CommunicationLog::where('communication_status', 'Resolved')->count();
        $pendingFollowUps = CommunicationLog::whereNotNull('follow_up_date')->count();
        $responseRate = $totalConversations > 0 ? round(($resolved / $totalConversations) * 100) : 0;

        return view('crm.customer-logs', [
            'logs' => $logs,
            'customers' => Customer::orderBy('first_name')->get(),
            'totalConversations' => $totalConversations,
            'pendingFollowUps' => $pendingFollowUps,
            'resolved' => $resolved,
            'responseRate' => $responseRate,
            'search' => $search,
            'channel' => $channel,
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
            'communication_status' => ['required', 'string', 'max:100'],
            'follow_up_date' => ['nullable', 'date'],
        ]);

        CommunicationLog::create([
            'customer_id' => $data['customer_id'],
            'employee_id' => $this->resolveEmployeeId(),
            'communication_date' => now(),
            'communication_channel' => $data['communication_channel'],
            'subject' => $data['subject'],
            'notes' => $data['notes'] ?? null,
            'follow_up_date' => $data['follow_up_date'] ?? null,
            'communication_status' => $data['communication_status'],
        ]);

        return redirect()->route('crm.logs')->with('success', 'Communication log added successfully.');
    }

    public function updateStatus(Request $request, CommunicationLog $log)
    {
        $data = $request->validate([
            'communication_status' => ['required', 'string', 'max:255'],
        ]);

        $log->update(['communication_status' => $data['communication_status']]);

        return back()->with('success', 'Log status updated successfully.');
    }

    public function destroy(CommunicationLog $log)
    {
        $log->delete();

        return redirect()->route('crm.logs')->with('success', 'Communication log deleted.');
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
