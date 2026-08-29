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
        Schema::table('request_tags', function (Blueprint $table) {
            $table->boolean('is_joined')->default(1)->after('status_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_tags', function (Blueprint $table) {
            $table->dropColumn('is_joined');
        });
    }
};
