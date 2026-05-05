<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TargetsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('targets')->delete();
        
        \DB::table('targets')->insert(array (
            0 => 
            array (
                'id' => 1,
                'year' => '2026',
                'data' => '[]',
                'is_completed' => 0,
                'created_at' => '2026-05-05 12:42:25',
                'updated_at' => '2026-05-05 12:42:25',
            ),
        ));
        
        
    }
}