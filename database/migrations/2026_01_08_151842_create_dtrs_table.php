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
        Schema::create('dtrs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->date('date');
            $table->longText('am_in_at')->nullable();
            $table->longText('am_out_at')->nullable();
            $table->longText('pm_in_at')->nullable();
            $table->longText('pm_out_at')->nullable();
            $table->longText('remarks')->nullable();
            $table->unsignedSmallInteger('tardiness')->default(0);
            $table->unsignedSmallInteger('undertime')->default(0);
            $table->boolean('is_updated')->default(0);
            $table->boolean('is_completed')->default(0);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['date', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dtrs');
    }
};
