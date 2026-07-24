<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\LoyaltyPointTransaction;
use App\Models\LoyaltyProgram;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LoyaltyService
{
    public function awardForInvoice(Invoice $invoice): void
    {
        if ($invoice->payment_status !== 'Paid') {
            return;
        }

        $customer = $invoice->salesOrder?->customer;
        if (! $customer) {
            return;
        }

        $points = max(0, (int) floor((float) $invoice->total_amount / 100));
        if ($points > 0) {
            $this->post($customer, 'Earn', $points, $invoice, "Points earned from {$invoice->invoice_number}");
        }
    }

    public function reverseInvoice(Invoice $invoice): void
    {
        $loyalty = $invoice->salesOrder?->customer?->loyaltyProgram;
        if (! $loyalty) {
            return;
        }

        $earned = LoyaltyPointTransaction::query()->where([
            'loyalty_id' => $loyalty->loyalty_id,
            'transaction_type' => 'Earn',
            'source_type' => Invoice::class,
            'source_id' => $invoice->invoice_id,
        ])->value('points');

        if ($earned) {
            $this->post($loyalty->customer, 'Reversal', -abs((int) $earned), $invoice, "Points reversed for {$invoice->invoice_number}");
        }
    }

    public function post(Customer $customer, string $type, int $points, ?Model $source, string $description): LoyaltyPointTransaction
    {
        return DB::transaction(function () use ($customer, $type, $points, $source, $description): LoyaltyPointTransaction {
            $loyalty = LoyaltyProgram::query()->where('customer_id', $customer->customer_id)->lockForUpdate()->first()
                ?? LoyaltyProgram::query()->create([
                    'customer_id' => $customer->customer_id,
                    'membership_level' => 'Bronze',
                    'points_earned' => 0,
                    'points_redeemed' => 0,
                    'available_points' => 0,
                    'enrollment_date' => now(),
                ]);

            $existing = $source ? LoyaltyPointTransaction::query()->where([
                'loyalty_id' => $loyalty->loyalty_id,
                'transaction_type' => $type,
                'source_type' => $source::class,
                'source_id' => $source->getKey(),
            ])->first() : null;
            if ($existing) {
                return $existing;
            }

            $balance = max(0, (int) $loyalty->available_points + $points);
            $transaction = LoyaltyPointTransaction::query()->create([
                'loyalty_id' => $loyalty->loyalty_id,
                'employee_id' => request()->session()->get('employee_id'),
                'transaction_type' => $type,
                'points' => $points,
                'balance_after' => $balance,
                'source_type' => $source ? $source::class : null,
                'source_id' => $source?->getKey(),
                'description' => $description,
            ]);

            $loyalty->update([
                'available_points' => $balance,
                'points_earned' => (int) $loyalty->points_earned + max(0, $points),
                'points_redeemed' => (int) $loyalty->points_redeemed + ($type === 'Redeem' ? abs($points) : 0),
                'membership_level' => $this->tier($balance),
            ]);

            return $transaction;
        });
    }

    public function tier(int $balance): string
    {
        return match (true) {
            $balance >= 3000 => 'VIP',
            $balance >= 1500 => 'Gold',
            $balance >= 500 => 'Silver',
            default => 'Bronze',
        };
    }
}
