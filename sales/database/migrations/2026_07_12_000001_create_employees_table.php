<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('username')->unique();
            $table->string('password_hash');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('department')->nullable();
            $table->string('role')->nullable();
            $table->string('hierarchy_level')->nullable();
            $table->string('employee_status')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};