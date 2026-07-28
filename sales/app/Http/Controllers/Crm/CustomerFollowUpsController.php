<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\CommunicationLog;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CustomerFollowUpsController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status');

        $baseQuery = CommunicationLog::with([
            'customer',
            'agent',
        ])
            ->whereNotNull('follow_up_date');

        if ($search !== '') {

            $baseQuery->where(function ($q) use ($search) {

                $q->where('subject', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('agent', function ($aq) use ($search) {
                        $aq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });

            });
        }

        if ($status) {
            $baseQuery->where(
                'communication_status',
                $status
            );
        }

        $followUps = $baseQuery
            ->orderBy('follow_up_date')
            ->paginate(10)
            ->withQueryString();

        // Follow-Up Analytics: date-range filtered counts using
        // follow_up_date / communication_status, driven off real data.
        $analyticsPeriod = $request->query('analytics_period', 'this_week');
        $analyticsStart = $request->query('analytics_start');
        $analyticsEnd = $request->query('analytics_end');

        [$analyticsRangeStart, $analyticsRangeEnd] = $this->resolveAnalyticsRange(
            $analyticsPeriod,
            $analyticsStart,
            $analyticsEnd
        );

        $analyticsBase = CommunicationLog::whereNotNull('follow_up_date')
            ->whereBetween('follow_up_date', [$analyticsRangeStart, $analyticsRangeEnd]);

        $analyticsTotal = (clone $analyticsBase)->count();

        $analyticsCompleted = (clone $analyticsBase)
            ->where('communication_status', 'Completed')
            ->count();

        $analyticsPending = (clone $analyticsBase)
            ->where('communication_status', 'Pending')
            ->count();

        $analyticsOverdue = (clone $analyticsBase)
            ->where('communication_status', '!=', 'Completed')
            ->where('follow_up_date', '<', now())
            ->count();

        return view('crm.customer-followups', [

            'followUps' => $followUps,

            'customers' => Customer::orderBy('first_name')->get(),

            // Agents table used for follow-up assignment
            'agents' => Agent::where(
                'status',
                'Active'
            )
                ->orderBy('first_name')
                ->get(),

            'todayCount' => CommunicationLog::whereDate(
                'follow_up_date',
                now()->toDateString()
            )->count(),

            'pendingCount' => CommunicationLog::where(
                'communication_status',
                'Pending'
            )->count(),

            'overdueCount' => CommunicationLog::whereDate(
                'follow_up_date',
                '<',
                now()->toDateString()
            )
                ->where(
                    'communication_status',
                    '!=',
                    'Completed'
                )
                ->count(),

            'openHighPriorityCount' => CommunicationLog::where('priority', 'High')
                ->where('communication_status', '!=', 'Completed')
                ->count(),

            'completedCount' => CommunicationLog::where(
                'communication_status',
                'Completed'
            )->count(),

            'search' => $search,

            'status' => $status,

            // Assigned Agents: available agents for each visible follow-up,
            // ranked by the follow-up's own priority column (see method docblock).
            'agentAssignmentOptions' => $this->buildAgentAssignmentOptions(
                $followUps->getCollection()
            ),

            'analyticsPeriod' => $analyticsPeriod,
            'analyticsStart' => $analyticsStart,
            'analyticsEnd' => $analyticsEnd,
            'analyticsRangeStart' => $analyticsRangeStart,
            'analyticsRangeEnd' => $analyticsRangeEnd,
            'analyticsTotal' => $analyticsTotal,
            'analyticsCompleted' => $analyticsCompleted,
            'analyticsPending' => $analyticsPending,
            'analyticsOverdue' => $analyticsOverdue,

        ]);
    }

    public function store(Request $request)
    {

        $data = $request->validate([

            'customer_id' => [
                'required',
                'exists:customers,customer_id',
            ],

            'employee_id' => [
                'required',
                'exists:employees,employee_id',
            ],

            'agent_id' => [
                'nullable',
                'exists:agents,agent_id',
            ],

            'communication_channel' => [
                'required',
            ],

            'subject' => [
                'required',
                'string',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'follow_up_date' => [
                'required',
                'date',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High',
            ],

            'communication_status' => [
                'required',
                'in:Pending,Completed',
            ],

        ]);

        $agentId = $data['agent_id'] ?? null;

        if ($data['priority'] === 'High') {
            $agentId = $this->recommendedAgentId();

            if (! $agentId) {
                return back()->withErrors(['agent_id' => 'No active agent is available for this high-priority follow-up.'])->withInput();
            }
        } elseif ($agentId && ! $this->isActiveAgent($agentId)) {
            return back()->withErrors(['agent_id' => 'Selected agent is not available.'])->withInput();
        }

        CommunicationLog::create([

            'customer_id' => $data['customer_id'],

            'employee_id' => $data['employee_id'],

            // Assigned Agent
            'agent_id' => $agentId,

            'communication_date' => now(),

            'communication_channel' => $data['communication_channel'],

            'subject' => $data['subject'],

            'notes' => $data['notes'] ?? null,

            'follow_up_date' => $data['follow_up_date'],

            'priority' => $data['priority'],

            'communication_status' => $data['communication_status'],

        ]);

        return back()->with(
            'success',
            'Follow-up created successfully.'
        );
    }

    public function updateStatus(Request $request, CommunicationLog $log)
    {
        $data = $request->validate([
            'communication_status' => [
                'required',
                'in:Pending,Completed',
            ],
        ]);

        $log->update([
            'communication_status' => $data['communication_status'],
        ]);

        return back()->with(
            'success',
            'Follow-up status updated successfully.'
        );
    }

    public function update(Request $request, CommunicationLog $log)
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,customer_id'],
            'agent_id' => ['nullable', 'exists:agents,agent_id'],
            'communication_channel' => ['required', 'string', 'max:100'],
            'subject' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'follow_up_date' => ['required', 'date'],
            'priority' => ['required', 'in:Low,Medium,High'],
            'communication_status' => ['required', 'in:Pending,Completed'],
        ]);

        if ($data['priority'] === 'High') {
            $data['agent_id'] = $this->recommendedAgentId($log);

            if (! $data['agent_id']) {
                return back()->withErrors(['agent_id' => 'No active agent is available for this high-priority follow-up.']);
            }
        } elseif (($data['agent_id'] ?? null) && ! $this->isActiveAgent($data['agent_id'])) {
            return back()->withErrors(['agent_id' => 'Selected agent is not available.']);
        }

        $log->update($data);

        return back()->with('success', 'Follow-up updated successfully.');
    }

    public function destroy(CommunicationLog $log)
    {
        $log->delete();

        return back()->with('success', 'Follow-up deleted successfully.');
    }

    /**
     * Assign (or reassign) an available agent to a follow-up.
     */
    public function assignAgent(Request $request, CommunicationLog $log)
    {
        $data = $request->validate([
            'agent_id' => [
                'required',
                'exists:agents,agent_id',
            ],
        ]);

        $agent = Agent::where('agent_id', $data['agent_id'])
            ->where('status', 'Active')
            ->first();

        if (! $agent) {
            return back()->withErrors([
                'agent_id' => 'Selected agent is not available.',
            ]);
        }

        $log->update([
            'agent_id' => $agent->agent_id,
        ]);

        return back()->with(
            'success',
            'Agent assigned successfully.'
        );
    }

    /**
     * Rank a follow-up's priority value so it can be compared numerically.
     * Higher means more urgent for the customer.
     */
    private function priorityRank(?string $priority): int
    {
        return match ($priority) {
            'High' => 3,
            'Medium' => 2,
            'Low' => 1,
            default => 0,
        };
    }

    /**
     * Select the active agent with the fewest unfinished high-priority follow-ups.
     * High-priority follow-ups always use this automatic assignment.
     */
    private function recommendedAgentId(?CommunicationLog $excluding = null): ?int
    {
        $workloads = CommunicationLog::query()
            ->where('communication_status', 'Pending')
            ->where('priority', 'High')
            ->when($excluding, fn ($query) => $query->where('communication_id', '!=', $excluding->communication_id))
            ->selectRaw('agent_id, count(*) as workload')
            ->whereNotNull('agent_id')
            ->groupBy('agent_id')
            ->pluck('workload', 'agent_id');

        return Agent::query()
            ->where('status', 'Active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->sortBy(fn (Agent $agent) => $workloads->get($agent->agent_id, 0))
            ->first()?->agent_id;
    }

    private function isActiveAgent(int $agentId): bool
    {
        return Agent::query()
            ->where('agent_id', $agentId)
            ->where('status', 'Active')
            ->exists();
    }

    /**
     * Build the "Assigned Agents" options for every follow-up on the current page.
     *
     * Each active agent is ranked by how many pending follow-ups they already
     * hold at this follow-up's priority level or higher (the customer's
     * priority column on communication_logs) — the agent's own attributes,
     * such as hierarchy_level, are never used for the recommendation. The
     * agent with the lightest matching workload is marked as recommended.
     *
     * @param  Collection<int, CommunicationLog>  $followUps
     * @return array<int, array<int, array{employee_id:int,name:string,department:?string,workload:int,recommended:bool}>>
     */
    private function buildAgentAssignmentOptions(Collection $followUps): array
    {
        $activeAgents = Agent::where('status', 'Active')
            ->orderBy('first_name')
            ->get();

        $pendingByAgent = CommunicationLog::where('communication_status', 'Pending')
            ->whereNotNull('agent_id')
            ->get(['agent_id', 'priority'])
            ->groupBy('agent_id');

        $options = [];

        foreach ($followUps as $followUp) {
            $currentRank = $this->priorityRank($followUp->priority);

            $ranked = $activeAgents
                ->map(function (Agent $agent) use ($pendingByAgent, $currentRank) {
                    $agentPending = $pendingByAgent->get($agent->agent_id, collect());

                    $workload = $agentPending
                        ->filter(fn ($pending) => $this->priorityRank($pending->priority) >= $currentRank)
                        ->count();

                    return [
                        'agent_id' => $agent->agent_id,
                        'name' => $agent->full_name,
                        'department' => $agent->department,
                        'workload' => $workload,
                    ];
                })
                ->sortBy([
                    ['workload', 'asc'],
                    ['name', 'asc'],
                ])
                ->values();

            $options[$followUp->communication_id] = $ranked
                ->map(function (array $agent, int $index) {
                    $agent['recommended'] = $index === 0;

                    return $agent;
                })
                ->all();
        }

        return $options;
    }

    /**
     * Resolve the [start, end] Carbon range for the Follow-Up Analytics filter.
     */
    private function resolveAnalyticsRange(string $period, ?string $startDate, ?string $endDate): array
    {
        $today = now();

        return match ($period) {
            'last_week' => [
                $today->copy()->subWeek()->startOfWeek(),
                $today->copy()->subWeek()->endOfWeek(),
            ],

            'this_month' => [
                $today->copy()->startOfMonth(),
                $today->copy()->endOfMonth(),
            ],

            'last_month' => [
                $today->copy()->subMonth()->startOfMonth(),
                $today->copy()->subMonth()->endOfMonth(),
            ],

            'custom' => [
                $startDate ? Carbon::parse($startDate)->startOfDay() : $today->copy()->startOfWeek(),
                $endDate ? Carbon::parse($endDate)->endOfDay() : $today->copy()->endOfWeek(),
            ],

            default => [
                $today->copy()->startOfWeek(),
                $today->copy()->endOfWeek(),
            ],
        };
    }
}
