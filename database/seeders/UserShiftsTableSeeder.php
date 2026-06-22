<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserShiftsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('user_shifts')->delete();
        
        \DB::table('user_shifts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'shift_id' => 7,
                'user_id' => 1,
                'created_at' => '2026-06-22 14:23:42',
                'updated_at' => '2026-06-22 14:23:42',
            ),
        ));
        
        
    }
}