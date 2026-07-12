<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->string('request_type')->nullable();
            $table->dateTime('scheduled_date')->nullable();
            $table->dateTime('completion_date')->nullable();
            $table->string('service_status')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};