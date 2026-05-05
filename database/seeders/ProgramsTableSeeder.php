<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProgramsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('programs')->delete();
        
        \DB::table('programs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'OneLab',
                'short' => 'OneLab',
                'has_scope' => 0,
                'is_active' => 1,
                'created_at' => '2026-05-05 12:26:50',
                'updated_at' => '2026-05-05 12:26:50',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Community Empowerment through Science and Technology',
                'short' => 'CEST',
                'has_scope' => 0,
                'is_active' => 1,
                'created_at' => '2026-05-05 12:26:50',
                'updated_at' => '2026-05-05 12:26:50',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Grants-In-Aid Program',
                'short' => 'GIA',
                'has_scope' => 1,
                'is_active' => 1,
                'created_at' => '2026-05-05 12:26:50',
                'updated_at' => '2026-05-05 12:26:50',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Special Allotment Advice',
                'short' => 'SAA',
                'has_scope' => 0,
                'is_active' => 1,
                'created_at' => '2026-05-05 12:26:50',
                'updated_at' => '2026-05-05 12:26:50',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Small Enterprise Technology Upgrading Program',
                'short' => 'SETUP',
                'has_scope' => 0,
                'is_active' => 1,
                'created_at' => '2026-05-05 12:26:50',
                'updated_at' => '2026-05-05 12:26:50',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Science for the Security, Safety and Care Program',
                'short' => 'SSCP',
                'has_scope' => 0,
                'is_active' => 1,
                'created_at' => '2026-05-05 12:26:50',
                'updated_at' => '2026-05-05 12:26:50',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Trust Fund',
                'short' => 'Trust Fund',
                'has_scope' => 0,
                'is_active' => 1,
                'created_at' => '2026-05-05 12:26:50',
                'updated_at' => '2026-05-05 12:26:50',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Zamboanga Industrial, Energy and Emerging Research and Development Consortium',
                'short' => 'ZIEERDEC',
                'has_scope' => 0,
                'is_active' => 1,
                'created_at' => '2026-05-05 12:26:50',
                'updated_at' => '2026-05-05 12:26:50',
            ),
        ));
        
        
    }
}