<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('asset_equipment_maintenance_requests', function (Blueprint $table) {
            $table->string('maintainable_type')->nullable()->after('code');
            $table->integer('maintainable_id')->unsigned()->nullable()->after('maintainable_type');
        });

        DB::table('asset_equipment_maintenance_requests')->update([
            'maintainable_type' => 'equipment',
            'maintainable_id' => DB::raw('equipment_id'),
        ]);

        Schema::table('asset_equipment_maintenance_requests', function (Blueprint $table) {
            $table->dropForeign(['equipment_id']);
            $table->dropColumn('equipment_id');
            $table->index(['maintainable_type', 'maintainable_id'], 'asset_maintenance_requests_maintainable_index');
        });

        Schema::table('asset_equipment_maintenance_requests', function (Blueprint $table) {
            $table->string('maintainable_type')->nullable(false)->change();
            $table->integer('maintainable_id')->unsigned()->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_equipment_maintenance_requests', function (Blueprint $table) {
            $table->integer('equipment_id')->unsigned()->nullable()->after('code');
        });

        DB::table('asset_equipment_maintenance_requests')->update([
            'equipment_id' => DB::raw('maintainable_id'),
        ]);

        Schema::table('asset_equipment_maintenance_requests', function (Blueprint $table) {
            $table->dropIndex('asset_maintenance_requests_maintainable_index');
            $table->dropColumn(['maintainable_type', 'maintainable_id']);
            $table->integer('equipment_id')->unsigned()->nullable(false)->change();
            $table->foreign('equipment_id')->references('id')->on('asset_equipments')->onDelete('cascade');
        });
    }
};
