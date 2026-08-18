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
        Schema::create('posts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('number')->nullable();
            $table->string('title',150);
            $table->longText('content');
            $table->boolean('is_commentable')->default(true);
            $table->unsignedSmallInteger('type_id');
            $table->foreign('type_id')->references('id')->on('list_data')->onDelete('cascade');
            $table->unsignedSmallInteger('visibility_id');
            $table->foreign('visibility_id')->references('id')->on('list_data')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
