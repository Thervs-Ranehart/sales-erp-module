<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->string('department')->default('After-Sales Support');
            $table->dateTime('first_response_due_at')->nullable();
            $table->dateTime('resolution_due_at')->nullable();
            $table->unsignedInteger('escalation_level')->default(0);
            $table->dateTime('last_escalated_at')->nullable();
            $table->dateTime('archived_at')->nullable();
            $table->text('archive_reason')->nullable();
        });
        Schema::table('ticket_assignments', function (Blueprint $table) {
            $table->string('department')->nullable();
            $table->text('assignment_reason')->nullable();
        });
        Schema::table('warranty_records', function (Blueprint $table) {
            $table->dateTime('archived_at')->nullable();
            $table->text('archive_reason')->nullable();
        });
        Schema::table('warranty_claims', function (Blueprint $table) {
            $table->string('eligibility_status')->nullable();
            $table->text('eligibility_notes')->nullable();
            $table->text('decision_reason')->nullable();
            $table->dateTime('cancelled_at')->nullable();
        });
        Schema::table('service_contracts', function (Blueprint $table) {
            $table->unsignedInteger('service_limit')->nullable();
            $table->unsignedInteger('services_used')->default(0);
            $table->dateTime('archived_at')->nullable();
            $table->text('archive_reason')->nullable();
        });
        Schema::table('service_requests', function (Blueprint $table) {
            $table->text('service_result')->nullable();
            $table->dateTime('cancelled_at')->nullable();
        });
        Schema::table('resolution_tracking', function (Blueprint $table) {
            $table->string('resolution_status')->default('Draft');
            $table->unsignedInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
        });
        Schema::table('satisfaction_monitoring', function (Blueprint $table) {
            $table->string('survey_token')->nullable()->unique();
            $table->dateTime('requested_at')->nullable();
        });
        Schema::create('support_case_events', function (Blueprint $table) {
            $table->increments('event_id');
            $table->unsignedInteger('ticket_id');
            $table->unsignedInteger('employee_id')->nullable();
            $table->string('event_type');
            $table->text('description');
            $table->dateTime('created_at');
            $table->foreign('ticket_id')->references('ticket_id')->on('support_tickets');
            $table->foreign('employee_id')->references('employee_id')->on('employees');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_case_events');
        Schema::table('satisfaction_monitoring', fn (Blueprint $table) => $table->dropColumn(['survey_token', 'requested_at']));
        Schema::table('resolution_tracking', fn (Blueprint $table) => $table->dropColumn(['resolution_status', 'approved_by', 'approved_at']));
        Schema::table('service_requests', fn (Blueprint $table) => $table->dropColumn(['service_result', 'cancelled_at']));
        Schema::table('service_contracts', fn (Blueprint $table) => $table->dropColumn(['service_limit', 'services_used', 'archived_at', 'archive_reason']));
        Schema::table('warranty_claims', fn (Blueprint $table) => $table->dropColumn(['eligibility_status', 'eligibility_notes', 'decision_reason', 'cancelled_at']));
        Schema::table('warranty_records', fn (Blueprint $table) => $table->dropColumn(['archived_at', 'archive_reason']));
        Schema::table('ticket_assignments', fn (Blueprint $table) => $table->dropColumn(['department', 'assignment_reason']));
        Schema::table('support_tickets', fn (Blueprint $table) => $table->dropColumn([
            'department', 'first_response_due_at', 'resolution_due_at', 'escalation_level',
            'last_escalated_at', 'archived_at', 'archive_reason',
        ]));
    }
};
