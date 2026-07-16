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
        Schema::create('event_csf_ratings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->integer('answer')->nullable();
            $table->integer('rating')->nullable();
            $table->integer('importance')->nullable();
            $table->unsignedInteger('question_id');
            $table->foreign('question_id')->references('id')->on('event_csf_questions')->onDelete('cascade');
            $table->unsignedBigInteger('csf_id');
            $table->foreign('csf_id')->references('id')->on('event_csf_entries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_csf_ratings');
    }
};
