<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('communication_logs', function (Blueprint $table) {
            $table->id('communication_id');
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->dateTime('communication_date')->nullable();
            $table->string('communication_channel')->nullable();
            $table->string('subject')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('follow_up_date')->nullable();
            $table->string('communication_status')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communication_logs');
    }
};