<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technicians', function (Blueprint $table): void {
            $table->increments('technician_id');
            // Service requests currently store the assigned employee ID.
            $table->unsignedInteger('employee_id')->unique();
            $table->string('specialization')->nullable();
            $table->string('contact_number', 30)->nullable();
            $table->enum('availability_status', ['Available', 'Busy', 'On Leave', 'Inactive'])
                ->default('Available');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')
                ->references('employee_id')
                ->on('employees')
                ->cascadeOnDelete();
        });

        // Preserve existing service-request assignments as technician profiles.
        $now = now();
        $technicianIds = DB::table('service_requests')
            ->whereNotNull('technician_id')
            ->distinct()
            ->orderBy('technician_id')
            ->pluck('technician_id');

        foreach ($technicianIds as $technicianId) {
            DB::table('technicians')->insert([
                'employee_id' => $technicianId,
                'availability_status' => 'Available',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('technicians');
    }
};
