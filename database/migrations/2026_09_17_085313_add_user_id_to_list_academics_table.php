<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('list_academics', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->after('level_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        DB::table('list_academics')->whereNull('user_id')->update(['user_id' => 1]);
    }

    public function down(): void
    {
        Schema::table('list_academics', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
