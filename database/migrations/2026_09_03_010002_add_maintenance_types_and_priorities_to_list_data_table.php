<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('list_data')->insert([
            ['name' => 'Preventive Maintenance', 'type' => 'Maintenance Type', 'is_active' => 1],
            ['name' => 'Corrective Maintenance', 'type' => 'Maintenance Type', 'is_active' => 1],
            ['name' => 'Calibration', 'type' => 'Maintenance Type', 'is_active' => 1],
            ['name' => 'Repair', 'type' => 'Maintenance Type', 'is_active' => 1],
            ['name' => 'Inspection', 'type' => 'Maintenance Type', 'is_active' => 1],

            ['name' => 'Low', 'type' => 'Priority', 'is_active' => 1],
            ['name' => 'Medium', 'type' => 'Priority', 'is_active' => 1],
            ['name' => 'High', 'type' => 'Priority', 'is_active' => 1],
            ['name' => 'Urgent', 'type' => 'Priority', 'is_active' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('list_data')->whereIn('type', ['Maintenance Type', 'Priority'])->delete();
    }
};
