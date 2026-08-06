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
        Schema::table('event_session_participants', function (Blueprint $table) {
            $table->datetime('approval_mailed_at')->nullable()->after('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_session_participants', function (Blueprint $table) {
            $table->dropColumn('approval_mailed_at');
        });
    }
};
