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
        Schema::create('event_session_questions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->longText('question');
            $table->boolean('is_answered')->default(0);
            $table->unsignedBigInteger('participant_id');
            $table->foreign('participant_id')->references('id')->on('participants')->onDelete('cascade');
            $table->unsignedInteger('session_id');
            $table->foreign('session_id')->references('id')->on('event_sessions')->onDelete('cascade');
            $table->unique(['participant_id', 'session_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_session_questions');
    }
};
