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
        Schema::create('request_travel', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('code',30)->unique()->index();
            $table->json('expenses');
            $table->unsignedSmallInteger('mode_id');
            $table->foreign('mode_id')->references('id')->on('list_data')->onDelete('cascade');
            $table->unsignedSmallInteger('transpo_id')->nullable();
            $table->foreign('transpo_id')->references('id')->on('list_data')->onDelete('cascade');
            $table->unsignedSmallInteger('expense_id');
            $table->foreign('expense_id')->references('id')->on('list_data')->onDelete('cascade');            
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
            $table->boolean('is_ard')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_travel');
    }
};
