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
        Schema::create('event_csf_entries', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->decimal('rate',5,2);
            $table->string('comment')->nullable();
            $table->string('attribute')->nullable();
            $table->unsignedInteger('feedbackable_id');   
            $table->string('feedbackable_type'); 
            $table->unsignedBigInteger('participant_id');
            $table->foreign('participant_id')->references('id')->on('participants')->onDelete('cascade');
            $table->unique(
                ['participant_id', 'feedbackable_id', 'feedbackable_type'],
                'ecsf_participant_feedback_unique'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_csf_entries');
    }
};
