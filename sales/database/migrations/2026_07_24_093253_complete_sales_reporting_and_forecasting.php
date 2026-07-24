<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_regions', function (Blueprint $table) {
            $table->increments('region_id');
            $table->string('region_code')->unique();
            $table->string('region_name');
            $table->string('country')->default('Philippines');
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedInteger('region_id')->nullable();
        });

        Schema::table('sales_forecasts', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(1);
            $table->text('assumptions')->nullable();
            $table->unsignedInteger('sample_size')->default(0);
            $table->decimal('prediction_lower', 15, 2)->nullable();
            $table->decimal('prediction_upper', 15, 2)->nullable();
            $table->decimal('actual_revenue', 15, 2)->nullable();
            $table->decimal('mae', 15, 2)->nullable();
            $table->decimal('mape', 15, 4)->nullable();
            $table->decimal('rmse', 15, 2)->nullable();
            $table->string('forecast_status')->default('Generated');
        });

        Schema::table('forecast_recommendations', function (Blueprint $table) {
            $table->unsignedInteger('assigned_to')->nullable();
            $table->string('assigned_department')->nullable();
            $table->unsignedInteger('reviewed_by')->nullable();
            $table->dateTime('reviewed_at')->nullable();
            $table->date('due_date')->nullable();
            $table->text('evidence')->nullable();
            $table->text('decision_notes')->nullable();
            $table->text('outcome')->nullable();
            $table->dateTime('completed_at')->nullable();
        });

        Schema::create('planning_actions', function (Blueprint $table) {
            $table->increments('planning_action_id');
            $table->unsignedInteger('recommendation_id');
            $table->string('action_type');
            $table->string('title');
            $table->unsignedInteger('assigned_to')->nullable();
            $table->string('assigned_department')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status')->default('Open');
            $table->timestamps();
            $table->foreign('recommendation_id')->references('recommendation_id')->on('forecast_recommendations');
        });

        Schema::create('forecast_workflow_events', function (Blueprint $table) {
            $table->increments('event_id');
            $table->string('subject_type');
            $table->unsignedInteger('subject_id');
            $table->unsignedInteger('employee_id')->nullable();
            $table->string('event_type');
            $table->text('description');
            $table->timestamp('created_at');
            $table->index(['subject_type', 'subject_id']);
        });

        Schema::create('forecast_export_logs', function (Blueprint $table) {
            $table->increments('export_id');
            $table->unsignedInteger('employee_id')->nullable();
            $table->string('export_type');
            $table->string('format');
            $table->text('filters')->nullable();
            $table->timestamp('exported_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecast_export_logs');
        Schema::dropIfExists('forecast_workflow_events');
        Schema::dropIfExists('planning_actions');
        Schema::table('forecast_recommendations', fn (Blueprint $table) => $table->dropColumn([
            'assigned_to', 'assigned_department', 'reviewed_by', 'reviewed_at', 'due_date',
            'evidence', 'decision_notes', 'outcome', 'completed_at',
        ]));
        Schema::table('sales_forecasts', fn (Blueprint $table) => $table->dropColumn([
            'version', 'assumptions', 'sample_size', 'prediction_lower', 'prediction_upper',
            'actual_revenue', 'mae', 'mape', 'rmse', 'forecast_status',
        ]));
        Schema::table('customers', fn (Blueprint $table) => $table->dropColumn('region_id'));
        Schema::dropIfExists('sales_regions');
    }
};
