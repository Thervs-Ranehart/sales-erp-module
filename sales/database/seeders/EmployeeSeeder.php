<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * The fixed set of system accounts for this ERP.
     * Password for every account below is "123" (hashed on seed).
     */
    public function run(): void
    {
        $accounts = [
            ['username' => 'admin',          'role' => 'Admin',           'department' => 'Administration'],
            ['username' => 'salesmanager',   'role' => 'Sales Manager',   'department' => 'Sales'],
            ['username' => 'salesrep',       'role' => 'Sales Rep',       'department' => 'Sales'],
            ['username' => 'finance',        'role' => 'Finance',         'department' => 'Finance'],
            ['username' => 'financemanager', 'role' => 'Finance Manager', 'department' => 'Finance'],
            ['username' => 'warehouse',      'role' => 'Warehouse',       'department' => 'Warehouse'],
            ['username' => 'procurement',    'role' => 'Procurement',     'department' => 'Procurement'],
            ['username' => 'support',        'role' => 'Support',         'department' => 'After-Sales Support'],
            ['username' => 'operations',     'role' => 'Operations',      'department' => 'Operations'],
            ['username' => 'hr',             'role' => 'HR',              'department' => 'Human Resources'],
        ];

        foreach ($accounts as $account) {
            // updateOrCreate so re-running the seeder never duplicates accounts
            // and safely resets the password if it ever needs to change.
            Employee::updateOrCreate(
                ['username' => $account['username']],
                [
                    'password_hash' => Hash::make('123'),
                    'first_name' => ucfirst($account['username']),
                    'last_name' => '',
                    'department' => $account['department'],
                    'role' => $account['role'],
                    'hierarchy_level' => null,
                    'employee_status' => 'Active',
                ]
            );
        }
    }
}