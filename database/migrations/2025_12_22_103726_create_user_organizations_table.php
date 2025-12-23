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
        Schema::create('user_organizations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedTinyInteger('status_id'); //retired //
            $table->foreign('status_id')->references('id')->on('list_statuses')->onDelete('cascade');
            $table->unsignedsmallInteger('type_id');
            $table->foreign('type_id')->references('id')->on('list_data')->onDelete('cascade');
            $table->unsignedTinyInteger('position_id');
            $table->foreign('position_id')->references('id')->on('list_positions')->onDelete('cascade');
            $table->unsignedTinyInteger('salary_id');
            $table->foreign('salary_id')->references('id')->on('list_salaries')->onDelete('cascade');
            $table->unsignedTinyInteger('division_id');
            $table->foreign('division_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->unsignedTinyInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('list_units')->onDelete('cascade');
            $table->unsignedTinyInteger('station_id');
            $table->foreign('station_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
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
        Schema::dropIfExists('user_organizations');
    }
};
