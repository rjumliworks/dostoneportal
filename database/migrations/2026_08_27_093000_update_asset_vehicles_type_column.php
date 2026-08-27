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
        Schema::table('asset_vehicles', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('asset_vehicles', function (Blueprint $table) {
            $table->smallInteger('type_id')->unsigned()->index()->after('plate');
            $table->foreign('type_id')->references('id')->on('list_data')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_vehicles', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->dropColumn('type_id');
        });

        Schema::table('asset_vehicles', function (Blueprint $table) {
            $table->string('type')->after('plate');
        });
    }
};
