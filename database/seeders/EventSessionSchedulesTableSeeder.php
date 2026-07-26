<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EventSessionSchedulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('event_session_schedules')->delete();
        
        \DB::table('event_session_schedules')->insert(array (
            0 => 
            array (
                'id' => 1,
                'date' => '2026-08-12',
                'time_of_day' => 'Whole Day',
                'start_time' => '10:00:00',
                'end_time' => '11:30:00',
                'session_id' => 1,
                'created_at' => '2026-07-18 22:51:39',
                'updated_at' => '2026-07-18 22:51:39',
            ),
            1 => 
            array (
                'id' => 2,
                'date' => '2026-08-12',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 2,
                'created_at' => '2026-07-21 15:35:49',
                'updated_at' => '2026-07-21 15:35:49',
            ),
            2 => 
            array (
                'id' => 3,
                'date' => '2026-08-12',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 3,
                'created_at' => '2026-07-21 23:07:43',
                'updated_at' => '2026-07-21 23:07:43',
            ),
            3 => 
            array (
                'id' => 4,
                'date' => '2026-08-12',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 4,
                'created_at' => '2026-07-21 23:08:56',
                'updated_at' => '2026-07-21 23:08:56',
            ),
            4 => 
            array (
                'id' => 5,
                'date' => '2026-08-13',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 4,
                'created_at' => '2026-07-21 23:08:56',
                'updated_at' => '2026-07-21 23:08:56',
            ),
            5 => 
            array (
                'id' => 6,
                'date' => '2026-08-14',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 4,
                'created_at' => '2026-07-21 23:08:56',
                'updated_at' => '2026-07-21 23:08:56',
            ),
            6 => 
            array (
                'id' => 7,
                'date' => '2026-08-13',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 5,
                'created_at' => '2026-07-21 23:09:30',
                'updated_at' => '2026-07-21 23:09:30',
            ),
            7 => 
            array (
                'id' => 8,
                'date' => '2026-08-13',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 6,
                'created_at' => '2026-07-21 23:10:38',
                'updated_at' => '2026-07-21 23:10:38',
            ),
            8 => 
            array (
                'id' => 9,
                'date' => '2026-08-13',
                'time_of_day' => 'AM',
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
                'session_id' => 7,
                'created_at' => '2026-07-21 23:11:25',
                'updated_at' => '2026-07-21 23:11:25',
            ),
            9 => 
            array (
                'id' => 10,
                'date' => '2026-08-13',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 8,
                'created_at' => '2026-07-21 23:12:07',
                'updated_at' => '2026-07-21 23:12:07',
            ),
            10 => 
            array (
                'id' => 11,
                'date' => '2026-08-13',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 9,
                'created_at' => '2026-07-21 23:13:03',
                'updated_at' => '2026-07-21 23:13:03',
            ),
            11 => 
            array (
                'id' => 12,
                'date' => '2026-08-13',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 10,
                'created_at' => '2026-07-21 23:13:47',
                'updated_at' => '2026-07-21 23:13:47',
            ),
            12 => 
            array (
                'id' => 13,
                'date' => '2026-08-13',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 11,
                'created_at' => '2026-07-21 23:14:06',
                'updated_at' => '2026-07-21 23:14:06',
            ),
            13 => 
            array (
                'id' => 14,
                'date' => '2026-08-13',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 12,
                'created_at' => '2026-07-21 23:14:49',
                'updated_at' => '2026-07-21 23:14:49',
            ),
            14 => 
            array (
                'id' => 15,
                'date' => '2026-08-14',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 13,
                'created_at' => '2026-07-21 23:15:23',
                'updated_at' => '2026-07-21 23:15:23',
            ),
            15 => 
            array (
                'id' => 16,
                'date' => '2026-08-12',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 14,
                'created_at' => '2026-07-21 23:15:51',
                'updated_at' => '2026-07-21 23:15:51',
            ),
            16 => 
            array (
                'id' => 17,
                'date' => '2026-08-13',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 14,
                'created_at' => '2026-07-21 23:15:51',
                'updated_at' => '2026-07-21 23:15:51',
            ),
            17 => 
            array (
                'id' => 18,
                'date' => '2026-08-14',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 14,
                'created_at' => '2026-07-21 23:15:51',
                'updated_at' => '2026-07-21 23:15:51',
            ),
            18 => 
            array (
                'id' => 19,
                'date' => '2026-08-12',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 15,
                'created_at' => '2026-07-21 23:16:38',
                'updated_at' => '2026-07-21 23:16:38',
            ),
            19 => 
            array (
                'id' => 20,
                'date' => '2026-08-13',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 15,
                'created_at' => '2026-07-21 23:16:38',
                'updated_at' => '2026-07-21 23:16:38',
            ),
            20 => 
            array (
                'id' => 21,
                'date' => '2026-08-14',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 15,
                'created_at' => '2026-07-21 23:16:38',
                'updated_at' => '2026-07-21 23:16:38',
            ),
            21 => 
            array (
                'id' => 22,
                'date' => '2026-08-13',
                'time_of_day' => 'Whole Day',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'session_id' => 16,
                'created_at' => '2026-07-21 23:17:03',
                'updated_at' => '2026-07-21 23:17:03',
            ),
        ));
        
        
    }
}