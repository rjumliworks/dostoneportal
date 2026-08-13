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
        Schema::table('event_csf_entries', function (Blueprint $table) {
            $table->string('guest_name')->nullable()->after('participant_id');
            $table->string('guest_email')->nullable()->after('guest_name');
            $table->string('guest_affiliation')->nullable()->after('guest_email');
            $table->string('guest_designation')->nullable()->after('guest_affiliation');
        });

        // No doctrine/dbal in this project, so Blueprint's nullable()->change()
        // isn't available — modify the column with raw SQL instead (FK stays
        // intact; MySQL FKs don't validate NULLs).
        DB::statement('ALTER TABLE event_csf_entries MODIFY participant_id BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE event_csf_entries MODIFY participant_id BIGINT UNSIGNED NOT NULL');

        Schema::table('event_csf_entries', function (Blueprint $table) {
            $table->dropColumn(['guest_name', 'guest_email', 'guest_affiliation', 'guest_designation']);
        });
    }
};
