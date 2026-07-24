<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loyalty_point_transactions', function (Blueprint $table) {
            $table->increments('point_transaction_id');
            $table->unsignedInteger('loyalty_id');
            $table->unsignedInteger('employee_id')->nullable();
            $table->string('transaction_type');
            $table->integer('points');
            $table->integer('balance_after');
            $table->string('source_type')->nullable();
            $table->unsignedInteger('source_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreign('loyalty_id')->references('loyalty_id')->on('loyalty_programs');
            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->unique(['loyalty_id', 'transaction_type', 'source_type', 'source_id'], 'loyalty_source_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_point_transactions');
    }
};
