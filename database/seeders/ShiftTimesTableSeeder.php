<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ShiftTimesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('shift_times')->delete();
        
        \DB::table('shift_times')->insert(array (
            0 => 
            array (
                'id' => 1,
                'days' => '1',
                'segment' => 'am',
                'in_time' => '08:00:00',
                'in_grace' => 0,
                'out_time' => '12:00:00',
                'shift_id' => 1,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            1 => 
            array (
                'id' => 2,
                'days' => '2,3,4,5',
                'segment' => 'am',
                'in_time' => '08:00:00',
                'in_grace' => 30,
                'out_time' => '12:00:00',
                'shift_id' => 1,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            2 => 
            array (
                'id' => 3,
                'days' => '1,2,3,4,5',
                'segment' => 'pm',
                'in_time' => '13:00:00',
                'in_grace' => 0,
                'out_time' => '17:00:00',
                'shift_id' => 1,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            3 => 
            array (
                'id' => 4,
                'days' => '1',
                'segment' => 'am',
                'in_time' => '08:00:00',
                'in_grace' => 0,
                'out_time' => '11:30:00',
                'shift_id' => 2,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            4 => 
            array (
                'id' => 5,
                'days' => '2,3,4,5',
                'segment' => 'am',
                'in_time' => '08:00:00',
                'in_grace' => 30,
                'out_time' => '11:30:00',
                'shift_id' => 2,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            5 => 
            array (
                'id' => 6,
                'days' => '1,2,3,4,5',
                'segment' => 'pm',
                'in_time' => '12:30:00',
                'in_grace' => 0,
                'out_time' => '17:00:00',
                'shift_id' => 2,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            6 => 
            array (
                'id' => 7,
                'days' => '1',
                'segment' => 'am',
                'in_time' => '08:00:00',
                'in_grace' => 0,
                'out_time' => '12:30:00',
                'shift_id' => 3,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            7 => 
            array (
                'id' => 8,
                'days' => '2,3,4,5',
                'segment' => 'am',
                'in_time' => '08:00:00',
                'in_grace' => 30,
                'out_time' => '12:30:00',
                'shift_id' => 3,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            8 => 
            array (
                'id' => 9,
                'days' => '1,2,3,4,5',
                'segment' => 'pm',
                'in_time' => '13:30:00',
                'in_grace' => 0,
                'out_time' => '17:00:00',
                'shift_id' => 3,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            9 => 
            array (
                'id' => 10,
                'days' => '1,2,3,4,5,6,7',
                'segment' => 'whole',
                'in_time' => '07:00:00',
                'in_grace' => 0,
                'out_time' => '15:00:00',
                'shift_id' => 4,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            10 => 
            array (
                'id' => 11,
                'days' => '1,2,3,4,5,6,7',
                'segment' => 'whole',
                'in_time' => '15:00:00',
                'in_grace' => 0,
                'out_time' => '23:00:00',
                'shift_id' => 5,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            11 => 
            array (
                'id' => 12,
                'days' => '1,2,3,4,5,6,7',
                'segment' => 'whole',
                'in_time' => '23:00:00',
                'in_grace' => 0,
                'out_time' => '07:00:00',
                'shift_id' => 6,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            12 => 
            array (
                'id' => 13,
                'days' => '1',
                'segment' => 'am',
                'in_time' => '07:00:00',
                'in_grace' => 0,
                'out_time' => '12:00:00',
                'shift_id' => 7,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            13 => 
            array (
                'id' => 14,
                'days' => '2,3,4',
                'segment' => 'am',
                'in_time' => '07:00:00',
                'in_grace' => 30,
                'out_time' => '12:00:00',
                'shift_id' => 7,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            14 => 
            array (
                'id' => 15,
                'days' => '1,2,3,4',
                'segment' => 'pm',
                'in_time' => '13:00:00',
                'in_grace' => 0,
                'out_time' => '18:00:00',
                'shift_id' => 7,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            15 => 
            array (
                'id' => 16,
                'days' => '1',
                'segment' => 'am',
                'in_time' => '07:00:00',
                'in_grace' => 0,
                'out_time' => '11:30:00',
                'shift_id' => 8,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            16 => 
            array (
                'id' => 17,
                'days' => '2,3,4',
                'segment' => 'am',
                'in_time' => '07:00:00',
                'in_grace' => 30,
                'out_time' => '11:30:00',
                'shift_id' => 8,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            17 => 
            array (
                'id' => 18,
                'days' => '1,2,3,4',
                'segment' => 'pm',
                'in_time' => '12:30:00',
                'in_grace' => 0,
                'out_time' => '18:00:00',
                'shift_id' => 8,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            18 => 
            array (
                'id' => 19,
                'days' => '1',
                'segment' => 'am',
                'in_time' => '07:00:00',
                'in_grace' => 0,
                'out_time' => '12:30:00',
                'shift_id' => 9,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            19 => 
            array (
                'id' => 20,
                'days' => '2,3,4',
                'segment' => 'am',
                'in_time' => '07:00:00',
                'in_grace' => 30,
                'out_time' => '12:30:00',
                'shift_id' => 9,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
            20 => 
            array (
                'id' => 21,
                'days' => '1,2,3,4',
                'segment' => 'pm',
                'in_time' => '13:30:00',
                'in_grace' => 0,
                'out_time' => '18:00:00',
                'shift_id' => 9,
                'created_at' => '2026-06-22 13:30:40',
                'updated_at' => '2026-06-22 13:30:40',
            ),
        ));
        
        
    }
}