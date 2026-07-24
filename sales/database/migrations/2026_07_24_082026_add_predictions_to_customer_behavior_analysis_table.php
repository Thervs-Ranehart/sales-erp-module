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
        Schema::table('customer_behavior_analysis', function (Blueprint $table) {
            $table->string('spending_trend')->nullable();
            $table->decimal('trend_percentage', 8, 2)->nullable();
            $table->date('predicted_next_purchase')->nullable();
            $table->decimal('churn_risk_score', 5, 2)->nullable();
            $table->decimal('predicted_90_day_value', 15, 2)->nullable();
            $table->string('recommended_product_category')->nullable();
            $table->text('retention_recommendation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_behavior_analysis', function (Blueprint $table) {
            $table->dropColumn([
                'spending_trend', 'trend_percentage', 'predicted_next_purchase',
                'churn_risk_score', 'predicted_90_day_value',
                'recommended_product_category', 'retention_recommendation',
            ]);
        });
    }
};
