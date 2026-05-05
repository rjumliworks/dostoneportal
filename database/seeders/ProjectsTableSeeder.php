<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('projects')->delete();
        
        \DB::table('projects')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Smarter OneLab for Industry 4.0 through Testing and Calibration, Education and Discovery',
                'short' => 'OneLab for TED',
                'is_active' => 1,
                'is_external' => 0,
                'province_code' => NULL,
                'status_id' => 47,
                'program_id' => 1,
                'created_at' => '2026-05-05 12:34:10',
                'updated_at' => '2026-05-05 12:34:10',
            ),
            1 => 
            array (
                'id' => 2,
            'name' => 'Support to DOST IX\'s Organizational Transformation through Mainstreaming of Gender and Development (GAD) Programs',
                'short' => 'GAD',
                'is_active' => 1,
                'is_external' => 0,
                'province_code' => NULL,
                'status_id' => 47,
                'program_id' => 3,
                'created_at' => '2026-05-05 12:34:10',
                'updated_at' => '2026-05-05 12:34:10',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Smart HR Solutions: Modernizing Talent Acquisition, Workforce Development, Performance Review, and Recognition Processes',
                'short' => 'HR',
                'is_active' => 1,
                'is_external' => 0,
                'province_code' => NULL,
                'status_id' => 47,
                'program_id' => 3,
                'created_at' => '2026-05-05 12:34:10',
                'updated_at' => '2026-05-05 12:34:10',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Strengthening Cyber Defense through Advanced Network Security and Threat Monitoring',
                'short' => 'MIS',
                'is_active' => 1,
                'is_external' => 0,
                'province_code' => NULL,
                'status_id' => 47,
                'program_id' => 3,
                'created_at' => '2026-05-05 12:34:10',
                'updated_at' => '2026-05-05 12:34:10',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Advancing DOST IX Operational Efficiency Through Robust Quality Management System and Strategic Planning',
                'short' => 'Planning',
                'is_active' => 1,
                'is_external' => 0,
                'province_code' => NULL,
                'status_id' => 47,
                'program_id' => 3,
                'created_at' => '2026-05-05 12:34:10',
                'updated_at' => '2026-05-05 12:34:10',
            ),
        ));
        
        
    }
}