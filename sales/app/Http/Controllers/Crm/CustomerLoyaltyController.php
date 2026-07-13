<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyProgram;
use App\Models\Reward;
use Illuminate\Http\Request;

class CustomerLoyaltyController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $query = LoyaltyProgram::query()->with(['customer']);

        if ($search !== '') {
            $query->whereHas('customer', function ($cq) use ($search) {
                $cq->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('customer_id', 'like', "%{$search}%");
            });
        }

        $programs = $query->orderByDesc('available_points')->paginate(10)->withQueryString();

        $activeMembers = LoyaltyProgram::whereNotNull('enrollment_date')->count();
        $availablePoints = LoyaltyProgram::sum('available_points');
        $rewardsClaimed = LoyaltyProgram::sum('points_redeemed');
        $expiringPoints = LoyaltyProgram::where('available_points', '>', 0)
            ->whereDate('updated_at', '<=', now()->subMonths(11))
            ->sum('available_points');

        $rewards = Reward::orderByDesc('created_at')->get();

        return view('crm.customer-loyalty', [
            'loyalties' => $programs,
            'activeMembers' => $activeMembers,
            'availablePoints' => $availablePoints,
            'rewardsClaimed' => $rewardsClaimed,
            'expiringPoints' => $expiringPoints,
            'search' => $search,
            'rewards' => $rewards,
        ]);
    }

    public function show(LoyaltyProgram $loyalty)
    {
        $loyalty->load('customer');

        return view('crm.customer-loyalty', [
            'loyalties' => LoyaltyProgram::with('customer')->orderByDesc('available_points')->paginate(10),
            'activeMembers' => LoyaltyProgram::whereNotNull('enrollment_date')->count(),
            'availablePoints' => LoyaltyProgram::sum('available_points'),
            'rewardsClaimed' => LoyaltyProgram::sum('points_redeemed'),
            'expiringPoints' => 0,
            'search' => '',
            'selectedLoyalty' => $loyalty,
            'rewards' => Reward::orderByDesc('created_at')->get(),
        ]);
    }

    public function update(Request $request, LoyaltyProgram $loyalty)
    {
        $data = $request->validate([
            'membership_level' => ['required', 'string', 'max:100'],
            'available_points' => ['required', 'integer', 'min:0'],
            'points_earned' => ['nullable', 'integer', 'min:0'],
            'points_redeemed' => ['nullable', 'integer', 'min:0'],
        ]);

        $loyalty->update([
            'membership_level' => $data['membership_level'],
            'available_points' => $data['available_points'],
            'points_earned' => $data['points_earned'] ?? $loyalty->points_earned,
            'points_redeemed' => $data['points_redeemed'] ?? $loyalty->points_redeemed,
            'enrollment_date' => $loyalty->enrollment_date ?? now()->toDateString(),
        ]);

        return redirect()->route('crm.loyalty')->with('success', 'Loyalty information updated successfully.');
    }
}