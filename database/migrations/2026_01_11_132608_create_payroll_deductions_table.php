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
        Schema::create('payroll_deductions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->decimal('amount',12,2)->default(0.00);
            $table->unsignedTinyInteger('deduction_id');
            $table->foreign('deduction_id')->references('id')->on('list_deductions')->onDelete('cascade');
            $table->unsignedInteger('payroll_id');
            $table->foreign('payroll_id')->references('id')->on('payrolls')->onDelete('cascade');
            $table->boolean('is_plus')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_deductions');
    }
};
