<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('asset_equipment_maintenance_requests', 'asset_maintenance_requests');
        Schema::rename('asset_equipment_records', 'asset_maintenance_records');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('asset_maintenance_records', 'asset_equipment_records');
        Schema::rename('asset_maintenance_requests', 'asset_equipment_maintenance_requests');
    }
};
