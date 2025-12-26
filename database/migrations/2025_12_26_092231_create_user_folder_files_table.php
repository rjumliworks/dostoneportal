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
        Schema::create('user_folder_files', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('path');
            $table->string('mime_type', 100);
            $table->json('meta')->nullable();
            $table->unsignedBigInteger('size');
            $table->enum('status', ['pending','uploading','processing','completed','failed'])->default('pending');
            $table->unsignedSmallInteger('type_id');
            $table->foreign('type_id')->references('id')->on('list_data')->onDelete('cascade');
            $table->unsignedInteger('folder_id');
            $table->foreign('folder_id')->references('id')->on('user_folders')->onDelete('cascade');
            $table->datetime('opened_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_folder_files');
    }
};
