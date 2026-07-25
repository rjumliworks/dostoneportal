<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EventVenuesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('event_venues')->delete();
        
        \DB::table('event_venues')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'VN-072026-DOSTIX-0001',
                'name' => 'To be announced',
                'establishment' => 'Zamboanga Del Norte Convention and Sports Center',
                'address' => 'Gonzales Street, Dipolog City',
                'event_id' => 1,
                'created_at' => '2026-07-18 22:43:10',
                'updated_at' => '2026-07-18 22:43:10',
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'VN-072026-DOSTIX-0002',
                'name' => 'Main Entrance',
                'establishment' => 'Zamboanga Del Norte Convention and Sports Center',
                'address' => 'Gonzales Street, Dipolog City',
                'event_id' => 1,
                'created_at' => '2026-07-18 22:43:33',
                'updated_at' => '2026-07-18 22:43:33',
            ),
            2 => 
            array (
                'id' => 3,
                'code' => 'VN-072026-DOSTIX-0003',
                'name' => 'Plenary Hall',
                'establishment' => 'Zamboanga Del Norte Convention and Sports Center',
                'address' => 'Gonzales Street, Dipolog City',
                'event_id' => 1,
                'created_at' => '2026-07-18 22:43:41',
                'updated_at' => '2026-07-18 22:43:41',
            ),
        ));
        
        
    }
}