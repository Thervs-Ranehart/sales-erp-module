<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Notification;
use App\Models\SatisfactionMonitoring;
use App\Models\SupportTicket;
use App\Models\WarrantyRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AfterSalesAutomationService
{
    /** @return array{first_response_due_at: Carbon, resolution_due_at: Carbon} */
    public function deadlines(string $priority): array
    {
        return $this->deadlinesFrom(now(), $priority);
    }

    /** @return array{first_response_due_at: Carbon, resolution_due_at: Carbon} */
    public function deadlinesFrom(Carbon $openedAt, string $priority): array
    {
        [$responseHours, $resolutionHours] = match (strtolower($priority)) {
            'high' => [1, 8],
            'medium' => [4, 24],
            default => [8, 72],
        };

        return [
            'first_response_due_at' => $openedAt->copy()->addHours($responseHours),
            'resolution_due_at' => $openedAt->copy()->addHours($resolutionHours),
        ];
    }

    /** @return array{eligible: bool, reason: string} */
    public function warrantyEligibility(WarrantyRecord $warranty, SupportTicket $ticket): array
    {
        if ($warranty->archived_at !== null) {
            return ['eligible' => false, 'reason' => 'Warranty record is archived.'];
        }
        if ($warranty->order_id !== $ticket->order_id || $warranty->product_id !== $ticket->product_id) {
            return ['eligible' => false, 'reason' => 'Warranty does not match the ticket order and product.'];
        }
        if (! in_array($warranty->currentStatus(), ['Active', 'Expiring Soon'], true)) {
            return ['eligible' => false, 'reason' => 'Warranty coverage is not active.'];
        }

        return ['eligible' => true, 'reason' => 'Order, product, and coverage dates are valid.'];
    }

    public function requestSatisfactionFeedback(SupportTicket $ticket): SatisfactionMonitoring
    {
        return SatisfactionMonitoring::query()->firstOrCreate(
            ['ticket_id' => $ticket->ticket_id],
            ['survey_token' => Str::uuid()->toString(), 'requested_at' => now()]
        );
    }

    public function escalateBreaches(): int
    {
        SupportTicket::query()->whereNull('resolution_due_at')->get()->each(function (SupportTicket $ticket): void {
            $ticket->update($this->deadlinesFrom($ticket->created_at ?? now(), $ticket->priority ?? 'Low'));
        });

        $employeeId = Employee::query()->where('department', 'After-Sales Support')->value('employee_id')
            ?? Employee::query()->value('employee_id');
        $count = 0;

        SupportTicket::query()->whereNull('archived_at')
            ->whereNotIn('status', ['Resolved', 'Closed'])
            ->where('resolution_due_at', '<', now())
            ->get()
            ->each(function (SupportTicket $ticket) use ($employeeId, &$count): void {
                DB::transaction(function () use ($ticket, $employeeId, &$count): void {
                    $level = min(3, (int) $ticket->escalation_level + 1);
                    $ticket->update(['status' => 'Escalated', 'escalation_level' => $level, 'last_escalated_at' => now()]);
                    $ticket->caseEvents()->create([
                        'employee_id' => $employeeId,
                        'event_type' => 'SLA Escalation',
                        'description' => "Resolution SLA breached. Escalated to level {$level}.",
                        'created_at' => now(),
                    ]);
                    Notification::query()->create([
                        'employee_id' => $employeeId,
                        'notification_type' => 'Support SLA',
                        'title' => "SLA breach: TK-{$ticket->ticket_id}",
                        'message' => "Ticket {$ticket->subject} requires escalation.",
                        'related_module' => 'After-Sales Support',
                        'related_record_id' => $ticket->ticket_id,
                        'is_read' => false,
                        'created_at' => now(),
                    ]);
                    $count++;
                });
            });

        return $count;
    }
}
