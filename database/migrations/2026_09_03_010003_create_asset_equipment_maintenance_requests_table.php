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
        Schema::create('asset_equipment_maintenance_requests', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code', 30)->unique()->index();
            $table->integer('equipment_id')->unsigned()->index();
            $table->foreign('equipment_id')->references('id')->on('asset_equipments')->onDelete('cascade');
            $table->integer('requested_by')->unsigned()->index();
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->string('location')->nullable();
            $table->text('work_requested');
            $table->text('problem_description')->nullable();
            $table->smallInteger('priority_id')->unsigned()->nullable()->index();
            $table->foreign('priority_id')->references('id')->on('list_data')->onDelete('set null');
            $table->tinyInteger('status_id')->unsigned()->index();
            $table->foreign('status_id')->references('id')->on('list_statuses')->onDelete('cascade');
            $table->text('remarks')->nullable();
            $table->date('requested_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_equipment_maintenance_requests');
    }
};
