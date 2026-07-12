<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('satisfaction_monitoring', function (Blueprint $table) {
            $table->id('feedback_id');
            $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->integer('rating')->nullable();
            $table->string('satisfaction_level')->nullable();
            $table->text('comments')->nullable();
            $table->dateTime('submitted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('satisfaction_monitoring');
    }
};