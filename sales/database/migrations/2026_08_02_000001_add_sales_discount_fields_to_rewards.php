<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rewards', function (Blueprint $table): void {
            $table->string('discount_type')->default('Fixed')->after('points_required');
            $table->decimal('discount_value', 12, 2)->default(0)->after('discount_type');
        });

        Schema::table('reward_redemptions', function (Blueprint $table): void {
            $table->unsignedInteger('order_id')->nullable()->after('reward_id');
            $table->foreign('order_id')->references('order_id')->on('sales_orders')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reward_redemptions', function (Blueprint $table): void {
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
        });

        Schema::table('rewards', function (Blueprint $table): void {
            $table->dropColumn(['discount_type', 'discount_value']);
        });
    }
};
