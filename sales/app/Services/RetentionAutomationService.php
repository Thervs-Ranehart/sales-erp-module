<?php

namespace App\Services;

use App\Models\CommunicationLog;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Notification;

class RetentionAutomationService
{
    public function run(): int
    {
        $employeeId = (int) Employee::query()->where('department', 'CRM')->value('employee_id')
            ?: (int) Employee::query()->value('employee_id');
        $created = 0;

        Customer::query()->available()->with('salesOrders')->get()->each(function (Customer $customer) use ($employeeId, &$created): void {
            $lastOrder = $customer->salesOrders->max('order_date');
            if (! $lastOrder || abs(now()->diffInDays($lastOrder)) <= 90) {
                return;
            }

            $key = 'retention-at-risk-'.$customer->customer_id.'-'.now()->format('Y-m');
            $log = CommunicationLog::query()->firstOrCreate(['automation_key' => $key], [
                'customer_id' => $customer->customer_id,
                'employee_id' => $employeeId,
                'communication_date' => now(),
                'communication_channel' => 'Follow-up',
                'subject' => 'Automated at-risk retention follow-up',
                'notes' => 'Contact this customer and review recent purchase behavior.',
                'follow_up_date' => now()->addDay(),
                'communication_status' => 'Pending',
                'priority' => 'High',
            ]);

            if ($log->wasRecentlyCreated) {
                Notification::query()->create([
                    'employee_id' => $employeeId,
                    'notification_type' => 'CRM Retention',
                    'title' => 'At-risk customer follow-up',
                    'message' => "Follow up with {$customer->full_name}.",
                    'related_module' => 'CRM',
                    'related_record_id' => $customer->customer_id,
                    'is_read' => false,
                    'created_at' => now(),
                ]);
                $created++;
            }
        });

        return $created;
    }
}
