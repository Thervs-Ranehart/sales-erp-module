<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\MarketingCampaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MarketingCampaignController extends Controller
{
    public function index(): View
    {
        return view('crm.marketing-campaigns', [
            'campaigns' => MarketingCampaign::query()->with('recipients.customer')->latest()->get(),
            'segments' => DB::table('customer_segments')->whereNotNull('segment_name')->distinct()->pluck('segment_name'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'campaign_name' => ['required', 'string', 'max:255'],
            'objective' => ['nullable', 'string', 'max:255'],
            'channel' => ['required', 'in:Email,SMS,Phone,In-App'],
            'target_segment' => ['nullable', 'string', 'max:100'],
            'target_loyalty_tier' => ['nullable', 'string', 'max:100'],
            'scheduled_at' => ['nullable', 'date'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $campaign = DB::transaction(function () use ($data, $request): MarketingCampaign {
            $campaign = MarketingCampaign::query()->create([
                ...$data,
                'created_by' => $request->session()->get('employee_id') ?? Employee::query()->value('employee_id'),
                'status' => 'Scheduled',
            ]);
            $customers = Customer::query()->available()
                ->whereHas('profile', fn ($query) => $query->where('marketing_consent', true))
                ->when($data['target_segment'] ?? null, fn ($query, $segment) => $query->whereHas('segments', fn ($segmentQuery) => $segmentQuery->where('segment_name', $segment)))
                ->when($data['target_loyalty_tier'] ?? null, fn ($query, $tier) => $query->whereHas('loyaltyProgram', fn ($loyaltyQuery) => $loyaltyQuery->where('membership_level', $tier)))
                ->get();
            foreach ($customers as $customer) {
                $campaign->recipients()->create(['customer_id' => $customer->customer_id, 'delivery_status' => 'Queued']);
            }

            return $campaign;
        });

        return back()->with('success', "Campaign created with {$campaign->recipients()->count()} consented recipients.");
    }
}
