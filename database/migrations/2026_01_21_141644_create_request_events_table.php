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
        Schema::create('request_events', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->text('title');
            $table->boolean('is_host')->default(true);
            $table->unsignedSmallInteger('audience_id');
            $table->foreign('audience_id')->references('id')->on('list_data');
            $table->unsignedSmallInteger('mode_id');
            $table->foreign('mode_id')->references('id')->on('list_data')->onDelete('cascade');
            $table->unsignedSmallInteger('type_id');
            $table->foreign('type_id')->references('id')->on('list_data')->onDelete('cascade');
            $table->unsignedBigInteger('request_id')->nullable();
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_events');
    }
};
