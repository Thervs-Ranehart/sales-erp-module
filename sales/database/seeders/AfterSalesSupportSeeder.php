<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\ResolutionTracking;
use App\Models\SatisfactionMonitoring;
use App\Models\ServiceContract;
use App\Models\ServiceRequest;
use App\Models\SupportAttachment;
use App\Models\SupportCaseEvent;
use App\Models\SupportTicket;
use App\Models\TicketAssignment;
use App\Models\WarrantyClaim;
use App\Models\WarrantyRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AfterSalesSupportSeeder extends Seeder
{
    /** Seed only records owned by the After-Sales Support and Case Management module. */
    public function run(): void
    {
        $orders = \App\Models\SalesOrder::query()
            ->with('items')
            ->where('order_status', 'Delivered')
            ->orderBy('order_id')
            ->get();
        $staff = Employee::query()
            ->whereIn('department', ['After-Sales Support', 'Operations'])
            ->orderBy('employee_id')
            ->get();

        if ($orders->isEmpty() || $staff->isEmpty()) {
            $this->command?->warn('AfterSalesSupportSeeder skipped: seed customers, orders, products, and support staff first.');

            return;
        }

        $cases = [
            ['Technical Support', 'Laptop fails to wake after sleep mode', 'Intermittent startup issue reported after a recent operating-system update.', 'High', 'In Progress'],
            ['Warranty', 'Printer produces faded output after cartridge replacement', 'Customer reports inconsistent print density on standard office documents.', 'Medium', 'Open'],
            ['Field Service', 'POS terminal loses network connection during checkout', 'Store staff observed three connection drops during peak trading hours.', 'High', 'Escalated'],
            ['Technical Support', 'Laptop docking station does not detect external display', 'Display is detected only after reconnecting the USB-C cable.', 'Medium', 'Pending'],
            ['Warranty', 'Printer paper-feed assembly requires replacement', 'Paper jams continue after the customer completed the cleaning procedure.', 'High', 'Resolved'],
            ['Field Service', 'POS terminal receipt printer alignment check', 'Customer requested a preventive on-site inspection before a sales event.', 'Low', 'Closed'],
            ['Technical Support', 'Laptop battery drains faster than expected', 'Battery level drops significantly while the device is idle overnight.', 'Medium', 'Open'],
            ['Warranty', 'Printer control panel displays a recurring error code', 'The error returns after a power cycle and standard troubleshooting.', 'High', 'In Progress'],
            ['Field Service', 'POS terminal barcode scanner calibration request', 'Scanner intermittently misses small retail barcode labels.', 'Medium', 'Pending'],
            ['Technical Support', 'Laptop camera is unavailable in video meetings', 'The built-in camera is not detected by approved conferencing software.', 'Low', 'Resolved'],
            ['Warranty', 'Printer tray sensor requires inspection', 'The device reports an empty tray while paper is loaded correctly.', 'Medium', 'Closed'],
            ['Field Service', 'POS terminal card reader diagnostic visit', 'Chip-card transactions occasionally require a second attempt.', 'High', 'Escalated'],
        ];

        foreach ($orders as $index => $order) {
            foreach ($order->items as $item) {
                $sample = $cases[$index % count($cases)];
                $createdAt = now()->subDays(3 + $index)->setTime(9 + ($index % 6), 15);
                $staffMember = $staff[$index % $staff->count()];
                $contract = ServiceContract::query()->updateOrCreate(
                    ['contract_number' => sprintf('ASC-%s-%03d', now()->format('Y'), $index + 1)],
                    [
                        'customer_id' => $order->customer_id,
                        'product_id' => $item->product_id,
                        'service_type' => $sample[0],
                        'service_start' => $createdAt->copy()->subMonths(2)->toDateString(),
                        'service_end' => $createdAt->copy()->addYear()->toDateString(),
                        'contract_status' => 'Active',
                        'service_limit' => 4,
                        'services_used' => $index % 3,
                    ],
                );
                $resolvedAt = in_array($sample[4], ['Resolved', 'Closed'], true) ? $createdAt->copy()->addDays(2) : null;
                $ticket = SupportTicket::query()->updateOrCreate(
                    ['order_id' => $order->order_id, 'product_id' => $item->product_id, 'subject' => $sample[1]],
                    [
                        'customer_id' => $order->customer_id,
                        'service_contract_id' => $contract->contract_id,
                        'ticket_type' => $sample[0],
                        'description' => $sample[2],
                        'priority' => $sample[3],
                        'status' => $sample[4],
                        'department' => 'After-Sales Support',
                        'created_at' => $createdAt,
                        'due_date' => $createdAt->copy()->addDays($sample[3] === 'High' ? 1 : 3),
                        'first_response_due_at' => $createdAt->copy()->addHours(4),
                        'resolution_due_at' => $createdAt->copy()->addHours($sample[3] === 'High' ? 24 : 72),
                        'escalation_level' => $sample[4] === 'Escalated' ? 1 : 0,
                        'last_escalated_at' => $sample[4] === 'Escalated' ? $createdAt->copy()->addHours(25) : null,
                        'resolved_at' => $resolvedAt,
                        'closed_at' => $sample[4] === 'Closed' ? $resolvedAt->copy()->addDay() : null,
                    ],
                );
                TicketAssignment::query()->updateOrCreate(
                    ['ticket_id' => $ticket->ticket_id, 'employee_id' => $staffMember->employee_id],
                    [
                        'assigned_at' => $createdAt->copy()->addMinutes(30),
                        'assignment_status' => 'Assigned',
                        'department' => 'After-Sales Support',
                        'assignment_reason' => 'Assigned according to category, priority, and current support workload.',
                    ],
                );
                $this->caseEvent($ticket, $staffMember, 'Created', 'Case logged from a verified customer after-sales request.', $createdAt);
                $this->caseEvent($ticket, $staffMember, 'Assigned', 'Case assigned to the support queue for initial assessment.', $createdAt->copy()->addMinutes(30));
                $this->caseEvent($ticket, $staffMember, 'Follow-up', 'Follow-up contact scheduled to confirm progress and customer availability.', $createdAt->copy()->addDay());

                $warranty = WarrantyRecord::query()->updateOrCreate(
                    ['warranty_number' => sprintf('WR-ASC-%s-%03d', now()->format('Y'), $index + 1)],
                    [
                        'order_id' => $order->order_id,
                        'product_id' => $item->product_id,
                        'warranty_start' => $createdAt->copy()->subMonths(6)->toDateString(),
                        'warranty_end' => $createdAt->copy()->addMonths(6)->toDateString(),
                        'warranty_status' => 'Active',
                    ],
                );
                ServiceRequest::query()->updateOrCreate(
                    ['request_number' => sprintf('SR-ASC-%s-%03d', now()->format('Y'), $index + 1)],
                    [
                        'ticket_id' => $ticket->ticket_id,
                        'technician_id' => $staffMember->employee_id,
                        'request_type' => $sample[0].' assessment',
                        'requested_at' => $createdAt->copy()->addHour(),
                        'scheduled_date' => $createdAt->copy()->addDay()->setTime(10, 0),
                        'scheduled_end' => $createdAt->copy()->addDay()->setTime(11, 30),
                        'schedule_notes' => 'Confirm access, bring the relevant diagnostic checklist, and record the customer outcome.',
                        'completion_date' => $resolvedAt,
                        'service_status' => $sample[4] === 'Closed' ? 'Completed' : ($sample[4] === 'Escalated' ? 'In Progress' : 'Scheduled'),
                        'service_result' => $resolvedAt ? 'Assessment completed and customer notified of the outcome.' : null,
                    ],
                );

                if ($index % 2 === 0) {
                    WarrantyClaim::query()->updateOrCreate(
                        ['warranty_id' => $warranty->warranty_id, 'ticket_id' => $ticket->ticket_id],
                        [
                            'claim_reason' => 'Customer requested warranty coverage review after the documented product issue.',
                            'claim_status' => $sample[4] === 'Closed' ? 'Completed' : 'Pending',
                            'claim_date' => $createdAt->copy()->addHours(2),
                            'approved_date' => $sample[4] === 'Closed' ? $createdAt->copy()->addDay() : null,
                            'eligibility_status' => 'Eligible',
                            'eligibility_notes' => 'Purchase, customer, and product details match the active warranty record.',
                        ],
                    );
                }
                if ($resolvedAt) {
                    ResolutionTracking::query()->updateOrCreate(
                        ['ticket_id' => $ticket->ticket_id, 'resolution_summary' => 'Customer issue resolved and outcome recorded.'],
                        [
                            'resolved_by' => $staffMember->employee_id,
                            'root_cause' => 'Verified configuration or component issue.',
                            'corrective_action' => 'Applied the approved corrective action and completed a functional check.',
                            'qc_status' => 'Passed',
                            'resolution_time_hours' => 2.5 + $index,
                            'resolved_at' => $resolvedAt,
                            'resolution_status' => 'Approved',
                            'approved_by' => $staff->first()->employee_id,
                            'approved_at' => $resolvedAt->copy()->addHour(),
                        ],
                    );
                    $this->caseEvent($ticket, $staffMember, 'Resolved', 'Resolution verified; customer was advised of the completed corrective action.', $resolvedAt);
                }
                if ($index < 6) {
                    $submittedAt = $resolvedAt?->copy()->addDay();
                    SatisfactionMonitoring::query()->updateOrCreate(
                        ['survey_token' => sprintf('asc-feedback-%03d', $index + 1)],
                        [
                            'ticket_id' => $ticket->ticket_id,
                            'rating' => $submittedAt ? ($index % 3 === 0 ? 5 : 4) : null,
                            'satisfaction_level' => $submittedAt ? ($index % 3 === 0 ? 'Very Satisfied' : 'Satisfied') : null,
                            'comments' => $submittedAt ? 'The support team provided clear updates and handled the request professionally.' : null,
                            'requested_at' => $createdAt->copy()->addDay(),
                            'submitted_at' => $submittedAt,
                        ],
                    );
                }
                SupportAttachment::query()->updateOrCreate(
                    ['ticket_id' => $ticket->ticket_id, 'original_name' => sprintf('case-summary-%03d.pdf', $index + 1)],
                    [
                        'uploaded_by' => $staffMember->employee_id,
                        'storage_path' => sprintf('support/tickets/%d/case-summary-%03d.pdf', $ticket->ticket_id, $index + 1),
                        'mime_type' => 'application/pdf',
                        'file_size' => 184320 + ($index * 1024),
                        'created_at' => $createdAt->copy()->addHours(3),
                    ],
                );
            }
        }

        $this->command?->info('AfterSalesSupportSeeder ensured realistic cases, assignments, histories, warranties, service requests, claims, resolutions, feedback, and attachments.');
    }

    private function caseEvent(SupportTicket $ticket, Employee $employee, string $type, string $description, Carbon $createdAt): void
    {
        SupportCaseEvent::query()->updateOrCreate(
            ['ticket_id' => $ticket->ticket_id, 'event_type' => $type, 'description' => $description],
            ['employee_id' => $employee->employee_id, 'created_at' => $createdAt],
        );
    }
}
