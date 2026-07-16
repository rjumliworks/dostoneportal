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
        Schema::create('event_session_activities', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('activity',250);
            $table->unsignedInteger('speaker_id')->nullable();
            $table->foreign('speaker_id')->references('id')->on('event_speakers')->onDelete('cascade');
            $table->unsignedInteger('session_id');
            $table->foreign('session_id')->references('id')->on('event_sessions')->onDelete('cascade');
            $table->unsignedInteger('schedule_id');
            $table->foreign('schedule_id')->references('id')->on('event_session_schedules')->onDelete('cascade');
            $table->boolean('has_breakdown');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_session_activities');
    }
};
