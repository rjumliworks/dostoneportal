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
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->decimal('height', 5, 2)->nullable()->after('blood_id');
            $table->decimal('weight', 5, 2)->nullable()->after('height');
            $table->string('citizenship', 20)->nullable()->after('weight');
            $table->string('citizenship_type', 30)->nullable()->after('citizenship');
            $table->string('citizenship_country', 100)->nullable()->after('citizenship_type');
            $table->text('place_of_birth')->nullable()->after('citizenship_country');
            $table->string('agency_employee_no', 50)->nullable()->after('place_of_birth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'height',
                'weight',
                'citizenship',
                'citizenship_type',
                'citizenship_country',
                'place_of_birth',
                'agency_employee_no',
            ]);
        });
    }
};
