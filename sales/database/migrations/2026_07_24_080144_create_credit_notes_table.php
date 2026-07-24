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
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->increments('credit_note_id');
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('approved_by')->nullable();
            $table->string('credit_note_number')->nullable()->unique();
            $table->string('status')->default('Issued');
            $table->text('reason');
            $table->decimal('amount', 15, 2)->default(0);
            $table->dateTime('issued_at')->nullable();
            $table->timestamps();

            $table->foreign('invoice_id')->references('invoice_id')->on('invoices');
            $table->foreign('created_by')->references('employee_id')->on('employees');
            $table->foreign('approved_by')->references('employee_id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_notes');
    }
};
