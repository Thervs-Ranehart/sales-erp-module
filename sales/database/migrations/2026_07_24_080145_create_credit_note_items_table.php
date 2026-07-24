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
        Schema::create('credit_note_items', function (Blueprint $table) {
            $table->increments('credit_note_item_id');
            $table->unsignedInteger('credit_note_id');
            $table->unsignedInteger('invoice_item_id');
            $table->integer('quantity');
            $table->decimal('amount', 15, 2);

            $table->foreign('credit_note_id')->references('credit_note_id')->on('credit_notes')->cascadeOnDelete();
            $table->foreign('invoice_item_id')->references('invoice_item_id')->on('invoice_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_note_items');
    }
};
