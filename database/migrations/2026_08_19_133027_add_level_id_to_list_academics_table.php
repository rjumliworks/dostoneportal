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
        Schema::table('list_academics', function (Blueprint $table) {
            // Nullable on purpose: school rows (type_id 173) have no level.
            $table->unsignedSmallInteger('level_id')->nullable()->after('type_id');
            $table->foreign('level_id')->references('id')->on('list_data')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('list_academics', function (Blueprint $table) {
            $table->dropForeign(['level_id']);
            $table->dropColumn('level_id');
        });
    }
};
