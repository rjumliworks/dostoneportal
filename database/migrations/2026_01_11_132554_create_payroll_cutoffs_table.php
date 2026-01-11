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
        Schema::create('payroll_cutoffs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code')->unique();
            $table->date('start');
            $table->date('end');
            $table->enum('type', ['1st','2nd'])->nullable();
            $table->integer('batch');
            $table->boolean('is_locked')->default(0);
            $table->boolean('is_completed')->default(0);
            $table->unsignedInteger('cycle_id');
            $table->foreign('cycle_id')->references('id')->on('payroll_cycles')->onDelete('cascade');
            $table->unsignedTinyInteger('status_id');
            $table->foreign('status_id')->references('id')->on('list_statuses')->onDelete('cascade');
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
        Schema::dropIfExists('payroll_cutoffs');
    }
};
