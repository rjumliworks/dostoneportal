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
        Schema::create('user_academics', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('is_ongoing');
            $table->year('graduated_at')->nullable();
            $table->unsignedSmallInteger('school_id');
            $table->foreign('school_id')->references('id')->on('list_academics')->onDelete('cascade');
            $table->unsignedSmallInteger('course_id');
            $table->foreign('course_id')->references('id')->on('list_academics')->onDelete('cascade');
            $table->unsignedSmallInteger('level_id');
            $table->foreign('level_id')->references('id')->on('list_data')->onDelete('cascade');
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
        Schema::dropIfExists('user_academics');
    }
};
