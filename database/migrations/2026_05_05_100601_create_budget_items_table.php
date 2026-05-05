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
        Schema::create('budget_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->decimal('amount',12,2)->default(0.00);
            $table->unsignedTinyInteger('expense_id') ;
            $table->foreign('expense_id')->references('id')->on('list_expenses')->onDelete('cascade');
            $table->unsignedInteger('budget_id') ;
            $table->foreign('budget_id')->references('id')->on('budgets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_items');
    }
};
