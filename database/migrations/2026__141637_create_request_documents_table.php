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
        Schema::create('request_documents', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->json('data')->nullable();
            $table->string('path')->nullable();
            $table->unsignedInteger('approved_id')->nullable();
            $table->foreign('approved_id')->references('id')->on('org_signatory_schedules')->onDelete('cascade');
            $table->datetime('approved_date')->nullable();
            $table->string('approved_by', 200)->nullable();
            $table->unsignedTinyInteger('status_id');
            $table->foreign('status_id')->references('id')->on('list_statuses')->onDelete('cascade');   
            $table->unsignedSmallInteger('type_id');
            $table->foreign('type_id')->references('id')->on('list_data')->onDelete('cascade');
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['type_id', 'request_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_documents');
    }
};
