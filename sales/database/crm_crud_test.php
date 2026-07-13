<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Customer;
use App\Models\CustomerProfile;
use App\Models\CommunicationLog;
use App\Models\LoyaltyProgram;
use App\Models\CustomerSegment;
use App\Models\CustomerBehaviorAnalysis;
use Illuminate\Support\Facades\DB;

function out(string $message): void
{
    echo $message . PHP_EOL;
}

out('=== CRM CRUD Test ===');
out('Customers before: ' . Customer::count());

$email = 'crmtest_' . time() . '@example.com';
$customer = Customer::create([
    'first_name' => 'CRM',
    'last_name' => 'TestUser',
    'email' => $email,
    'contact_no' => '09998887777',
    'address' => 'Test Address',
]);
out('CREATE ok - ID: ' . $customer->customer_id);

$read = Customer::find($customer->customer_id);
out('READ ok - ' . $read->display_name);

$read->update(['first_name' => 'CRMUpdated']);
out('UPDATE ok - ' . $read->fresh()->first_name);

CustomerProfile::updateOrCreate(
    ['customer_id' => $customer->customer_id],
    ['gender' => 'Male', 'marketing_consent' => true]
);
out('PROFILE ok');

$empId = DB::table('employees')->min('employee_id');
if (! $empId) {
    $empId = DB::table('employees')->insertGetId([
        'username' => 'crm.system',
        'password_hash' => bcrypt('password'),
        'first_name' => 'CRM',
        'last_name' => 'System',
        'department' => 'CRM',
        'role' => 'Staff',
        'employee_status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
CommunicationLog::create([
    'customer_id' => $customer->customer_id,
    'employee_id' => $empId,
    'communication_date' => now(),
    'communication_channel' => 'Email',
    'subject' => 'Test log',
    'communication_status' => 'Active',
]);
out('LOG ok');

LoyaltyProgram::updateOrCreate(
    ['customer_id' => $customer->customer_id],
    ['membership_level' => 'Regular', 'available_points' => 100, 'enrollment_date' => now()->toDateString()]
);
out('LOYALTY ok');

CustomerSegment::create([
    'customer_id' => $customer->customer_id,
    'segment_name' => 'Regular',
    'last_updated' => now(),
]);
out('SEGMENT ok');

try {
    DB::transaction(function () use ($customer) {
        $cust = Customer::findOrFail($customer->customer_id);
        $cust->profile()->delete();
        $cust->communicationLogs()->delete();
        $cust->loyaltyProgram()->delete();
        $cust->segments()->delete();
        $cust->behaviorAnalyses()->delete();
        $cust->delete();
    });
    out('DELETE ok');
} catch (Throwable $e) {
    out('DELETE FAILED: ' . $e->getMessage());
    exit(1);
}

out('Exists after delete: ' . (Customer::find($customer->customer_id) ? 'yes' : 'no'));
out('=== All CRM CRUD tests passed ===');
