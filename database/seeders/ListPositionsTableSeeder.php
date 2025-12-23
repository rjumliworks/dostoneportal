<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ListPositionsTableSeeder extends Seeder
{
    /**
     * Auto generated seeder file.
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('list_positions')->delete();
        
        \DB::table('list_positions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Not Available',
                'short' => 'n/a',
                'salary_id' => 1,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Chief Administrative Officer',
                'short' => 'Chief AO',
                'salary_id' => 24,
                'is_regular' => 1,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Chief Science Research Specialist',
                'short' => 'Chief SRS',
                'salary_id' => 24,
                'is_regular' => 1,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Supervising Science Research Specialist',
                'short' => 'Supervising SRS',
                'salary_id' => 22,
                'is_regular' => 1,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Senior Science Research Specialist',
                'short' => 'Senior SRS',
                'salary_id' => 19,
                'is_regular' => 1,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Accountant III',
                'short' => 'A III',
                'salary_id' => 19,
                'is_regular' => 1,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Administrative Officer V',
                'short' => 'AO V',
                'salary_id' => 18,
                'is_regular' => 1,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Science Research Specialist II',
                'short' => 'SRS II',
                'salary_id' => 16,
                'is_regular' => 1,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Science Research Specialist I',
                'short' => 'SRS I',
                'salary_id' => 13,
                'is_regular' => 1,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Science Research Assistant',
                'short' => 'SR Assistant',
                'salary_id' => 9,
                'is_regular' => 1,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Administrative Aide IV',
                'short' => 'Admin Aide IV',
                'salary_id' => 4,
                'is_regular' => 1,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Project Technical Specialist IV',
                'short' => 'PTS IV',
                'salary_id' => 19,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Project Technical Specialist III',
                'short' => 'PTS III',
                'salary_id' => 19,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Project Technical Specialist II',
                'short' => 'PTS II',
                'salary_id' => 19,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Project Technical Specialist I',
                'short' => 'PTS I',
                'salary_id' => 19,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Project Technical Assistant IV',
                'short' => 'PTA IV',
                'salary_id' => 19,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Project Technical Assistant III',
                'short' => 'PTA III',
                'salary_id' => 19,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Project Technical Assistant II',
                'short' => 'PTA II',
                'salary_id' => 19,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Project Technical Assistant I',
                'short' => 'PTA I',
                'salary_id' => 19,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
        ));

        
    }
}