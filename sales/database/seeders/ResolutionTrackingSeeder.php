<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\ResolutionTracking;
use App\Models\SupportTicket;
use Illuminate\Database\Seeder;

class ResolutionTrackingSeeder extends Seeder
{
    public function run(): void
    {
        $tickets = SupportTicket::query()->orderBy('ticket_id')->get();
        $employees = Employee::query()->orderBy('employee_id')->get();

        if ($tickets->isEmpty() || $employees->isEmpty()) {
            return;
        }

        $samples = [
            ['Resolved quickly after configuration correction', 'Incorrect setup', 'Configuration updated and QC passed.', 'Passed', 1.5, now()->subDays(7)],
            ['Replacement component installed', 'Component failure', 'Replacement installed; QC passed.', 'Passed', 6.0, now()->subDays(6)],
            ['Awaiting follow-up diagnostics', 'Intermittent fault', 'Follow-up inspection is pending.', 'Pending', 0.0, null],
            ['Resolved after extended investigation', 'Firmware compatibility', 'Firmware updated and verified.', 'Passed', 18.5, now()->subDays(4)],
            ['Quality review requires rework', 'Incomplete repair', 'QC failed; corrective rework required.', 'Failed', 9.0, now()->subDays(3)],
            ['Customer confirmation pending', 'Usage clarification required', 'Resolution explained; awaiting confirmation.', 'Pending', 3.0, null],
            ['Warranty process clarified', 'Coverage misunderstanding', 'Warranty terms explained and acknowledged.', 'Passed', 2.0, now()->subDays(2)],
            ['Follow-up visit required', 'Recurring performance issue', 'Field visit scheduled for follow-up.', 'Pending', 12.0, null],
        ];

        foreach ($samples as $index => [$summary, $cause, $action, $qc, $hours, $resolvedAt]) {
            $ticket = $tickets[$index % $tickets->count()];
            $employee = $employees[$index % $employees->count()];
            ResolutionTracking::query()->updateOrCreate(
                ['ticket_id' => $ticket->ticket_id, 'resolution_summary' => $summary],
                ['resolved_by' => $employee->employee_id, 'root_cause' => $cause, 'corrective_action' => $action, 'qc_status' => $qc, 'resolution_time_hours' => $hours, 'resolved_at' => $resolvedAt],
            );
        }
    }
}
