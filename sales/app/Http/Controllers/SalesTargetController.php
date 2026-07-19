<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalesTargetRequest;
use App\Models\SalesTarget;
use Illuminate\Http\RedirectResponse;

class SalesTargetController extends Controller
{
    public function store(StoreSalesTargetRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = (int) $request->session()->get('employee_id');
        $data['created_at'] = now();

        SalesTarget::query()->updateOrCreate(
            [
                'employee_id' => $data['employee_id'],
                'target_month' => $data['target_month'],
                'target_year' => $data['target_year'],
            ],
            $data,
        );

        return redirect()->route('forecasting.performance', ['year' => $data['target_year']])
            ->with('success', 'Sales target saved successfully.');
    }

    public function destroy(SalesTarget $salesTarget): RedirectResponse
    {
        $salesTarget->delete();

        return back()->with('success', 'Sales target deleted successfully.');
    }
}
