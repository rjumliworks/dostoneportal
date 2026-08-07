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
        DB::statement('ALTER TABLE `user_information` MODIFY `accounts` LONGTEXT NULL');
        DB::statement('ALTER TABLE `user_information` MODIFY `contacts` LONGTEXT NULL');
        DB::statement('ALTER TABLE `user_information` MODIFY `backgrounds` LONGTEXT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE `user_information` MODIFY `accounts` LONGTEXT NOT NULL');
        DB::statement('ALTER TABLE `user_information` MODIFY `contacts` LONGTEXT NOT NULL');
        DB::statement('ALTER TABLE `user_information` MODIFY `backgrounds` LONGTEXT NOT NULL');
    }
};
