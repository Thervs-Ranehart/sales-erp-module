<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Customer;
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

        // Customers who don't have a loyalty_programs row yet — used to
        // populate the "Enroll Customer" dropdown.
        $unenrolledCustomers = Customer::whereDoesntHave('loyaltyProgram')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('crm.customer-loyalty', array_merge([
            'loyalties' => $programs,
            'activeMembers' => $activeMembers,
            'availablePoints' => $availablePoints,
            'rewardsClaimed' => $rewardsClaimed,
            'expiringPoints' => $expiringPoints,
            'search' => $search,
            'rewards' => $rewards,
            'unenrolledCustomers' => $unenrolledCustomers,
        ], $this->tierCounts()));
    }

    public function show(LoyaltyProgram $loyalty)
    {
        $loyalty->load('customer');

        return view('crm.customer-loyalty', array_merge([
            'loyalties' => LoyaltyProgram::with('customer')->orderByDesc('available_points')->paginate(10),
            'activeMembers' => LoyaltyProgram::whereNotNull('enrollment_date')->count(),
            'availablePoints' => LoyaltyProgram::sum('available_points'),
            'rewardsClaimed' => LoyaltyProgram::sum('points_redeemed'),
            'expiringPoints' => 0,
            'search' => '',
            'selectedLoyalty' => $loyalty,
            'rewards' => Reward::orderByDesc('created_at')->get(),
            'unenrolledCustomers' => Customer::whereDoesntHave('loyaltyProgram')
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get(),
        ], $this->tierCounts()));
    }

    /**
     * Member counts per membership tier, computed against the whole table
     * (not just the current paginated page) so the "Membership Levels"
     * panel always reflects reality regardless of which page you're on.
     */
    protected function tierCounts(): array
    {
        return [
            'goldCount' => LoyaltyProgram::where('membership_level', 'Gold')->count(),
            'silverCount' => LoyaltyProgram::where('membership_level', 'Silver')->count(),
            'bronzeCount' => LoyaltyProgram::where('membership_level', 'Bronze')->count(),
        ];
    }

    /**
     * Enroll a customer into the loyalty program (creates their loyalty_programs row).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,customer_id', 'unique:loyalty_programs,customer_id'],
            'membership_level' => ['required', 'string', 'max:100'],
            'available_points' => ['nullable', 'integer', 'min:0'],
        ]);

        $startingPoints = $data['available_points'] ?? 0;

        LoyaltyProgram::create([
            'customer_id' => $data['customer_id'],
            'membership_level' => $data['membership_level'],
            'available_points' => $startingPoints,
            'points_earned' => $startingPoints,
            'points_redeemed' => 0,
            'enrollment_date' => now()->toDateString(),
        ]);

        return redirect()->route('crm.loyalty')->with('success', 'Customer enrolled in the loyalty program successfully.');
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