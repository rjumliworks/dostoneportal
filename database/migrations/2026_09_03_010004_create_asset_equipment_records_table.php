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
        Schema::create('asset_equipment_records', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('equipment_id')->unsigned()->index();
            $table->foreign('equipment_id')->references('id')->on('asset_equipments')->onDelete('cascade');
            $table->integer('request_id')->unsigned()->nullable()->index();
            $table->foreign('request_id')->references('id')->on('asset_equipment_maintenance_requests')->onDelete('set null');
            $table->smallInteger('type_id')->unsigned()->nullable()->index();
            $table->foreign('type_id')->references('id')->on('list_data')->onDelete('set null');
            $table->tinyInteger('status_id')->unsigned()->nullable()->index();
            $table->foreign('status_id')->references('id')->on('list_statuses')->onDelete('set null');
            $table->date('date');
            $table->text('operation_performed');
            $table->text('remarks')->nullable();
            $table->integer('performed_by')->unsigned();
            $table->foreign('performed_by')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('cost', 12, 2)->nullable();
            $table->string('attachment')->nullable();
            $table->date('next_due')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_equipment_records');
    }
};
