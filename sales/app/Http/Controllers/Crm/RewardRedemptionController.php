<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LoyaltyProgram;
use App\Models\Reward;
use App\Models\RewardRedemption;
use App\Services\LoyaltyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RewardRedemptionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'loyalty_id' => ['required', 'exists:loyalty_programs,loyalty_id'],
            'reward_id' => ['required', 'exists:rewards,reward_id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $redemption = DB::transaction(function () use ($data, $request): RewardRedemption {
            $loyalty = LoyaltyProgram::query()->with('customer')->lockForUpdate()->findOrFail($data['loyalty_id']);
            $reward = Reward::query()->findOrFail($data['reward_id']);
            $points = (int) $reward->points_required * (int) $data['quantity'];
            if ($reward->status === 'unavailable' || (int) $loyalty->available_points < $points) {
                throw ValidationException::withMessages(['reward_id' => 'This reward is unavailable or the customer has insufficient points.']);
            }

            $redemption = RewardRedemption::query()->create([
                'redemption_number' => null,
                'loyalty_id' => $loyalty->loyalty_id,
                'reward_id' => $reward->reward_id,
                'processed_by' => $request->session()->get('employee_id') ?? Employee::query()->value('employee_id'),
                'points_used' => $points,
                'quantity' => $data['quantity'],
                'status' => 'Fulfilled',
                'redeemed_at' => now(),
                'notes' => $data['notes'] ?? null,
            ]);
            $redemption->update(['redemption_number' => 'RDM-'.str_pad((string) $redemption->redemption_id, 5, '0', STR_PAD_LEFT)]);
            app(LoyaltyService::class)->post($loyalty->customer, 'Redeem', -$points, $redemption, "Redeemed {$reward->name}");

            return $redemption;
        });

        return back()->with('success', "{$redemption->redemption_number} completed successfully.");
    }

    public function cancel(RewardRedemption $redemption): RedirectResponse
    {
        if ($redemption->status === 'Cancelled') {
            return back();
        }

        DB::transaction(function () use ($redemption): void {
            $redemption->load('loyalty.customer', 'reward');
            $redemption->update(['status' => 'Cancelled', 'cancelled_at' => now()]);
            app(LoyaltyService::class)->post(
                $redemption->loyalty->customer,
                'Adjustment',
                $redemption->points_used,
                $redemption,
                "Restored points from cancelled {$redemption->redemption_number}"
            );
        });

        return back()->with('success', 'Redemption cancelled and points restored.');
    }
}
