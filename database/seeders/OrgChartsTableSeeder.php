<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrgChartsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('org_charts')->delete();
        
        \DB::table('org_charts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order' => 1,
                'designation_id' => 43,
                'assigned_id' => 2,
                'user_id' => NULL,
                'oic_id' => NULL,
                'is_oic' => 0,
                'is_active' => 1,
                'created_at' => '2026-01-11 18:36:48',
                'updated_at' => '2026-01-11 18:36:48',
            ),
            1 => 
            array (
                'id' => 2,
                'order' => 2,
                'designation_id' => 44,
                'assigned_id' => 3,
                'user_id' => NULL,
                'oic_id' => NULL,
                'is_oic' => 0,
                'is_active' => 1,
                'created_at' => '2026-01-11 18:36:48',
                'updated_at' => '2026-01-11 18:36:48',
            ),
            2 => 
            array (
                'id' => 3,
                'order' => 2,
                'designation_id' => 44,
                'assigned_id' => 4,
                'user_id' => NULL,
                'oic_id' => NULL,
                'is_oic' => 0,
                'is_active' => 1,
                'created_at' => '2026-01-11 18:36:48',
                'updated_at' => '2026-01-11 18:36:48',
            ),
            3 => 
            array (
                'id' => 4,
                'order' => 3,
                'designation_id' => 45,
                'assigned_id' => 6,
                'user_id' => NULL,
                'oic_id' => NULL,
                'is_oic' => 0,
                'is_active' => 1,
                'created_at' => '2026-01-11 18:36:48',
                'updated_at' => '2026-01-11 18:36:48',
            ),
            4 => 
            array (
                'id' => 5,
                'order' => 3,
                'designation_id' => 45,
                'assigned_id' => 7,
                'user_id' => NULL,
                'oic_id' => NULL,
                'is_oic' => 0,
                'is_active' => 1,
                'created_at' => '2026-01-11 18:36:48',
                'updated_at' => '2026-01-11 18:36:48',
            ),
            5 => 
            array (
                'id' => 6,
                'order' => 3,
                'designation_id' => 45,
                'assigned_id' => 8,
                'user_id' => NULL,
                'oic_id' => NULL,
                'is_oic' => 0,
                'is_active' => 1,
                'created_at' => '2026-01-11 18:36:48',
                'updated_at' => '2026-01-11 18:36:48',
            ),
            6 => 
            array (
                'id' => 7,
                'order' => 3,
                'designation_id' => 46,
                'assigned_id' => 9,
                'user_id' => NULL,
                'oic_id' => NULL,
                'is_oic' => 0,
                'is_active' => 1,
                'created_at' => '2026-01-11 18:36:48',
                'updated_at' => '2026-01-11 18:36:48',
            ),
            7 => 
            array (
                'id' => 8,
                'order' => 3,
                'designation_id' => 48,
                'assigned_id' => 5,
                'user_id' => NULL,
                'oic_id' => NULL,
                'is_oic' => 0,
                'is_active' => 1,
                'created_at' => '2026-01-11 18:36:48',
                'updated_at' => '2026-01-11 18:36:48',
            ),
        ));
        
        
    }
}