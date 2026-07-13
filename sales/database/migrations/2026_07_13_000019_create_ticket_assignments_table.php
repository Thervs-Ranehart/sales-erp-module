<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_assignments', function (Blueprint $table) {
            $table->increments('assignment_id');
            $table->unsignedInteger('ticket_id');
            $table->unsignedInteger('employee_id');
            $table->dateTime('assigned_at')->nullable();
            $table->string('assignment_status')->nullable();

            $table->foreign('ticket_id')->references('ticket_id')->on('support_tickets');
            $table->foreign('employee_id')->references('employee_id')->on('employees');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_assignments');
    }
};
