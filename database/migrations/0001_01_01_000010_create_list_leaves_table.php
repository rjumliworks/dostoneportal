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
        Schema::create('list_leaves', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->tinyIncrements('id');
            $table->string('name',100);
            $table->string('citation'); 
            $table->enum('sex', ['male', 'female'])->nullable()->comment('null means no sex restriction'); 
            $table->boolean('is_convertible')->default(0);
            $table->boolean('is_regular')->default(1);
            $table->boolean('is_nonregular')->default(0);
            $table->boolean('is_after')->default(0);
            $table->boolean('is_requested')->default(0);
            $table->boolean('carry_over')->default(0);
            $table->boolean('requires_balance')->nullable();
            $table->boolean('requires_document')->nullable();
            $table->string('renewal_period');
            $table->integer('max_days');
            $table->decimal('accrual_rate',4,2)->default(0.00);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_leaves');
    }
};
