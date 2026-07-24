<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_status')->default('Active')->after('preferences');
            $table->timestamp('archived_at')->nullable()->after('customer_status');
            $table->unsignedInteger('archived_by')->nullable()->after('archived_at');
            $table->text('archive_reason')->nullable()->after('archived_by');
        });

        Schema::table('customer_profiles', function (Blueprint $table) {
            $table->text('preferences')->nullable()->after('preferred_product_category');
        });

        DB::statement(
            'UPDATE customer_profiles
             SET preferences = (
                 SELECT customers.preferences
                 FROM customers
                 WHERE customers.customer_id = customer_profiles.customer_id
             )
             WHERE preferences IS NULL'
        );
    }

    public function down(): void
    {
        Schema::table('customer_profiles', function (Blueprint $table) {
            $table->dropColumn('preferences');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['customer_status', 'archived_at', 'archived_by', 'archive_reason']);
        });
    }
};
