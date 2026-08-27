<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_certificates', function (Blueprint $table) {
            $table->boolean('is_checked')->default(false)->after('password');
        });

        DB::statement('ALTER TABLE user_certificates MODIFY file VARCHAR(255) NULL');
        DB::statement('ALTER TABLE user_certificates MODIFY password LONGTEXT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE user_certificates MODIFY file VARCHAR(255) NOT NULL DEFAULT ''");
        DB::statement("ALTER TABLE user_certificates MODIFY password LONGTEXT NOT NULL");

        Schema::table('user_certificates', function (Blueprint $table) {
            $table->dropColumn('is_checked');
        });
    }
};
