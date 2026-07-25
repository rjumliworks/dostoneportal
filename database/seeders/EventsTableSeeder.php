<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('events')->delete();
        
        \DB::table('events')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'EVENT-072026-DOSTIX-0001',
                'name' => '2026 Regional Science, Technology, and Innovation Week',
                'year' => '2026',
                'start' => '2026-08-12',
                'end' => '2026-08-14',
                'registration_scope' => NULL,
                'is_active' => 1,
                'user_id' => 1,
                'created_at' => '2026-07-18 22:42:17',
                'updated_at' => '2026-07-18 22:42:17',
            ),
        ));
        
        
    }
}