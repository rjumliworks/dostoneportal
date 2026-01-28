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
        Schema::create('org_charts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->tinyIncrements('id');
            $table->integer('order');
            $table->unsignedTinyInteger('designation_id');
            $table->foreign('designation_id')->references('id')->on('list_dropdowns')->onDelete('cascade');   
            $table->unsignedTinyInteger('assigned_id');
            $table->foreign('assigned_id')->references('id')->on('list_dropdowns')->onDelete('cascade');        
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('oic_id')->nullable();
            $table->foreign('oic_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_oic')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_charts');
    }
};
