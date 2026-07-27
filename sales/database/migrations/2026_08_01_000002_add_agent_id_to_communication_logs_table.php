<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('communication_logs', function (Blueprint $table) {
            $table->unsignedInteger('agent_id')->nullable()->after('employee_id');
            $table->foreign('agent_id')
                  ->references('agent_id')
                  ->on('agents')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('communication_logs', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn('agent_id');
        });
    }
};

