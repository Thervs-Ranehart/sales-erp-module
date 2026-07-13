<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('communication_logs', function (Blueprint $table) {
            $table->increments('communication_id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('employee_id');
            $table->dateTime('communication_date')->nullable();
            $table->string('communication_channel')->nullable();
            $table->string('subject')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('follow_up_date')->nullable();
            $table->string('communication_status')->nullable();

            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('employee_id')->references('employee_id')->on('employees');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communication_logs');
    }
};
