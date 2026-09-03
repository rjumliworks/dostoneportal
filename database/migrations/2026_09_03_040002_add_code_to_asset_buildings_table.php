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
        Schema::table('asset_buildings', function (Blueprint $table) {
            $table->string('code', 30)->nullable()->after('id');
        });

        DB::table('asset_buildings')->orderBy('id')->get(['id'])->each(function ($row, $index) {
            DB::table('asset_buildings')->where('id', $row->id)->update([
                'code' => 'DOSTIX-BLDG-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
            ]);
        });

        Schema::table('asset_buildings', function (Blueprint $table) {
            $table->string('code', 30)->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_buildings', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};
