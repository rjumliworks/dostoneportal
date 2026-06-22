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
                'required_punches' => 4,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
            1 => 
            array (
                'id' => 2,
            'name' => 'Regular Office (Early Departure)',
                'required_punches' => 4,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
            2 => 
            array (
                'id' => 3,
            'name' => 'Regular Office (Late Start)',
                'required_punches' => 4,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Security Morning',
                'required_punches' => 2,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Security Afternoon',
                'required_punches' => 2,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Security Night',
                'required_punches' => 2,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Four-Day Work Week',
                'required_punches' => 4,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
            7 => 
            array (
                'id' => 8,
            'name' => 'Four-Day Work Week (Early Departure)',
                'required_punches' => 4,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
            8 => 
            array (
                'id' => 9,
            'name' => 'Four-Day Work Week (Late Start)',
                'required_punches' => 4,
                'is_active' => 1,
                'created_at' => '2026-06-22 13:30:33',
                'updated_at' => '2026-06-22 13:30:33',
            ),
        ));
        
        
    }
}