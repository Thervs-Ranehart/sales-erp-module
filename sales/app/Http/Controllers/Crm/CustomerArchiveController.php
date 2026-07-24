<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerArchiveController extends Controller
{
    public function archive(Request $request, Customer $customer): RedirectResponse
    {
        $data = $request->validate(['archive_reason' => ['required', 'string', 'max:1000']]);
        $customer->update([
            'customer_status' => 'Archived',
            'archived_at' => now(),
            'archived_by' => $request->session()->get('employee_id') ?? Employee::query()->value('employee_id'),
            'archive_reason' => $data['archive_reason'],
        ]);

        return back()->with('success', 'Customer archived. Transaction history was retained.');
    }

    public function restore(Customer $customer): RedirectResponse
    {
        $customer->update(['customer_status' => 'Active', 'archived_at' => null, 'archived_by' => null, 'archive_reason' => null]);

        return back()->with('success', 'Customer restored successfully.');
    }
}
