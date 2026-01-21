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
        Schema::create('request_leaves', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->decimal('count', 4, 1);
            $table->decimal('pay', 4, 1);
            $table->decimal('nopay', 4, 1);
            $table->decimal('borrowed', 4, 1);
            $table->unsignedInteger('certified_id')->nullable();
            $table->foreign('certified_id')->references('id')->on('org_signatory_schedules')->onDelete('cascade');
            $table->datetime('certified_date')->nullable();
            $table->string('certified_by', 200)->nullable();
            $table->unsignedTinyInteger('detail_id');
            $table->foreign('detail_id')->references('id')->on('list_dropdowns')->onDelete('cascade');   
            $table->unsignedTinyInteger('type_id');
            $table->foreign('type_id')->references('id')->on('list_leaves')->onDelete('cascade');
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_leaves');
    }
};
