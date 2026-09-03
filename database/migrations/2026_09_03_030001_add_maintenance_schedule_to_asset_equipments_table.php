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
        Schema::table('asset_equipments', function (Blueprint $table) {
            $table->json('maintenance_schedule')->nullable()->after('maintenance_due');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_equipments', function (Blueprint $table) {
            $table->dropColumn('maintenance_schedule');
        });
    }
};
