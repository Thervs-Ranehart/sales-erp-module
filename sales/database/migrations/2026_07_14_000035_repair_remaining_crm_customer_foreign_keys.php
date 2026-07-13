<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            $this->repairLoyaltyPrograms();
            $this->repairCustomerSegments();
            $this->repairCustomerBehaviorAnalysis();
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    private function repairLoyaltyPrograms(): void
    {
        if (! Schema::hasTable('loyalty_programs')) {
            return;
        }

        Schema::create('loyalty_programs_repaired', function (Blueprint $table) {
            $table->increments('loyalty_id');
            $table->unsignedInteger('customer_id')->unique();
            $table->string('membership_level')->nullable();
            $table->integer('points_earned')->nullable();
            $table->integer('points_redeemed')->nullable();
            $table->integer('available_points')->nullable();
            $table->date('enrollment_date')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('customer_id')
                ->references('customer_id')
                ->on('customers');
        });

        DB::statement(
            'INSERT INTO loyalty_programs_repaired (
                loyalty_id, customer_id, membership_level, points_earned,
                points_redeemed, available_points, enrollment_date,
                created_at, updated_at
            )
            SELECT
                loyalty_id, customer_id, membership_level, points_earned,
                points_redeemed, available_points, enrollment_date,
                created_at, updated_at
            FROM loyalty_programs'
        );

        Schema::drop('loyalty_programs');
        Schema::rename('loyalty_programs_repaired', 'loyalty_programs');
    }

    private function repairCustomerSegments(): void
    {
        if (! Schema::hasTable('customer_segments')) {
            return;
        }

        Schema::create('customer_segments_repaired', function (Blueprint $table) {
            $table->increments('segment_id');
            $table->unsignedInteger('customer_id');
            $table->string('segment_name')->nullable();
            $table->string('spending_category')->nullable();
            $table->string('purchase_frequency')->nullable();
            $table->timestamp('last_updated')->nullable();

            $table->foreign('customer_id')
                ->references('customer_id')
                ->on('customers');
        });

        DB::statement(
            'INSERT INTO customer_segments_repaired (
                segment_id, customer_id, segment_name,
                spending_category, purchase_frequency, last_updated
            )
            SELECT
                segment_id, customer_id, segment_name,
                spending_category, purchase_frequency, last_updated
            FROM customer_segments'
        );

        Schema::drop('customer_segments');
        Schema::rename('customer_segments_repaired', 'customer_segments');
    }

    private function repairCustomerBehaviorAnalysis(): void
    {
        if (! Schema::hasTable('customer_behavior_analysis')) {
            return;
        }

        Schema::create('customer_behavior_analysis_repaired', function (Blueprint $table) {
            $table->increments('analysis_id');
            $table->unsignedInteger('customer_id');
            $table->date('analysis_period_start')->nullable();
            $table->date('analysis_period_end')->nullable();
            $table->integer('total_orders')->nullable();
            $table->decimal('total_spent', 15, 2)->nullable();
            $table->decimal('average_order_value', 15, 2)->nullable();
            $table->string('favorite_product_category')->nullable();
            $table->decimal('customer_lifetime_value', 15, 2)->nullable();
            $table->timestamp('generated_at')->nullable();

            $table->foreign('customer_id')
                ->references('customer_id')
                ->on('customers');
        });

        DB::statement(
            'INSERT INTO customer_behavior_analysis_repaired (
                analysis_id, customer_id, analysis_period_start,
                analysis_period_end, total_orders, total_spent,
                average_order_value, favorite_product_category,
                customer_lifetime_value, generated_at
            )
            SELECT
                analysis_id, customer_id, analysis_period_start,
                analysis_period_end, total_orders, total_spent,
                average_order_value, favorite_product_category,
                customer_lifetime_value, generated_at
            FROM customer_behavior_analysis'
        );

        Schema::drop('customer_behavior_analysis');
        Schema::rename(
            'customer_behavior_analysis_repaired',
            'customer_behavior_analysis'
        );
    }

    public function down(): void
    {
        // This repair should not be reversed.
    }
};