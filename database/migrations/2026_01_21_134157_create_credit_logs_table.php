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
        Schema::create('credit_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->decimal('amount',12,2); 
            $table->decimal('old_balance',12,2); 
            $table->decimal('new_balance',12,2); 
            $table->string('remarks')->nullable();
            $table->boolean('is_automated')->default(1);
            $table->boolean('is_expired')->default(0);
            $table->unsignedInteger('user_id'); //if change
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedSmallInteger('type_id');
            $table->foreign('type_id')->references('id')->on('list_data')->onDelete('cascade');
            $table->unsignedInteger('credit_id');
            $table->foreign('credit_id')->references('id')->on('user_credits')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_logs');
    }
};
