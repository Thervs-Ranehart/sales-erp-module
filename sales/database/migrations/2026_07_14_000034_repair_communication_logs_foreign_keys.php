<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            Schema::create('communication_logs_repaired', function (Blueprint $table) {
                $table->increments('communication_id');
                $table->unsignedInteger('customer_id');
                $table->unsignedInteger('employee_id');
                $table->dateTime('communication_date')->nullable();
                $table->string('communication_channel')->nullable();
                $table->string('subject')->nullable();
                $table->text('notes')->nullable();
                $table->dateTime('follow_up_date')->nullable();
                $table->string('communication_status')->nullable();

                $table->foreign('customer_id')
                    ->references('customer_id')
                    ->on('customers');

                $table->foreign('employee_id')
                    ->references('employee_id')
                    ->on('employees');
            });

            DB::statement(
                'INSERT INTO communication_logs_repaired (
                    communication_id,
                    customer_id,
                    employee_id,
                    communication_date,
                    communication_channel,
                    subject,
                    notes,
                    follow_up_date,
                    communication_status
                )
                SELECT
                    communication_id,
                    customer_id,
                    employee_id,
                    communication_date,
                    communication_channel,
                    subject,
                    notes,
                    follow_up_date,
                    communication_status
                FROM communication_logs'
            );

            Schema::dropIfExists('communication_logs');
            Schema::rename('communication_logs_repaired', 'communication_logs');
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        // This repair should not be reversed.
    }
};