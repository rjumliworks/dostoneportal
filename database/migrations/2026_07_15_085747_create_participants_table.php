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
        Schema::create('participants', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->text('email');
            $table->string('kradworkz', 64)->unique()->index();
            $table->text('mobile');
            $table->string('mobile_hash', 64)->unique()->index();
            $table->text('lastname');
            $table->text('firstname');
            $table->text('middlename');
            $table->string('suffix',10)->nullable();
            $table->boolean('is_completed')->default(0);
            $table->timestamp('last_login_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
