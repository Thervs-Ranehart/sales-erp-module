<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranty_claims', function (Blueprint $table) {
            $table->increments('claim_id');
            $table->unsignedInteger('warranty_id');
            $table->unsignedInteger('ticket_id');
            $table->text('claim_reason')->nullable();
            $table->string('claim_status')->nullable();
            $table->dateTime('claim_date')->nullable();
            $table->dateTime('approved_date')->nullable();

            $table->foreign('warranty_id')->references('warranty_id')->on('warranty_records');
            $table->foreign('ticket_id')->references('ticket_id')->on('support_tickets');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranty_claims');
    }
};
