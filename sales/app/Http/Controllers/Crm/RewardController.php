<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    /**
     * Store a newly created reward (Create Reward modal on the Loyalty page).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'points_required' => ['required', 'integer', 'min:0'],
            'icon' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:available,limited,unavailable'],
        ]);

        $validated['icon'] = $validated['icon'] ?: 'bi-gift';

        Reward::create($validated);

        return redirect()
            ->route('crm.loyalty')
            ->with('success', 'Reward created successfully.');
    }

    /**
     * Update an existing reward (Edit action inside the Manage Rewards modal).
     */
    public function update(Request $request, Reward $reward): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'points_required' => ['required', 'integer', 'min:0'],
            'icon' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:available,limited,unavailable'],
        ]);

        $validated['icon'] = $validated['icon'] ?: 'bi-gift';

        $reward->update($validated);

        return redirect()
            ->route('crm.loyalty')
            ->with('success', 'Reward updated successfully.');
    }

    /**
     * Delete a reward (Delete action inside the Manage Rewards modal).
     */
    public function destroy(Reward $reward): RedirectResponse
    {
        $reward->delete();

        return redirect()
            ->route('crm.loyalty')
            ->with('success', 'Reward deleted successfully.');
    }
}