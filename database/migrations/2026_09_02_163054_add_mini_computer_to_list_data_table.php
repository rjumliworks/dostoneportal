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
            'name' => 'Mini Computer',
            'type' => 'Asset',
            'is_active' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('list_data')->where('type', 'Asset')->where('name', 'Mini Computer')->delete();
    }
};
