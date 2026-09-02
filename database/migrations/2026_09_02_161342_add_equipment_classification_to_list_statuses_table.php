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
        DB::table('list_statuses')->insert([
            ['name' => 'Serviceable', 'classification' => 'Equipment', 'type' => 'n/a', 'color' => 'text-white', 'bg' => 'bg-success', 'icon' => 'n/a', 'is_active' => 1],
            ['name' => 'Under Repair', 'classification' => 'Equipment', 'type' => 'n/a', 'color' => 'text-white', 'bg' => 'bg-warning', 'icon' => 'n/a', 'is_active' => 1],
            ['name' => 'Unserviceable', 'classification' => 'Equipment', 'type' => 'n/a', 'color' => 'text-white', 'bg' => 'bg-danger', 'icon' => 'n/a', 'is_active' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('list_statuses')->where('classification', 'Equipment')->delete();
    }
};
