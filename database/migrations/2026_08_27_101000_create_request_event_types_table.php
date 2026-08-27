<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('request_event_types', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedBigInteger('request_event_id');
            $table->foreign('request_event_id')->references('id')->on('request_events')->onDelete('cascade');
            $table->unsignedTinyInteger('type_id');
            $table->foreign('type_id')->references('id')->on('list_events')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['request_event_id', 'type_id']);
        });

        DB::table('request_events')->whereNotNull('type_id')->get(['id', 'type_id'])->each(function ($event) {
            DB::table('request_event_types')->insert([
                'request_event_id' => $event->id,
                'type_id' => $event->type_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        Schema::table('request_events', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->dropColumn('type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_events', function (Blueprint $table) {
            $table->unsignedTinyInteger('type_id')->nullable()->after('audience_id');
        });

        DB::table('request_event_types')->orderBy('id')->get()->each(function ($pivot) {
            DB::table('request_events')->where('id', $pivot->request_event_id)->update([
                'type_id' => $pivot->type_id,
            ]);
        });

        Schema::table('request_events', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')->on('list_events')->onDelete('cascade');
        });

        Schema::dropIfExists('request_event_types');
    }
};
