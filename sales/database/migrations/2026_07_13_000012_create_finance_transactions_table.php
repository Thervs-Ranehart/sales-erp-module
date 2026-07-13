<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->increments('finance_transaction_id');
            $table->unsignedInteger('invoice_id');
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->date('transaction_date')->nullable();

            $table->foreign('invoice_id')->references('invoice_id')->on('invoices');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
