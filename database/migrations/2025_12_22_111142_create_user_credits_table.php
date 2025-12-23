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
        Schema::create('user_credits', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->decimal('balance',12,2); 
            $table->decimal('earned',12,2);
            $table->decimal('used',12,2);
            $table->decimal('carried_over', 12, 2)->default(0);
            $table->decimal('expired', 12, 2)->default(0);
            $table->year('year');
            $table->boolean('is_active')->default(1);
            $table->unsignedTinyInteger('leave_id');
            $table->foreign('leave_id')->references('id')->on('list_leaves')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'leave_id', 'year']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_credits');
    }
};
