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
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->tinyInteger('rating')->check('rating BETWEEN 1 AND 5');
            $table->unsignedInteger('survey_id');
            $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('cascade');
            $table->unsignedSmallInteger('question_id');
            $table->foreign('question_id')->references('id')->on('survey_questions')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_answers');
    }
};
