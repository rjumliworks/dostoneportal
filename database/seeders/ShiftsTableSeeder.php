<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ShiftsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('shifts')->delete();
        
        \DB::table('shifts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Regular Office',
                'days' => 5,
                'hours' => 8,
                'required_punches' => 4,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
            1 => 
            array (
                'id' => 2,
            'name' => 'Regular Office (Early Departure)',
                'days' => 5,
                'hours' => 8,
                'required_punches' => 4,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
            2 => 
            array (
                'id' => 3,
            'name' => 'Regular Office (Late Start)',
                'days' => 5,
                'hours' => 8,
                'required_punches' => 4,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Security Morning',
                'days' => 7,
                'hours' => 0,
                'required_punches' => 2,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Security Afternoon',
                'days' => 7,
                'hours' => 0,
                'required_punches' => 2,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Security Night',
                'days' => 7,
                'hours' => 0,
                'required_punches' => 2,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Four-Day Work Week',
                'days' => 4,
                'hours' => 10,
                'required_punches' => 4,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
            7 => 
            array (
                'id' => 8,
            'name' => 'Four-Day Work Week (Early Departure)',
                'days' => 4,
                'hours' => 10,
                'required_punches' => 4,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
            8 => 
            array (
                'id' => 9,
            'name' => 'Four-Day Work Week (Late Start)',
                'days' => 4,
                'hours' => 10,
                'required_punches' => 4,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
        ));
        
        
    }
}