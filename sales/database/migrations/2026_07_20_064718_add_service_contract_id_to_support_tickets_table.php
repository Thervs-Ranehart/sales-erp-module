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
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->unsignedInteger('service_contract_id')->nullable()->after('product_id');
            $table->foreign('service_contract_id')->references('contract_id')->on('service_contracts')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropForeign(['service_contract_id']);
            $table->dropColumn('service_contract_id');
        });
    }
};
