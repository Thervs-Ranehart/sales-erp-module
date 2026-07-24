<?php

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Models\ResolutionTracking;
use App\Models\SalesOrder;
use App\Models\SupportTicket;
use App\Models\WarrantyRecord;
use App\Services\AfterSalesAutomationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->employee = Employee::query()->create([
        'username' => 'support-manager',
        'password_hash' => password_hash('password', PASSWORD_BCRYPT),
        'first_name' => 'Support',
        'last_name' => 'Manager',
        'department' => 'After-Sales Support',
        'role' => 'Manager',
        'employee_status' => 'Active',
    ]);
    $this->customer = Customer::query()->create([
        'first_name' => 'After',
        'last_name' => 'Sales',
        'email' => 'after-sales@example.test',
    ]);
    $this->product = Product::query()->create([
        'product_name' => 'Covered Product',
        'category' => 'Hardware',
        'unit_price' => 1000,
        'stock_quantity' => 10,
        'product_status' => 'Active',
    ]);
    $this->order = SalesOrder::query()->create([
        'order_number' => 'SO-SUPPORT-001',
        'customer_id' => $this->customer->customer_id,
        'employee_id' => $this->employee->employee_id,
        'order_date' => now(),
        'order_status' => 'delivered',
        'subtotal' => 1000,
        'discount' => 0,
        'tax' => 0,
        'shipping_fee' => 0,
        'total_amount' => 1000,
    ]);
    $this->order->items()->create([
        'product_id' => $this->product->product_id,
        'quantity' => 1,
        'unit_price' => 1000,
        'discount' => 0,
        'subtotal' => 1000,
    ]);
    $this->withSession(['employee_id' => $this->employee->employee_id, 'employee_role' => 'Manager']);
});

test('staff can create archive and restore a ticket with SLA deadlines', function (): void {
    $this->post(route('support.tickets.store'), [
        'order_id' => $this->order->order_id,
        'product_id' => $this->product->product_id,
        'ticket_type' => 'Technical',
        'subject' => 'Product requires support',
        'description' => 'Customer reported a repeatable issue.',
        'priority' => 'High',
        'department' => 'Technical Support',
    ])->assertRedirect();

    $ticket = SupportTicket::query()->firstOrFail();
    expect($ticket->customer_id)->toBe($this->customer->customer_id)
        ->and($ticket->resolution_due_at)->not->toBeNull()
        ->and($ticket->caseEvents()->count())->toBe(1);

    $this->patch(route('support.tickets.archive', $ticket), ['archive_reason' => 'Duplicate case'])->assertRedirect();
    expect($ticket->fresh()->archived_at)->not->toBeNull();
    $this->patch(route('support.tickets.restore', $ticket))->assertRedirect();
    expect($ticket->fresh()->archived_at)->toBeNull();
});

test('warranty claims are validated and approved claims create service requests', function (): void {
    $ticket = createSupportTicket($this);
    $warranty = WarrantyRecord::query()->create([
        'order_id' => $this->order->order_id,
        'product_id' => $this->product->product_id,
        'warranty_number' => 'WR-TEST-001',
        'warranty_start' => today()->subMonth(),
        'warranty_end' => today()->addYear(),
        'warranty_status' => 'Active',
    ]);

    $this->post(route('support.warranty-claims.store'), [
        'warranty_id' => $warranty->warranty_id,
        'ticket_id' => $ticket->ticket_id,
        'claim_reason' => 'Covered product failed.',
    ])->assertRedirect();

    $claim = $warranty->warrantyClaims()->firstOrFail();
    expect($claim->eligibility_status)->toBe('Eligible');
    $this->post(route('support.warranty-claims.status', $claim), ['claim_status' => 'Approved'])->assertRedirect();
    $this->assertDatabaseHas('service_requests', ['ticket_id' => $ticket->ticket_id, 'request_type' => 'Approved Warranty Claim']);
});

test('approving a resolution closes the operational loop and requests feedback', function (): void {
    $ticket = createSupportTicket($this);
    $resolution = ResolutionTracking::query()->create([
        'ticket_id' => $ticket->ticket_id,
        'resolved_by' => $this->employee->employee_id,
        'resolution_summary' => 'Replaced component.',
        'root_cause' => 'Component failure.',
        'corrective_action' => 'Installed replacement.',
        'resolution_time_hours' => 2,
        'qc_status' => 'pending',
        'resolution_status' => 'Draft',
    ]);

    $this->patch(route('support.resolution-tracking.approve', $resolution))->assertRedirect();

    expect($resolution->fresh()->resolution_status)->toBe('Approved')
        ->and($ticket->fresh()->status)->toBe('Resolved');
    $this->assertDatabaseHas('satisfaction_monitoring', ['ticket_id' => $ticket->ticket_id]);
});

test('overdue tickets are escalated once per automation run and employees are notified', function (): void {
    $ticket = createSupportTicket($this);
    $ticket->update(['resolution_due_at' => now()->subHour()]);

    expect(app(AfterSalesAutomationService::class)->escalateBreaches())->toBe(1);
    expect($ticket->fresh()->status)->toBe('Escalated')
        ->and($ticket->fresh()->escalation_level)->toBe(1);
    $this->assertDatabaseHas('notifications', ['related_record_id' => $ticket->ticket_id, 'notification_type' => 'Support SLA']);
});

test('case evidence can be attached and audited', function (): void {
    Storage::fake('public');
    $ticket = createSupportTicket($this);

    $this->post(route('support.tickets.attachments.store', $ticket), [
        'attachment' => UploadedFile::fake()->create('evidence.pdf', 120, 'application/pdf'),
    ])->assertRedirect();

    $attachment = $ticket->attachments()->firstOrFail();
    Storage::disk('public')->assertExists($attachment->storage_path);
    $this->assertDatabaseHas('support_case_events', ['ticket_id' => $ticket->ticket_id, 'event_type' => 'Attachment Added']);
});

function createSupportTicket($test): SupportTicket
{
    return SupportTicket::query()->create([
        'order_id' => $test->order->order_id,
        'customer_id' => $test->customer->customer_id,
        'product_id' => $test->product->product_id,
        'ticket_type' => 'Technical',
        'subject' => 'Support case',
        'description' => 'Needs service.',
        'priority' => 'High',
        'status' => 'Open',
        'department' => 'After-Sales Support',
        'created_at' => now(),
        'resolution_due_at' => now()->addHours(8),
    ]);
}
