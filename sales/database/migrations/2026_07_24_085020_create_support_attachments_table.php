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
        Schema::create('support_attachments', function (Blueprint $table) {
            $table->increments('attachment_id');
            $table->unsignedInteger('ticket_id');
            $table->unsignedInteger('uploaded_by')->nullable();
            $table->string('original_name');
            $table->string('storage_path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->timestamp('created_at');
            $table->foreign('ticket_id')->references('ticket_id')->on('support_tickets');
            $table->foreign('uploaded_by')->references('employee_id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_attachments');
    }
};
