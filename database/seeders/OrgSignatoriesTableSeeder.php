<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrgSignatoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('org_signatories')->delete();
        
        \DB::table('org_signatories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'designationable_id' => 1,
                'designationable_type' => 'App\\Models\\OrgChart',
                'user_id' => NULL,
                'oic_id' => NULL,
                'is_oic' => 0,
                'is_topmanagement' => 0,
                'is_active' => 1,
                'created_at' => '2026-01-11 18:54:11',
                'updated_at' => '2026-01-11 18:54:11',
            ),
            1 => 
            array (
                'id' => 2,
                'designationable_id' => 2,
                'designationable_type' => 'App\\Models\\OrgChart',
                'user_id' => NULL,
                'oic_id' => NULL,
                'is_oic' => 0,
                'is_topmanagement' => 0,
                'is_active' => 1,
                'created_at' => '2026-01-11 18:54:11',
                'updated_at' => '2026-01-11 18:54:11',
            ),
            2 => 
            array (
                'id' => 3,
                'designationable_id' => 3,
                'designationable_type' => 'App\\Models\\OrgChart',
                'user_id' => NULL,
                'oic_id' => NULL,
                'is_oic' => 0,
                'is_topmanagement' => 0,
                'is_active' => 1,
                'created_at' => '2026-01-11 18:54:11',
                'updated_at' => '2026-01-11 18:54:11',
            ),
            3 => 
            array (
                'id' => 4,
                'designationable_id' => 4,
                'designationable_type' => 'App\\Models\\OrgChart',
                'user_id' => NULL,
                'oic_id' => NULL,
                'is_oic' => 0,
                'is_topmanagement' => 0,
                'is_active' => 1,
                'created_at' => '2026-01-11 18:54:11',
                'updated_at' => '2026-01-11 18:54:11',
            ),
            4 => 
            array (
                'id' => 5,
                'designationable_id' => 5,
                'designationable_type' => 'App\\Models\\OrgChart',
                'user_id' => NULL,
                'oic_id' => NULL,
                'is_oic' => 0,
                'is_topmanagement' => 0,
                'is_active' => 1,
                'created_at' => '2026-01-11 18:54:11',
                'updated_at' => '2026-01-11 18:54:11',
            ),
            5 => 
            array (
                'id' => 6,
                'designationable_id' => 6,
                'designationable_type' => 'App\\Models\\OrgChart',
                'user_id' => NULL,
                'oic_id' => NULL,
                'is_oic' => 0,
                'is_topmanagement' => 0,
                'is_active' => 1,
                'created_at' => '2026-01-11 18:54:11',
                'updated_at' => '2026-01-11 18:54:11',
            ),
            6 => 
            array (
                'id' => 7,
                'designationable_id' => 7,
                'designationable_type' => 'App\\Models\\OrgChart',
                'user_id' => NULL,
                'oic_id' => NULL,
                'is_oic' => 0,
                'is_topmanagement' => 0,
                'is_active' => 1,
                'created_at' => '2026-01-11 18:54:11',
                'updated_at' => '2026-01-11 18:54:11',
            ),
            7 => 
            array (
                'id' => 8,
                'designationable_id' => 8,
                'designationable_type' => 'App\\Models\\OrgChart',
                'user_id' => NULL,
                'oic_id' => NULL,
                'is_oic' => 0,
                'is_topmanagement' => 0,
                'is_active' => 1,
                'created_at' => '2026-01-11 18:54:11',
                'updated_at' => '2026-01-11 18:54:11',
            ),
        ));
        
        
    }
}