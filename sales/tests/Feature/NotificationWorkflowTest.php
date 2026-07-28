<?php

use App\Models\Employee;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('employees can view and mark only their notifications as read', function (): void {
    $employee = Employee::query()->create([
        'username' => 'notification-user',
        'password_hash' => password_hash('password', PASSWORD_BCRYPT),
        'first_name' => 'Notification',
        'last_name' => 'User',
        'department' => 'Sales',
        'role' => 'Sales Representative',
        'employee_status' => 'Active',
    ]);
    $otherEmployee = Employee::query()->create([
        'username' => 'other-notification-user',
        'password_hash' => password_hash('password', PASSWORD_BCRYPT),
        'first_name' => 'Other',
        'last_name' => 'User',
        'department' => 'Sales',
        'role' => 'Sales Representative',
        'employee_status' => 'Active',
    ]);
    $notification = Notification::query()->create([
        'employee_id' => $employee->employee_id,
        'notification_type' => 'Support SLA',
        'title' => 'Assigned notification',
        'message' => 'This notification belongs to the signed-in employee.',
        'is_read' => false,
        'created_at' => now(),
    ]);
    Notification::query()->create([
        'employee_id' => $otherEmployee->employee_id,
        'notification_type' => 'Support SLA',
        'title' => 'Other employee notification',
        'message' => 'This must remain private.',
        'is_read' => false,
        'created_at' => now(),
    ]);

    $this->withSession(['employee_id' => $employee->employee_id])
        ->get(route('notifications.index'))
        ->assertOk()
        ->assertSee('Assigned notification')
        ->assertDontSee('Other employee notification');

    $this->withSession(['employee_id' => $employee->employee_id])
        ->patch(route('notifications.read', $notification))
        ->assertRedirect();

    expect($notification->fresh()->is_read)->toBeTrue();
});
