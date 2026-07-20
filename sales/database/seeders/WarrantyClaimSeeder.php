<?php

namespace Database\Seeders;

use App\Models\WarrantyClaim;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarrantyClaimSeeder extends Seeder
{
    /**
     * Create representative claims only for existing warranty/ticket pairs that
     * share the same customer and product. This keeps every foreign key valid.
     */
    public function run(): void
    {
        $pairs = DB::table('warranty_records as warranties')
            ->join('sales_orders as orders', 'orders.order_id', '=', 'warranties.order_id')
            ->join('support_tickets as tickets', function ($join): void {
                $join->on('tickets.customer_id', '=', 'orders.customer_id')
                    ->on('tickets.product_id', '=', 'warranties.product_id');
            })
            ->orderBy('warranties.warranty_id')
            ->orderBy('tickets.ticket_id')
            ->get(['warranties.warranty_id', 'tickets.ticket_id']);

        if ($pairs->isEmpty()) {
            $this->command?->warn('WarrantyClaimSeeder skipped: no compatible warranty and support-ticket pairs exist.');

            return;
        }

        $claims = [
            ['Defective product reported after normal use', 'Pending', 2],
            ['Hardware failure requiring diagnostic review', 'Pending', 6],
            ['Damaged component requires warranty assessment', 'Approved', 18],
            ['Recurring malfunction reported after prior repair', 'Completed', 35],
            ['Product stopped working during normal operation', 'Rejected', 48],
            ['Replacement request for a failed covered unit', 'Approved', 64],
            ['Repair request for intermittent power loss', 'Completed', 83],
            ['Warranty coverage verification before service', 'Pending', 101],
            ['Accidental damage review submitted for assessment', 'Rejected', 126],
            ['Missing accessory claim for included component', 'Completed', 154],
        ];

        foreach ($claims as $index => [$reason, $status, $daysAgo]) {
            $pair = $pairs[$index % $pairs->count()];
            $claimDate = now()->subDays($daysAgo)->setTime(10 + ($index % 6), 15);
            $approvedDate = in_array($status, ['Approved', 'Completed'], true)
                ? $claimDate->copy()->addDays(2)->setTime(14, 30)
                : null;

            WarrantyClaim::query()->updateOrCreate(
                [
                    'warranty_id' => $pair->warranty_id,
                    'ticket_id' => $pair->ticket_id,
                    'claim_reason' => $reason,
                ],
                [
                    'claim_status' => $status,
                    'claim_date' => $claimDate,
                    'approved_date' => $approvedDate,
                ],
            );
        }

        $this->command?->info('WarrantyClaimSeeder ensured 10 realistic warranty claim samples.');
    }
}
