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
        Schema::create('request_leave_credits', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->boolean('is_borrowed')->default(0);
            $table->unsignedBigInteger('log_id');
            $table->foreign('log_id')->references('id')->on('credit_logs')->onDelete('cascade');
            $table->unsignedInteger('credit_id');
            $table->foreign('credit_id')->references('id')->on('user_credits')->onDelete('cascade');
            $table->unsignedBigInteger('leave_id');
            $table->foreign('leave_id')->references('id')->on('request_leaves')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_leave_credits');
    }
};
