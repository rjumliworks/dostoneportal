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
        Schema::table('request_travels', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->nullable()->after('expenses');
            $table->foreign('event_id')->references('id')->on('request_events')->onDelete('cascade');
        });

        Schema::table('request_reservations', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->nullable()->after('id');
            $table->foreign('event_id')->references('id')->on('request_events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_tables', function (Blueprint $table) {
            //
        });
    }
};
