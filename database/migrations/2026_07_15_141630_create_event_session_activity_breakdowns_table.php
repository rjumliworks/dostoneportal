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
        Schema::create('event_session_activity_breakdowns', function (Blueprint $table) {
           $table->engine = 'InnoDB';
            $table->increments('id');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('activity',250);
            $table->unsignedInteger('speaker_id');
            $table->foreign('speaker_id')->references('id')->on('event_speakers')->onDelete('cascade');
            $table->unsignedInteger('activity_id');
            $table->foreign('activity_id')->references('id')->on('event_session_activities')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_session_activity_breakdowns');
    }
};
