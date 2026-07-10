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
        Schema::create('request_signatories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');    
            $table->string('code',30);
            $table->unsignedInteger('recommended_id')->nullable();
            $table->foreign('recommended_id')->references('id')->on('org_signatory_schedules')->onDelete('cascade');
            $table->datetime('recommended_date')->nullable();
            $table->string('recommended_by', 200)->nullable();
            $table->unsignedInteger('approved_id')->nullable();
            $table->foreign('approved_id')->references('id')->on('org_signatory_schedules')->onDelete('cascade');
            $table->datetime('approved_date')->nullable();
            $table->string('approved_by', 200)->nullable();
            $table->unsignedInteger('disapproved_id')->nullable();
            $table->foreign('disapproved_id')->references('id')->on('org_signatory_schedules')->onDelete('cascade');
            $table->unsignedTinyInteger('status_id');
            $table->foreign('status_id')->references('id')->on('list_statuses')->onDelete('cascade'); 
            $table->tinyInteger('division_id')->unsigned()->index();
            $table->foreign('division_id')->references('id')->on('list_dropdowns')->onDelete('cascade');    
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
            $table->boolean('is_approval_only')->default(0);
            $table->boolean('is_completed')->default(0);
            $table->boolean('is_disapproved')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_signatories');
    }
};
