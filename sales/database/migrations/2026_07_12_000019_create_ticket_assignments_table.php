<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ticket_assignments', function (Blueprint $table) {
            $table->id('assignment_id');
            $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->dateTime('assigned_at')->nullable();
            $table->string('assignment_status')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_assignments');
    }
};