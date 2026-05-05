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
        Schema::create('list_expenses', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->tinyIncrements('id');
            $table->string('code')->unique()->nullable();
            $table->string('name');
            $table->string('short',50)->nullable();
            $table->boolean('is_active')->default(1);
            $table->unsignedTinyInteger('category_id') ;
            $table->foreign('category_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->unsignedTinyInteger('class_id');
            $table->foreign('class_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_expenses');
    }
};
