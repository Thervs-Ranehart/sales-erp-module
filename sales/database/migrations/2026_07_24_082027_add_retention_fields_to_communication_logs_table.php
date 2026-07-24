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
        Schema::table('communication_logs', function (Blueprint $table) {
            $table->string('priority')->default('Normal');
            $table->string('automation_key')->nullable()->unique();
            $table->string('recurrence')->nullable();
            $table->string('retention_outcome')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('communication_logs', function (Blueprint $table) {
            $table->dropColumn(['priority', 'automation_key', 'recurrence', 'retention_outcome']);
        });
    }
};
