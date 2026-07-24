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
        Schema::create('sales_approvals', function (Blueprint $table) {
            $table->increments('approval_id');
            $table->unsignedInteger('requested_by');
            $table->unsignedInteger('reviewed_by')->nullable();
            $table->string('approvable_type');
            $table->unsignedInteger('approvable_id');
            $table->string('action');
            $table->string('status')->default('Pending');
            $table->text('reason');
            $table->text('review_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->foreign('requested_by')->references('employee_id')->on('employees');
            $table->foreign('reviewed_by')->references('employee_id')->on('employees');
            $table->index(['approvable_type', 'approvable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_approvals');
    }
};
