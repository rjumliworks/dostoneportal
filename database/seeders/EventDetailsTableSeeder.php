<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EventDetailsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('event_details')->delete();
        
        \DB::table('event_details')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => '𝗦𝗰𝗶𝗲𝗻𝗰𝗲, 𝗧𝗲𝗰𝗵𝗻𝗼𝗹𝗼𝗴𝘆, & 𝗗𝗶𝗴𝗶𝘁𝗮𝗹 𝗜𝗻𝗻𝗼𝘃𝗮𝘁𝗶𝗼𝗻 𝗗𝗿𝗶𝘃𝗶𝗻𝗴 𝗙𝗼𝗼𝗱 𝗦𝗲𝗰𝘂𝗿𝗶𝘁𝘆, 𝗦𝘂𝘀𝘁𝗮𝗶𝗻𝗮𝗯𝗹𝗲 𝗘𝗻𝗲𝗿𝗴𝘆, 𝗮𝗻𝗱 𝗡𝗮𝘁𝗶𝗼𝗻𝗮𝗹 𝗥𝗲𝘀𝗶𝗹𝗶𝗲𝗻𝗰𝗲',
                'venue' => 'Zamboanga Del Norte Convention and Sports Center',
                'address' => 'Gonzales Street',
                'longitude' => '123.348656',
                'latitude' => '8.582665',
                'barangay_code' => '097202009',
                'municipality_code' => '097202000',
                'province_code' => '097200000',
                'region_code' => '090000000',
                'event_id' => 1,
                'created_at' => '2026-07-18 22:42:17',
                'updated_at' => '2026-07-18 22:42:17',
            ),
        ));
        
        
    }
}