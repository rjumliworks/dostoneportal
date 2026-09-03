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
            ['name' => 'Pending', 'classification' => 'Maintenance Record', 'type' => 'n/a', 'color' => 'text-dark', 'bg' => 'bg-secondary-subtle', 'icon' => 'n/a', 'is_active' => 1],
            ['name' => 'Ongoing', 'classification' => 'Maintenance Record', 'type' => 'n/a', 'color' => 'text-white', 'bg' => 'bg-warning', 'icon' => 'n/a', 'is_active' => 1],
            ['name' => 'Completed', 'classification' => 'Maintenance Record', 'type' => 'n/a', 'color' => 'text-white', 'bg' => 'bg-success', 'icon' => 'n/a', 'is_active' => 1],

            ['name' => 'Pending', 'classification' => 'Maintenance Request', 'type' => 'n/a', 'color' => 'text-dark', 'bg' => 'bg-secondary-subtle', 'icon' => 'n/a', 'is_active' => 1],
            ['name' => 'Approved', 'classification' => 'Maintenance Request', 'type' => 'n/a', 'color' => 'text-white', 'bg' => 'bg-info', 'icon' => 'n/a', 'is_active' => 1],
            ['name' => 'Completed', 'classification' => 'Maintenance Request', 'type' => 'n/a', 'color' => 'text-white', 'bg' => 'bg-success', 'icon' => 'n/a', 'is_active' => 1],
            ['name' => 'Rejected', 'classification' => 'Maintenance Request', 'type' => 'n/a', 'color' => 'text-white', 'bg' => 'bg-danger', 'icon' => 'n/a', 'is_active' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('list_statuses')->whereIn('classification', ['Maintenance Record', 'Maintenance Request'])->delete();
    }
};
