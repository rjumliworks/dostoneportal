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
        Schema::table('user_information', function (Blueprint $table) {
            // Groups height/weight/citizenship/citizenship_type/citizenship_country/
            // place_of_birth/agency_employee_no, same pattern as accounts/backgrounds/contacts.
            $table->longText('personal')->nullable()->after('backgrounds');
        });

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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->string('citizenship', 20)->nullable();
            $table->string('citizenship_type', 30)->nullable();
            $table->string('citizenship_country', 100)->nullable();
            $table->text('place_of_birth')->nullable();
            $table->string('agency_employee_no', 50)->nullable();
        });

        Schema::table('user_information', function (Blueprint $table) {
            $table->dropColumn(['personal']);
        });
    }
};
