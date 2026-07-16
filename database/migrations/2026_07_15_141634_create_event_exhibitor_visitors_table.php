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
        Schema::create('event_exhibitor_visitors', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->boolean('has_voted')->default(0);
            $table->datetime('voted_at')->nullable();
            $table->unsignedBigInteger('participant_id');
            $table->foreign('participant_id')->references('id')->on('participants')->onDelete('cascade');
            $table->unsignedInteger('exhibitor_id');
            $table->foreign('exhibitor_id')->references('id')->on('event_exhibitors')->onDelete('cascade');
            $table->unique(['participant_id', 'exhibitor_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_exhibitor_visitors');
    }
};
