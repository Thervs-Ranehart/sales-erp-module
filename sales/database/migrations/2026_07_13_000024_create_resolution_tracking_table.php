<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resolution_tracking', function (Blueprint $table) {
            $table->increments('resolution_id');
            $table->unsignedInteger('ticket_id');
            $table->unsignedInteger('resolved_by');
            $table->text('resolution_summary')->nullable();
            $table->text('root_cause')->nullable();
            $table->text('corrective_action')->nullable();
            $table->decimal('resolution_time_hours', 15, 2)->nullable();
            $table->dateTime('resolved_at')->nullable();

            $table->foreign('ticket_id')->references('ticket_id')->on('support_tickets');
            $table->foreign('resolved_by')->references('employee_id')->on('employees');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resolution_tracking');
    }
};
