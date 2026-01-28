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
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->date('date');
            $table->longText('am_in_at')->nullable();
            $table->longText('am_out_at')->nullable();
            $table->longText('pm_in_at')->nullable();
            $table->longText('pm_out_at')->nullable();
            $table->longText('remarks')->nullable();
            $table->unsignedInteger('visitor_id');
            $table->foreign('visitor_id')->references('id')->on('visitors')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['date', 'visitor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
