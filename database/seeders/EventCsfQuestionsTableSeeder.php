<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EventCsfQuestionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('event_csf_questions')->delete();
        
        \DB::table('event_csf_questions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'The topics discussed during the event were relevant to me.',
                'sequence' => 1,
                'is_overall' => 0,
                'is_rating' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-16 07:31:28',
                'updated_at' => '2026-07-16 07:31:28',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'The topics discussed during the event were substanial.',
                'sequence' => 1,
                'is_overall' => 0,
                'is_rating' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-16 07:31:28',
                'updated_at' => '2026-07-16 07:31:28',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'The discussions contributed to my awareness, knowledge, and understanding of disaster risk reduction and management',
                'sequence' => 1,
                'is_overall' => 0,
                'is_rating' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-16 07:31:28',
                'updated_at' => '2026-07-16 07:31:28',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'The resource persons were knowledgeable, and their comments were relevant and thought-provoking.',
                'sequence' => 1,
                'is_overall' => 0,
                'is_rating' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-16 07:31:28',
                'updated_at' => '2026-07-16 07:31:28',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'The presentation of the resource persons is clear, visible, and well-organized.',
                'sequence' => 1,
                'is_overall' => 0,
                'is_rating' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-16 07:31:28',
                'updated_at' => '2026-07-16 07:31:28',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'The resource persons provided clear answers to questions and concerns raised.',
                'sequence' => 1,
                'is_overall' => 0,
                'is_rating' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-16 07:31:28',
                'updated_at' => '2026-07-16 07:31:28',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'The host encouraged participation and intellectual discussion.',
                'sequence' => 1,
                'is_overall' => 0,
                'is_rating' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-16 07:31:28',
                'updated_at' => '2026-07-16 07:31:28',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'The amount of time and opportunity provided for discussion was adequate.',
                'sequence' => 1,
                'is_overall' => 0,
                'is_rating' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-16 07:31:28',
                'updated_at' => '2026-07-16 07:31:28',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'The session was well-organized.',
                'sequence' => 1,
                'is_overall' => 0,
                'is_rating' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-16 07:31:28',
                'updated_at' => '2026-07-16 07:31:28',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'The venue and facilities during the session were adequate.',
                'sequence' => 1,
                'is_overall' => 0,
                'is_rating' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-16 07:31:28',
                'updated_at' => '2026-07-16 07:31:28',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'The event was helpful and responsive to participant\'s needs.',
                'sequence' => 1,
                'is_overall' => 0,
                'is_rating' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-16 07:31:28',
                'updated_at' => '2026-07-16 07:31:28',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'The event provided sufficient information before and during its duration.',
                'sequence' => 1,
                'is_overall' => 0,
                'is_rating' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-16 07:31:28',
                'updated_at' => '2026-07-16 07:31:28',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'The registration process was efficient and hassle-free.',
                'sequence' => 1,
                'is_overall' => 0,
                'is_rating' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-16 07:31:28',
                'updated_at' => '2026-07-16 07:31:28',
            ),
        ));
        
        
    }
}