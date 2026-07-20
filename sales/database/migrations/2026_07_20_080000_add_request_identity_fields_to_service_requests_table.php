<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table): void {
            $table->string('request_number')->nullable()->unique()->after('request_id');
            $table->dateTime('requested_at')->nullable()->after('request_type');
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table): void {
            $table->dropUnique(['request_number']);
            $table->dropColumn(['request_number', 'requested_at']);
        });
    }
};
