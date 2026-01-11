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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->decimal('gross',12,2)->default(0.00);
            $table->decimal('deduction',12,2)->default(0.00);
            $table->decimal('netpay',12,2)->default(0.00);
            $table->decimal('tardiness',12,2)->default(0.00);
            $table->decimal('mins',5,2)->default(0.00);
            $table->decimal('days',5,2)->default(0.00);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('cutoff_id');
            $table->foreign('cutoff_id')->references('id')->on('payroll_cutoffs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
