<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Security Afternoon (shift_id 5) now runs Mon-Sat 15:00-23:00 as before,
        // but on Sunday the same guard covers the night shift too (15:00-07:00 the
        // next day), bridging the weekly guard rotation without a coverage gap.
        DB::table('shift_times')
            ->where('shift_id', 5)
            ->where('days', '1,2,3,4,5,6,7')
            ->update(['days' => '1,2,3,4,5,6']);

        DB::table('shift_times')->insert([
            'days' => '7',
            'segment' => 'whole',
            'in_time' => '15:00:00',
            'in_grace' => 0,
            'out_time' => '07:00:00',
            'shift_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('shift_times')
            ->where('shift_id', 5)
            ->where('days', '7')
            ->where('out_time', '07:00:00')
            ->delete();

        DB::table('shift_times')
            ->where('shift_id', 5)
            ->where('days', '1,2,3,4,5,6')
            ->update(['days' => '1,2,3,4,5,6,7']);
    }
};
