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
        Schema::table('user_academics', function (Blueprint $table) {
            $table->year('attended_from')->nullable()->after('school_id');
            $table->year('attended_to')->nullable()->after('attended_from');
            $table->string('units_earned', 150)->nullable()->after('graduated_at');
            $table->string('honors', 255)->nullable()->after('units_earned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_academics', function (Blueprint $table) {
            $table->dropColumn(['attended_from', 'attended_to', 'units_earned', 'honors']);
        });
    }
};
