<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('warranty_claims', function (Blueprint $table) {
            $table->id('claim_id');
            $table->foreignId('warranty_id')->constrained('warranty_records')->cascadeOnDelete();
            $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->text('claim_reason')->nullable();
            $table->string('claim_status')->nullable();
            $table->dateTime('claim_date')->nullable();
            $table->dateTime('approved_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranty_claims');
    }
};