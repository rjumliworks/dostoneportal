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
        Schema::create('request_travel_events', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedBigInteger('request_travel_id');
            $table->foreign('request_travel_id')->references('id')->on('request_travels')->onDelete('cascade');
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('request_events')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['request_travel_id', 'event_id']);
        });

        Schema::table('request_travels', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_travels', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->after('expenses');
            $table->foreign('event_id')->references('id')->on('request_events')->onDelete('cascade');
        });

        Schema::dropIfExists('request_travel_events');
    }
};
