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
        Schema::create('projects', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('short')->unique();
            $table->boolean('is_active');
            $table->boolean('is_external')->default(0);
            $table->string('province_code')->nullable()->constrained();
            $table->foreign('province_code')->references('code')->on('location_provinces')->onDelete('cascade');
            $table->unsignedTinyInteger('status_id') ;
            $table->foreign('status_id')->references('id')->on('list_statuses')->onDelete('cascade');
            $table->unsignedInteger('program_id') ;
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
