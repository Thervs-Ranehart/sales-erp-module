<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table): void {
            $table->unsignedInteger('technician_id')->nullable()->after('ticket_id');
            $table->dateTime('scheduled_end')->nullable()->after('scheduled_date');
            $table->text('schedule_notes')->nullable()->after('scheduled_end');

            $table->foreign('technician_id')
                ->references('employee_id')
                ->on('employees')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table): void {
            $table->dropForeign(['technician_id']);
            $table->dropColumn(['technician_id', 'scheduled_end', 'schedule_notes']);
        });
    }
};
