<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // NOTE: migration 2026_07_14_000037_add_lockout_columns_to_employees_table
        // already adds these same two columns. This migration duplicated that
        // work and would fail with a "duplicate column" error if run after it.
        // Guarded here so it's a safe no-op in that case, but still works on
        // its own if 037 is ever removed.
        Schema::table('employees', function (Blueprint $table) {
            if (! Schema::hasColumn('employees', 'failed_login_attempts')) {
                $table->unsignedInteger('failed_login_attempts')
                      ->default(0)
                      ->after('employee_status');
            }

            if (! Schema::hasColumn('employees', 'locked_until')) {
                $table->timestamp('locked_until')
                      ->nullable()
                      ->after('failed_login_attempts');
            }
        });
    }

    public function down(): void
    {
        // Left as a no-op: dropping these here would also break the down()
        // of migration 037, which owns these columns. Roll back 037 instead
        // if you need to remove failed_login_attempts / locked_until.
    }
};