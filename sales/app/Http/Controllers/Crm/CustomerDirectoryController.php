<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status');
        $customerType = $request->query('type');

        $query = Customer::query()->with(['profile', 'loyaltyProgram', 'communicationLogs'])->withCount('salesOrders');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('contact_no', 'like', "%{$search}%")
                    ->orWhere('customer_id', 'like', "%{$search}%");
            });
        }

        if ($status === 'Active') {
            $query->where(function ($q) {
                $q->whereHas('communicationLogs', function ($logQuery) {
                    $logQuery->where('communication_status', '!=', 'Inactive');
                })->orWhereHas('salesOrders')
                    ->orWhereHas('loyaltyProgram', function ($loyaltyQuery) {
                        $loyaltyQuery->whereNotNull('enrollment_date');
                    });
            });
        } elseif ($status === 'Inactive') {
            $query->whereDoesntHave('communicationLogs', function ($logQuery) {
                $logQuery->where('communication_status', '!=', 'Inactive');
            })->whereDoesntHave('salesOrders')
                ->where(function ($q) {
                    $q->whereDoesntHave('loyaltyProgram')
                        ->orWhereHas('loyaltyProgram', function ($loyaltyQuery) {
                            $loyaltyQuery->whereNull('enrollment_date');
                        });
                });
        }

        if ($customerType === 'VIP') {
            $query->whereHas('loyaltyProgram', function ($q) {
                $q->where('membership_level', 'VIP');
            });
        } elseif ($customerType === 'Corporate') {
            $query->whereHas('loyaltyProgram', function ($q) {
                $q->where('membership_level', 'Corporate');
            });
        } elseif ($customerType === 'Regular') {
            $query->where(function ($q) {
                $q->whereDoesntHave('loyaltyProgram')
                    ->orWhereHas('loyaltyProgram', function ($loyaltyQuery) {
                        $loyaltyQuery->where(function ($levelQuery) {
                            $levelQuery->whereNull('membership_level')
                                ->orWhereNotIn('membership_level', ['VIP', 'Corporate']);
                        });
                    });
            });
        }

        $customers = $query
            ->orderByDesc('customer_id')
            ->paginate(10)
            ->withQueryString();

        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where(function ($q) {
            $q->whereHas('communicationLogs', function ($logQuery) {
                $logQuery->where('communication_status', '!=', 'Inactive');
            })->orWhereHas('salesOrders')
                ->orWhereHas('loyaltyProgram', function ($loyaltyQuery) {
                    $loyaltyQuery->whereNotNull('enrollment_date');
                });
        })->count();

        $newCustomers = Customer::whereDate('created_at', '>=', now()->subDays(30))->count();
        $inactiveAccounts = max(0, $totalCustomers - $activeCustomers);

        $regularCount = Customer::where(function ($q) {
            $q->whereDoesntHave('loyaltyProgram')
                ->orWhereHas('loyaltyProgram', function ($loyaltyQuery) {
                    $loyaltyQuery->where(function ($levelQuery) {
                        $levelQuery->whereNull('membership_level')
                            ->orWhereNotIn('membership_level', ['VIP', 'Corporate']);
                    });
                });
        })->count();

        $vipCount = Customer::whereHas('loyaltyProgram', function ($q) {
            $q->where('membership_level', 'VIP');
        })->count();

        $corporateCount = Customer::whereHas('loyaltyProgram', function ($q) {
            $q->where('membership_level', 'Corporate');
        })->count();

        return view('crm.customer-directory', [
            'customers' => $customers,
            'totalCustomers' => $totalCustomers,
            'activeCustomers' => $activeCustomers,
            'newCustomers' => $newCustomers,
            'inactiveAccounts' => $inactiveAccounts,
            'regularCount' => $regularCount,
            'vipCount' => $vipCount,
            'corporateCount' => $corporateCount,
            'appliedStatus' => $status,
            'appliedType' => $customerType,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('crm.customer-create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:customers,email'],
            'contact_no' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'preferences' => ['nullable', 'string'],
        ]);

        Customer::create($data);

        return redirect()->route('crm.directory')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['profile', 'loyaltyProgram', 'segments', 'communicationLogs', 'salesOrders.items.product']);

        return view('crm.customer-profiles', [
            'customers' => Customer::orderByDesc('customer_id')->paginate(10),
            'selectedCustomer' => $customer,
            'search' => '',
            'customer' => $this->formatCustomerProfile($customer),
            'purchases' => $this->formatRecentPurchases($customer),
            'editMode' => false,
        ]);
    }

    public function edit(Customer $customer)
    {
        return view('crm.customer-edit', compact('customer'));
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
        ]);

        $customer->update($data);

        return redirect()->route('crm.directory')->with('success', 'Customer updated successfully.');
    }
public function destroy(Customer $customer)
{
    if ($customer->salesOrders()->exists()) {
        return redirect()->route('crm.directory')->with(
            'error',
            'This customer has sales history and cannot be deleted. Keep the record to preserve its linked transactions.'
        );
    }

    DB::transaction(function () use ($customer) {
        $customer->profile()->delete();
        $customer->communicationLogs()->delete();
        $customer->loyaltyProgram()->delete();
        $customer->segments()->delete();
        $customer->behaviorAnalyses()->delete();
        $customer->delete();
    });

    return redirect()->route('crm.directory')->with(
        'success',
        'Customer deleted successfully.'
    );
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
            'birthdate' => optional($selectedCustomer->profile?->birth_date)->toFormattedDateString(),
            'preferred_contact' => $selectedCustomer->profile?->preferred_contact,
            'preferred_product' => $selectedCustomer->profile?->preferred_product_category,
            'type' => optional($selectedCustomer->segments->first())->segment_name,
            'marketing' => $selectedCustomer->profile?->marketing_consent ? 'Approved' : 'Not Approved',
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
}
