<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resolution_tracking', function (Blueprint $table) {
            $table->id('resolution_id');
            $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->foreignId('resolved_by')->constrained('employees')->cascadeOnDelete();
            $table->text('resolution_summary')->nullable();
            $table->text('root_cause')->nullable();
            $table->text('corrective_action')->nullable();
            $table->decimal('resolution_time_hours', 8, 2)->default(0);
            $table->dateTime('resolved_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resolution_tracking');
    }
};