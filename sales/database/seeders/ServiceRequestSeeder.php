<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\ServiceContract;
use App\Models\ServiceRequest;
use App\Models\SupportTicket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ServiceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $ticket = SupportTicket::query()
            ->whereNotNull('service_contract_id')
            ->with('serviceContract')
            ->first();

        $technician = Employee::query()->orderBy('employee_id')->first();

        if (! $ticket) {
            [$ticket, $technician] = $this->createDemoContext($technician);
        }

        $secondTechnician = Employee::query()
            ->where('employee_id', '!=', $technician->employee_id)
            ->orderBy('employee_id')
            ->first() ?? $technician;

        $requests = [
            [
                'request_number' => 'SR-2026-0001',
                'request_type' => 'On-site installation verification',
                'requested_at' => now()->subDays(3),
                'service_status' => 'Pending',
                'technician_id' => null,
                'scheduled_date' => null,
                'scheduled_end' => null,
                'schedule_notes' => 'Awaiting customer availability confirmation.',
                'completion_date' => null,
            ],
            [
                'request_number' => 'SR-2026-0002',
                'request_type' => 'Preventive maintenance visit',
                'requested_at' => now()->subDays(2),
                'service_status' => 'Scheduled',
                'technician_id' => $technician->employee_id,
                'scheduled_date' => now()->addDays(2)->setTime(9, 0),
                'scheduled_end' => now()->addDays(2)->setTime(11, 0),
                'schedule_notes' => 'Bring calibration tools and service checklist.',
                'completion_date' => null,
            ],
            [
                'request_number' => 'SR-2026-0003',
                'request_type' => 'Intermittent performance diagnosis',
                'requested_at' => now()->subDays(4),
                'service_status' => 'In Progress',
                'technician_id' => $secondTechnician->employee_id,
                'scheduled_date' => now()->subDay()->setTime(13, 0),
                'scheduled_end' => now()->subDay()->setTime(15, 0),
                'schedule_notes' => 'Technician is reviewing diagnostic logs on-site.',
                'completion_date' => null,
            ],
            [
                'request_number' => 'SR-2026-0004',
                'request_type' => 'Firmware update confirmation',
                'requested_at' => now()->subDays(9),
                'service_status' => 'Completed',
                'technician_id' => $technician->employee_id,
                'scheduled_date' => now()->subDays(5)->setTime(10, 0),
                'scheduled_end' => now()->subDays(5)->setTime(11, 0),
                'schedule_notes' => 'Firmware applied and customer acceptance recorded.',
                'completion_date' => now()->subDays(5)->setTime(11, 15),
            ],
            [
                'request_number' => 'SR-2026-0005',
                'request_type' => 'Customer-requested visit cancellation',
                'requested_at' => now()->subDay(),
                'service_status' => 'Cancelled',
                'technician_id' => null,
                'scheduled_date' => now()->addDays(4)->setTime(14, 0),
                'scheduled_end' => now()->addDays(4)->setTime(15, 0),
                'schedule_notes' => 'Customer cancelled before the scheduled visit.',
                'completion_date' => null,
            ],
        ];

        foreach ($requests as $request) {
            $request['ticket_id'] = $ticket->ticket_id;

            ServiceRequest::query()->updateOrCreate(
                ['request_number' => $request['request_number']],
                $request,
            );
        }
    }

    /** @return array{SupportTicket, Employee} */
    private function createDemoContext(?Employee $technician): array
    {
        $customer = Customer::query()->first()
            ?? Customer::query()->firstOrCreate(
                ['email' => 'service-request-demo@example.invalid'],
                ['first_name' => 'Service', 'last_name' => 'Demo'],
            );
        $product = Product::query()->first()
            ?? Product::query()->firstOrCreate(
                ['product_name' => 'Service Request Demo Unit'],
                ['product_status' => 'Active'],
            );
        $technician ??= Employee::query()->firstOrCreate(
            ['username' => 'service.request.demo'],
            [
                'password_hash' => Hash::make('service-request-demo'),
                'first_name' => 'Service',
                'last_name' => 'Technician',
                'department' => 'After-Sales Support',
                'employee_status' => 'Active',
            ],
        );
        $order = SalesOrder::query()->firstOrCreate(
            ['order_number' => 'SO-SR-DEMO-2026'],
            [
                'customer_id' => $customer->customer_id,
                'employee_id' => $technician->employee_id,
                'order_date' => today(),
                'order_status' => 'Delivered',
            ],
        );
        $contract = ServiceContract::query()->firstOrCreate(
            ['contract_number' => 'SC-SR-DEMO-2026'],
            [
                'customer_id' => $customer->customer_id,
                'product_id' => $product->product_id,
                'service_type' => 'After-Sales Support',
                'service_start' => today()->subMonth(),
                'service_end' => today()->addYear(),
                'contract_status' => 'Active',
            ],
        );
        $ticket = SupportTicket::query()->firstOrCreate(
            [
                'order_id' => $order->order_id,
                'customer_id' => $customer->customer_id,
                'product_id' => $product->product_id,
                'subject' => 'Service request demonstration case',
            ],
            [
                'service_contract_id' => $contract->contract_id,
                'ticket_type' => 'Service',
                'description' => 'Demonstration case used by the Service Request sample data seeder.',
                'priority' => 'Medium',
                'status' => 'Open',
                'created_at' => now(),
            ],
        );

        if ($ticket->service_contract_id !== $contract->contract_id) {
            $ticket->update(['service_contract_id' => $contract->contract_id]);
        }

        return [$ticket, $technician];
    }
}
