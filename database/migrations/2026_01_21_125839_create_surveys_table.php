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
        Schema::create('surveys', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->year('year');
            $table->json('reports');
            $table->boolean('is_active')->default(1);
            $table->boolean('is_completed')->default(0);
            $table->unsignedTinyInteger('semester_id');
            $table->foreign('semester_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->datetime('finished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
