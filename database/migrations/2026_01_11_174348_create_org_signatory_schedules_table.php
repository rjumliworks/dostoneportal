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
        Schema::create('org_signatory_schedules', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');   
            $table->date('start_at');
            $table->date('end_at');  
            $table->unsignedInteger('signatory_id');
            $table->foreign('signatory_id')->references('id')->on('org_signatories')->onDelete('cascade');        
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_designated')->default(0);
            $table->boolean('is_ongoing')->default(0);
            $table->boolean('is_completed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_signatory_schedules');
    }
};
