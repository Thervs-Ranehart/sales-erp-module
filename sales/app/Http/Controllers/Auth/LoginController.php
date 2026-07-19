<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LoginAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class LoginController extends Controller
{
    /**
     * Max consecutive failed attempts allowed before an account is locked.
     */
    protected const MAX_ATTEMPTS = 5;

    /**
     * Lockout duration once the attempt limit is reached.
     */
    protected const LOCKOUT_MINUTES = 15;

    /**
     * Generic message shown for any failed login — wrong username, wrong
     * password, or unknown username all look identical to the client so an
     * attacker can't use the response to enumerate valid accounts.
     */
    protected const GENERIC_ERROR = 'Invalid email/username or password.';

    /**
     * Show the login form. If already logged in, skip straight to the dashboard.
     */
    public function show(Request $request)
    {
        if ($request->session()->has('employee_id')) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    /**
     * Validate the submitted username/password against the employees table.
     * Wrong credentials never make it past this point.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $ipAddress = $request->ip();
        $userAgent = (string) $request->userAgent();

        $employee = Employee::where('username', $credentials['username'])->first();

        // If a previous lockout has already expired, clear it out so the
        // employee gets a fresh set of attempts instead of staying flagged
        // as locked forever.
        if ($employee && $employee->locked_until !== null && $employee->locked_until->isPast()) {
            $employee->forceFill([
                'failed_login_attempts' => 0,
                'locked_until' => null,
            ])->save();
        }

        // Account is currently locked — reject immediately without checking
        // the password, and log the attempt for auditing.
        if ($employee && $employee->isLocked()) {
            $this->logAttempt($credentials['username'], $employee->employee_id, $ipAddress, $userAgent, false);

            $secondsLeft = max(1, now()->diffInSeconds($employee->locked_until, false));

            return back()
                ->withErrors(['username' => 'This account has been locked due to multiple failed login attempts.'])
                ->with('lockout_seconds', $secondsLeft)
                ->onlyInput('username');
        }

        $passwordValid = $employee && Hash::check($credentials['password'], $employee->password_hash);
        $accountActive = $employee && (! $employee->employee_status || strtolower($employee->employee_status) === 'active');

        if (! $passwordValid || ! $accountActive) {

            $this->logAttempt(
                $credentials['username'],
                $employee->employee_id ?? null,
                $ipAddress,
                $userAgent,
                false
            );

            // Existing account
            if ($employee) {

                $employee->failed_login_attempts++;
                $employee->save();

                $remainingAttempts = self::MAX_ATTEMPTS - $employee->failed_login_attempts;

                // Lock account after reaching max attempts
                if ($employee->failed_login_attempts >= self::MAX_ATTEMPTS) {

                    $employee->locked_until = now()->addMinutes(self::LOCKOUT_MINUTES);
                    $employee->save();

                    return back()
                        ->withErrors([
                            'username' => 'Too many failed login attempts. Your account has been locked for 15 minutes.',
                        ])
                        ->with('lockout_seconds', self::LOCKOUT_MINUTES * 60)
                        ->onlyInput('username');
                }

                return back()
                    ->withErrors([
                        'username' => self::GENERIC_ERROR,
                    ])
                    ->with('remaining_attempts', $remainingAttempts)
                    ->onlyInput('username');
            }

            // Username does not exist
            return back()
                ->withErrors([
                    'username' => self::GENERIC_ERROR,
                ])
                ->onlyInput('username');
        }

        // Successful login — clear any prior failed-attempt count and log it.
        $employee->forceFill([
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ])->save();

        $this->logAttempt($credentials['username'], $employee->employee_id, $ipAddress, $userAgent, true);

        // Prevent session fixation on every successful login.
        $request->session()->regenerate();

        $request->session()->put('employee_id', $employee->employee_id);
        $request->session()->put('employee_username', $employee->username);
        $request->session()->put('employee_role', $employee->role);
        $request->session()->put('employee_name', $employee->full_name);

        return redirect()->intended('/dashboard');
    }

    /**
     * Log the current employee out and return to the login screen.
     */
    public function logout(Request $request)
    {
        $request->session()->forget([
            'employee_id',
            'employee_username',
            'employee_role',
            'employee_name',
        ]);

        $request->session()->regenerate();

        return redirect('/');
    }

    /**
     * Record a login attempt (success or failure) for security auditing.
     */
    protected function logAttempt(string $usernameAttempted, ?int $employeeId, ?string $ipAddress, ?string $userAgent, bool $successful): void
    {
        // Keep authentication available if the audit migration has not yet
        // been applied. Logging resumes automatically once the table exists.
        if (! Schema::hasTable('login_attempts')) {
            return;
        }

        LoginAttempt::create([
            'username_attempted' => $usernameAttempted,
            'employee_id' => $employeeId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'successful' => $successful,
            'attempted_at' => now(),
        ]);
    }
}
