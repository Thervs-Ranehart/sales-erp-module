<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $employeeId = $request->session()->get('employee_id');

        if (! $employeeId) {
            return redirect('/')->withErrors([
                'username' => 'Please sign in to view your profile.',
            ]);
        }

        $employee = Employee::query()->find($employeeId);

        if (! $employee) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->withErrors([
                'username' => 'Your account could not be found. Please sign in again.',
            ]);
        }

        return view('profile.index', [
            'employee' => $employee,
        ]);
    }
}
