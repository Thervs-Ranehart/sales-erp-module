<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerBehaviorAnalysis;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerProfilesController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $customerId = $request->query('customer_id');

        $query = Customer::query()->with(['profile', 'loyaltyProgram', 'behaviorAnalyses']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('customer_id', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderByDesc('customer_id')->paginate(10)->withQueryString();

        $selectedCustomer = null;
$customer = null;
$purchases = collect();
$communications = collect();
$behaviorAnalysis = null;
$editMode = $request->boolean('edit');

/*
|--------------------------------------------------------------------------
| Auto-select customer
|--------------------------------------------------------------------------
*/
if ($customerId) {

    $selectedCustomer = Customer::with([
        'profile',
        'loyaltyProgram',
        'segments',
        'behaviorAnalyses',
        'salesOrders.items.product',
        'communicationLogs',
    ])->where('customer_id', $customerId)->first();

} elseif ($search !== '' && $customers->count()) {

    // kapag search lang ang ginawa, unang result ang piliin
    $selectedCustomer = Customer::with([
        'profile',
        'loyaltyProgram',
        'segments',
        'behaviorAnalyses',
        'salesOrders.items.product',
        'communicationLogs',
    ])->where('customer_id', $customers->first()->customer_id)->first();

}

if ($selectedCustomer) {
    $customer = $this->formatCustomerProfile($selectedCustomer);
    $purchases = $this->formatRecentPurchases($selectedCustomer);
    $communications = $this->formatRecentCommunications($selectedCustomer);
    $behaviorAnalysis = CustomerBehaviorAnalysis::generateFor($selectedCustomer);
}

        return view('crm.customer-profiles', [
            'customers' => $customers,
            'selectedCustomer' => $selectedCustomer,
            'search' => $search,
            'customer' => $customer,
            'purchases' => $purchases,
            'communications' => $communications,
            'behaviorAnalysis' => $behaviorAnalysis,
            'editMode' => $editMode,
        ]);
    }

    public function edit(Customer $customer)
    {
        $customer->load(['profile', 'loyaltyProgram', 'segments', 'salesOrders.items.product', 'communicationLogs']);

        return view('crm.customer-profiles', [
            'customers' => Customer::orderByDesc('customer_id')->paginate(10),
            'selectedCustomer' => $customer,
            'search' => '',
            'customer' => $this->formatCustomerProfile($customer),
            'purchases' => $this->formatRecentPurchases($customer),
            'communications' => $this->formatRecentCommunications($customer),
            'behaviorAnalysis' => CustomerBehaviorAnalysis::generateFor($customer),
            'editMode' => true,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,customer_id'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'contact_no' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'preferences' => ['nullable', 'string'],
            'gender' => ['nullable', 'string', 'max:50'],
            'birth_date' => ['nullable', 'date'],
            'preferred_contact' => ['nullable', 'string', 'max:100'],
            'preferred_product_category' => ['nullable', 'string', 'max:255'],
            'marketing_consent' => ['nullable', 'boolean'],
        ]);

        $customer = Customer::findOrFail($data['customer_id']);

        $customerFields = collect($data)->only([
            'first_name', 'last_name', 'email', 'contact_no', 'address', 'preferences',
        ])->filter(fn ($value) => ! is_null($value))->all();

        if ($customerFields) {
            if (isset($customerFields['email'])) {
                $request->validate([
                    'email' => ['email', 'max:255', Rule::unique('customers', 'email')->ignore($customer->customer_id, 'customer_id')],
                ]);
            }
            $customer->update($customerFields);
        }

        $profileFields = collect($data)->only([
            'gender', 'birth_date', 'preferred_contact', 'preferred_product_category', 'marketing_consent',
        ])->all();
        $profileFields['marketing_consent'] = $request->boolean('marketing_consent');

        $customer->profile()->updateOrCreate(
            ['customer_id' => $customer->customer_id],
            $profileFields
        );

        return redirect()->route('crm.profiles', ['customer_id' => $customer->customer_id])
            ->with('success', 'Profile saved successfully.');
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($customer->customer_id, 'customer_id')],
            'contact_no' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'preferences' => ['nullable', 'string'],
            'gender' => ['nullable', 'string', 'max:50'],
            'birth_date' => ['nullable', 'date'],
            'preferred_contact' => ['nullable', 'string', 'max:100'],
            'preferred_product_category' => ['nullable', 'string', 'max:255'],
            'marketing_consent' => ['nullable', 'boolean'],
        ]);

        $customer->update(collect($data)->only([
            'first_name', 'last_name', 'email', 'contact_no', 'address', 'preferences',
        ])->all());

        $customer->profile()->updateOrCreate(
            ['customer_id' => $customer->customer_id],
            [
                'gender' => $data['gender'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'preferred_contact' => $data['preferred_contact'] ?? null,
                'preferred_product_category' => $data['preferred_product_category'] ?? null,
                'marketing_consent' => $request->boolean('marketing_consent'),
            ]
        );

        return redirect()->route('crm.profiles', ['customer_id' => $customer->customer_id])
            ->with('success', 'Profile updated successfully.');
    }

    private function formatCustomerProfile(Customer $selectedCustomer): array
    {
        return [
            'id' => $selectedCustomer->customer_id,
            'first_name' => $selectedCustomer->first_name,
            'last_name' => $selectedCustomer->last_name,
            'initials' => collect([
                $selectedCustomer->first_name ? strtoupper(substr($selectedCustomer->first_name, 0, 1)) : '',
                $selectedCustomer->last_name ? strtoupper(substr($selectedCustomer->last_name, 0, 1)) : '',
            ])->implode(''),
            'name' => $selectedCustomer->display_name,
            'status' => $selectedCustomer->loyaltyProgram?->enrollment_date ? 'Active Customer' : 'Inactive Customer',
            'orders' => $selectedCustomer->salesOrders->count(),
            'spending' => number_format($selectedCustomer->salesOrders->sum('total_amount')),
            'loyalty' => (int) ($selectedCustomer->loyaltyProgram?->available_points ?? 0),
            'since' => optional($selectedCustomer->created_at)->format('Y') ?? '',
            'email' => $selectedCustomer->email,
            'phone' => $selectedCustomer->contact_no,
            'address' => $selectedCustomer->address,
            'preferences' => $selectedCustomer->preferences,
            'gender' => $selectedCustomer->profile?->gender,
            'birthdate' => optional($selectedCustomer->profile?->birth_date)->format('Y-m-d'),
            'preferred_contact' => $selectedCustomer->profile?->preferred_contact,
            'preferred_product' => $selectedCustomer->profile?->preferred_product_category,
            'type' => optional($selectedCustomer->segments->first())->segment_name,
            'marketing' => $selectedCustomer->profile?->marketing_consent ? 'Approved' : 'Not Approved',
            'marketing_consent' => (bool) ($selectedCustomer->profile?->marketing_consent ?? false),
        ];
    }

    private function formatRecentPurchases(Customer $selectedCustomer)
    {
        return $selectedCustomer->salesOrders
            ->flatMap(function ($order) {
                return $order->items->map(function ($item) use ($order) {
                    return [
                        'product' => $item->product?->product_name ?? '',
                        'price' => $item->subtotal ?? $item->unit_price ?? 0,
                        'date' => optional($order->order_date)->toFormattedDateString(),
                        'order_date' => $order->order_date,
                    ];
                });
            })
            ->sortByDesc('order_date')
            ->values()
            ->take(5);
    }

    private function formatRecentCommunications(Customer $selectedCustomer)
    {
        return $selectedCustomer->communicationLogs
            ->sortByDesc('communication_date')
            ->values()
            ->take(5)
            ->map(function ($log) {
                return [
                    'channel' => $log->communication_channel ?? '—',
                    'subject' => $log->subject ?? '—',
                    'date' => optional($log->communication_date)->format('M d, Y'),
                    'status' => $log->communication_status ?? '—',
                ];
            });
    }
}