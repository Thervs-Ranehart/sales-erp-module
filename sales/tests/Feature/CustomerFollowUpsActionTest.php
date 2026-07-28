<?php

use App\Models\Agent;
use App\Models\CommunicationLog;
use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $employee = Employee::query()->create([
        'username' => 'follow-up-user',
        'password_hash' => password_hash('password', PASSWORD_BCRYPT),
        'first_name' => 'Follow',
        'last_name' => 'Up',
        'department' => 'CRM',
        'role' => 'Manager',
        'employee_status' => 'Active',
    ]);

    $this->customer = Customer::query()->create([
        'first_name' => 'Follow',
        'last_name' => 'Customer',
        'email' => 'follow-up@example.test',
        'customer_status' => 'Active',
    ]);

    $this->agent = Agent::query()->create([
        'first_name' => 'Assigned',
        'last_name' => 'Agent',
        'status' => 'Active',
    ]);

    $this->followUp = CommunicationLog::query()->create([
        'customer_id' => $this->customer->customer_id,
        'employee_id' => $employee->employee_id,
        'agent_id' => $this->agent->agent_id,
        'communication_date' => now(),
        'communication_channel' => 'Email',
        'subject' => 'Initial follow-up',
        'follow_up_date' => today()->addDay(),
        'priority' => 'Medium',
        'communication_status' => 'Pending',
    ]);
});

test('follow-ups can be edited and deleted from their action controls', function (): void {
    $this->put(route('crm.followups.update', $this->followUp), [
        'customer_id' => $this->customer->customer_id,
        'agent_id' => $this->agent->agent_id,
        'communication_channel' => 'Phone',
        'subject' => 'Updated follow-up',
        'notes' => 'Call in the afternoon.',
        'follow_up_date' => today()->addDays(2)->toDateString(),
        'priority' => 'High',
        'communication_status' => 'Completed',
    ])->assertRedirect();

    $this->assertDatabaseHas('communication_logs', [
        'communication_id' => $this->followUp->communication_id,
        'subject' => 'Updated follow-up',
        'communication_status' => 'Completed',
        'priority' => 'High',
    ]);

    $this->delete(route('crm.followups.destroy', $this->followUp))->assertRedirect();

    $this->assertDatabaseMissing('communication_logs', [
        'communication_id' => $this->followUp->communication_id,
    ]);
});
