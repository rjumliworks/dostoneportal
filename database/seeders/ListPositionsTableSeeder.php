<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ListPositionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
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
                'salary_id' => 31,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Project Technical Specialist III',
                'short' => 'PTS III',
                'salary_id' => 32,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Project Technical Specialist II',
                'short' => 'PTS II',
                'salary_id' => 33,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Project Technical Specialist I',
                'short' => 'PTS I',
                'salary_id' => 34,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Project Technical Assistant VI',
                'short' => 'PTA VI',
                'salary_id' => 35,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Project Technical Assistant V',
                'short' => 'PTA V',
                'salary_id' => 36,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Project Technical Assistant IV',
                'short' => 'PTA IV',
                'salary_id' => 37,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Project Technical Assistant III',
                'short' => 'PTA III',
                'salary_id' => 38,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Project Technical Assistant II',
                'short' => 'PTA II',
                'salary_id' => 39,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Project Technical Assistant I',
                'short' => 'PTA I',
                'salary_id' => 40,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'Project Technical Aide V',
                'short' => 'PTA V',
                'salary_id' => 42,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'Project Technical Aide IV',
                'short' => 'PTA IV',
                'salary_id' => 43,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'Project Technical Aide III',
                'short' => 'PTA III',
                'salary_id' => 44,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'Project Technical Aide II',
                'short' => 'PTA II',
                'salary_id' => 45,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'Project Technical Aide I',
                'short' => 'PTA I',
                'salary_id' => 46,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'Project Administrative I',
                'short' => 'PA I',
                'salary_id' => 46,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'Project Administrative Assistant IV',
                'short' => 'PAA IV',
                'salary_id' => 37,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'Project Administrative Assistant III',
                'short' => 'PAA III',
                'salary_id' => 38,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'Project Administrative Assistant II',
                'short' => 'PAA II',
                'salary_id' => 39,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'Project Administrative Assistant I',
                'short' => 'PAA I',
                'salary_id' => 40,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'Project Administrative Aide VI',
                'short' => 'PAA VI',
                'salary_id' => 41,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'Project Administrative Aide V',
                'short' => 'PAA V',
                'salary_id' => 42,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'Project Administrative Aide IV',
                'short' => 'PAA IV',
                'salary_id' => 43,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'Project Administrative Aide III',
                'short' => 'PAA III',
                'salary_id' => 44,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'Project Administrative Aide II',
                'short' => 'PAA II',
                'salary_id' => 45,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'Project Administrative Aide I',
                'short' => 'PAA I',
                'salary_id' => 46,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'Project Utility Aide I',
                'short' => 'PUA I',
                'salary_id' => 49,
                'is_regular' => 0,
                'created_at' => '2025-06-19 12:21:52',
                'updated_at' => '2025-06-19 12:21:52',
            ),
        ));
        
        
    }
}