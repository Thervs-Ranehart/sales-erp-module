<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$pdo = DB::connection()->getPdo();

echo "Employees: " . DB::table('employees')->count() . PHP_EOL;
echo "Customers: " . DB::table('customers')->count() . PHP_EOL;

$tables = ['customers', 'customer_profiles', 'communication_logs', 'loyalty_programs', 'customer_segments', 'employees'];

foreach ($tables as $table) {
    echo "\n=== $table ===" . PHP_EOL;
    $cols = $pdo->query("PRAGMA table_info('$table')")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $col) {
        echo "  {$col['name']} {$col['type']} pk={$col['pk']}" . PHP_EOL;
    }
    $fks = $pdo->query("PRAGMA foreign_key_list('$table')")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($fks as $fk) {
        echo "  FK: {$fk['from']} -> {$fk['table']}.{$fk['to']}" . PHP_EOL;
    }
}
