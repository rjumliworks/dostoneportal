<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ListUnitsTableSeeder extends Seeder
{
    /**
     * Auto generated seeder file.
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('list_units')->delete();
        
        \DB::table('list_units')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Human Resource',
                'short' => 'HR',
                'is_active' => 1,
                'division_id' => 3,
                'created_at' => '2024-08-29 07:49:50',
                'updated_at' => '2024-08-29 07:49:50',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Office of the Regional Director Unit',
                'short' => 'ORD Unit',
                'is_active' => 1,
                'division_id' => 2,
                'created_at' => '2024-08-29 07:58:34',
                'updated_at' => '2024-08-29 07:58:34',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Disaster Risk Reduction Management',
                'short' => 'DRRM',
                'is_active' => 1,
                'division_id' => 2,
                'created_at' => '2024-08-29 07:59:53',
                'updated_at' => '2024-08-29 07:59:53',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Gender and Development',
                'short' => 'GAD',
                'is_active' => 1,
                'division_id' => 2,
                'created_at' => '2024-08-29 08:00:22',
                'updated_at' => '2024-08-29 08:00:22',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Planning',
                'short' => 'Planning',
                'is_active' => 1,
                'division_id' => 2,
                'created_at' => '2024-08-29 08:00:50',
                'updated_at' => '2024-08-29 08:00:50',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'RRDICOMM',
                'short' => 'RRDICOMM',
                'is_active' => 1,
                'division_id' => 2,
                'created_at' => '2024-08-29 08:28:23',
                'updated_at' => '2024-08-29 08:28:23',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Accounting',
                'short' => 'Accounting',
                'is_active' => 1,
                'division_id' => 3,
                'created_at' => '2024-08-29 08:28:46',
                'updated_at' => '2024-08-29 08:28:46',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Budgeting',
                'short' => 'Budgeting',
                'is_active' => 1,
                'division_id' => 3,
                'created_at' => '2024-08-29 08:28:57',
                'updated_at' => '2024-08-29 08:28:57',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Cashiering',
                'short' => 'Cashiering',
                'is_active' => 1,
                'division_id' => 3,
                'created_at' => '2024-08-29 08:29:07',
                'updated_at' => '2024-08-29 08:29:07',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Procument and Supply',
                'short' => 'Procument and Supply',
                'is_active' => 1,
                'division_id' => 3,
                'created_at' => '2024-08-29 08:30:50',
                'updated_at' => '2024-08-29 08:30:50',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Maintenance and General Services',
                'short' => 'Maintenance',
                'is_active' => 1,
                'division_id' => 3,
                'created_at' => '2024-08-29 08:31:16',
                'updated_at' => '2024-08-29 08:31:16',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Zamboanga Consortium for Health Research and Development',
                'short' => 'ZCHRD',
                'is_active' => 1,
                'division_id' => 4,
                'created_at' => '2024-08-29 08:32:00',
                'updated_at' => '2024-08-29 08:32:00',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Zamboanga Industry, Energy and Emerging Research and Development Consortium',
                'short' => 'ZIEERDEC',
                'is_active' => 1,
                'division_id' => 4,
                'created_at' => '2024-08-29 08:32:51',
                'updated_at' => '2024-08-29 08:32:51',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'S&T Scholarship',
                'short' => 'Scholarship',
                'is_active' => 1,
                'division_id' => 4,
                'created_at' => '2024-08-29 08:33:04',
                'updated_at' => '2024-08-29 08:33:04',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Regional Standards and Testing Laboratory',
                'short' => 'RSTL',
                'is_active' => 1,
                'division_id' => 4,
                'created_at' => '2024-08-29 08:34:09',
                'updated_at' => '2024-08-29 08:34:09',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Community Empowerment through Science and Technology',
                'short' => 'CEST',
                'is_active' => 1,
                'division_id' => 4,
                'created_at' => '2024-08-29 08:35:01',
                'updated_at' => '2024-08-29 08:35:01',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Regional Project Management Office',
                'short' => 'RPMO',
                'is_active' => 1,
                'division_id' => 4,
                'created_at' => '2024-08-29 08:35:20',
                'updated_at' => '2024-08-29 08:35:20',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'PSTO/CSTC',
                'short' => 'PSTO/CSTC',
                'is_active' => 1,
                'division_id' => 4,
                'created_at' => '2024-08-29 08:40:12',
                'updated_at' => '2024-08-29 08:40:12',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Guards',
                'short' => 'Guards',
                'is_active' => 1,
                'division_id' => 35,
                'created_at' => '2024-08-29 08:40:12',
                'updated_at' => '2024-08-29 08:40:12',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Management Information System',
                'short' => 'MIS',
                'is_active' => 1,
                'division_id' => 2,
                'created_at' => '2024-08-29 08:00:50',
                'updated_at' => '2024-08-29 08:00:50',
            ),
        ));

        
    }
}