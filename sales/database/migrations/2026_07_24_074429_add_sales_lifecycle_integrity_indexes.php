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
        if (
            Schema::hasTable('sales_orders')
            && ! Schema::hasIndex('sales_orders', ['quotation_id'], 'unique')
        ) {
            Schema::table('sales_orders', function (Blueprint $table): void {
                $table->unique('quotation_id', 'sales_orders_quotation_id_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('sales_orders')
            && Schema::hasIndex('sales_orders', 'sales_orders_quotation_id_unique')
        ) {
            Schema::table('sales_orders', function (Blueprint $table): void {
                $table->dropUnique('sales_orders_quotation_id_unique');
            });
        }
    }
};
