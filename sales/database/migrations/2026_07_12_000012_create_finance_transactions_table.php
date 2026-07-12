<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id('finance_transaction_id');
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->date('transaction_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};