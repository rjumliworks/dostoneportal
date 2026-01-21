<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SurveyQuestionsTableSeeder extends Seeder
{
    public function run()
    {
        

        \DB::table('survey_questions')->delete();
        
        \DB::table('survey_questions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'question' => 'I feel part of the DOST-IX family',
                'is_active' => 1,
                'created_at' => '2025-02-24 15:48:20',
                'updated_at' => '2025-02-24 15:48:22',
            ),
            1 => 
            array (
                'id' => 2,
                'question' => 'I find my work interesting and fulfilling.',
                'is_active' => 1,
                'created_at' => '2025-02-24 15:48:25',
                'updated_at' => '2025-02-24 15:48:27',
            ),
            2 => 
            array (
                'id' => 3,
                'question' => 'DOST IX provides plenty of opportunities for personal growth.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'question' => 'DOST IX has clearly defined quality goals.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'question' => 'Information is openly shared between management and employees.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'question' => 'I am compensated fairly.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'question' => 'My supervisor gives me enough opportunities to take an active role as a leader.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'question' => 'At DOST IX, we are rewarded for our performance and striving to achieve excellence.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'question' => 'I have security and stability at my work.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'question' => 'People within my unit cooperate with each other rather than compete.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'question' => 'My work gives me a feeling of personal accomplishment.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'question' => 'DOST IX provides technical training so that I can advance in my career.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'question' => 'My work assignment and my role in the organization are specified and adequately explained to me.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'question' => 'My supervisor listens to my opinion when making decision that involves my work activities.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'question' => 'I am able to plan my vacation and take off the days that I need.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'question' => 'I feel comfortable talking to my supervisor whenever there is a problem.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'question' => 'My supervisor recognized the extra effort and actions that I do to perform the best at DOST IX.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'question' => 'I trust the top management’s integrity and believe what they say.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'question' => 'My supervisor encourages teamwork and cooperation to achieve targeted goals.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'question' => 'I am proud of being a DOST IX employee.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'question' => 'DOST IX facilitates ongoing training to upgrade my skills.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'question' => 'Policies and procedures are explained adequately within DOST IX.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'question' => 'The management gives all of the information I need to perform my job well.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'question' => 'DOST IX provides competitive compensation and benefits packages compared to other offices.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'question' => 'My supervisor encourages me to do my best.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'question' => 'My supervisor always shows appreciation for every extra effort I put in my work.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'question' => 'I will continue to work for DOST IX in the next 5 years.',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));

        
    }
}