<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EventSessionDetailsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('event_session_details')->delete();
        
        \DB::table('event_session_details')->insert(array (
            0 => 
            array (
                'id' => 1,
                'capacity' => 500,
                'attendees' => 0,
            'description' => 'The Opening Ceremony officially launches the Regional Science, Technology, and Innovation Week (RSTW) 2026, bringing together government leaders, researchers, innovators, educators, industry partners, students, and the public to celebrate the role of science, technology, and innovation in regional development. The ceremony marks the start of a series of exhibits, forums, technology demonstrations, and collaborative activities that showcase science-based solutions and strengthen partnerships for a smarter, more resilient, and sustainable future.',
                'session_id' => 1,
                'created_at' => '2026-07-18 22:51:39',
                'updated_at' => '2026-07-18 22:51:39',
            ),
            1 => 
            array (
                'id' => 2,
                'capacity' => 100,
                'attendees' => 0,
                'description' => 'The DOST Press Conference serves as a platform for engaging the media and the public by highlighting the Department\'s latest science, technology, and innovation programs, accomplishments, and initiatives. It fosters collaboration with the media in promoting public awareness of S&T-driven solutions that contribute to inclusive and sustainable national development.
',
                'session_id' => 2,
                'created_at' => '2026-07-21 15:35:49',
                'updated_at' => '2026-07-21 15:35:49',
            ),
            2 => 
            array (
                'id' => 3,
                'capacity' => 120,
                'attendees' => 0,
            'description' => 'The event aims to bring DOST’s developed and funded technologies straight to our Local Government Units (LGUs). By matching these innovations with community needs, agency sectoral councils and institutes—including DOST-PCAARRD, DOST-PTRI, DOST-FRPDI, DOST-ASTI, DOST-FNRI, and DOST-ITDI  will present high-impact solutions tailored to LGU priorities.',
                'session_id' => 3,
                'created_at' => '2026-07-21 23:07:43',
                'updated_at' => '2026-07-21 23:07:43',
            ),
            3 => 
            array (
                'id' => 4,
                'capacity' => 40,
                'attendees' => 0,
                'description' => 'The training on "Demystifying the Harmonized Gender and Development Guidelines: Beyond Compliance to Creating Real Impacts" is designed to provide GAD Focal Point System members and project implementers from DOST agencies in the Visayas and Mindanao Clusters with practical skills to integrate gender-responsive approaches into science, technology, and innovation programs. The training goes beyond compliance by enabling participants to identify gender issues, apply the HGDG in project development and implementation, and use gender analysis to achieve inclusive, measurable outcomes that benefit communities.',
                'session_id' => 4,
                'created_at' => '2026-07-21 23:08:56',
                'updated_at' => '2026-07-21 23:08:56',
            ),
            4 => 
            array (
                'id' => 5,
                'capacity' => 100,
                'attendees' => 0,
                'description' => 'The seminar serves as the Mindanao leg of a nationwide series of pre-convention activities leading up to the PhilAASTI National Convention, themed “PhilAASTI @ 75: Building Foundations, Strengthening Pillars, and Delivering Solutions.” Together with similar seminars in Luzon and the Visayas, this event aims to foster meaningful discussions, promote collaboration, and generate innovative solutions that advance science, technology, and innovation in support of sustainable economic development.',
                'session_id' => 5,
                'created_at' => '2026-07-21 23:09:30',
                'updated_at' => '2026-07-21 23:09:30',
            ),
            5 => 
            array (
                'id' => 6,
                'capacity' => 70,
                'attendees' => 0,
                'description' => 'This fora aims to serve as a platform for showcasing innovative, science-based technologies and research initiatives that promote sustainable, climate-resilient, and resource-efficient agricultural and fisheries systems. The activity seeks to highlight the role of smart agriculture in improving productivity, enhancing environmental sustainability, and strengthening the resilience of farming and fishing communities amid changing climate conditions.',
                'session_id' => 6,
                'created_at' => '2026-07-21 23:10:38',
                'updated_at' => '2026-07-21 23:10:38',
            ),
            6 => 
            array (
                'id' => 7,
                'capacity' => 50,
                'attendees' => 0,
                'description' => 'F&N Talks is a forum that highlights innovative science- and technology-based approaches to addressing food and nutrition challenges. The forum will feature emerging technologies, research, digital solutions, and evidence-based strategies that strengthen food security, improve nutrition outcomes, and enhance the resilience of Filipino communities in the face of evolving social, economic, and environmental challenges .',
                'session_id' => 7,
                'created_at' => '2026-07-21 23:11:25',
                'updated_at' => '2026-07-21 23:11:25',
            ),
            7 => 
            array (
                'id' => 8,
                'capacity' => 90,
                'attendees' => 0,
            'description' => 'This training is designed to enhance participants\' knowledge and technical competencies in the proper testing and sealing of test measures and verification of non-automatic weighing instruments (NAWI) in accordance with existing Philippine laws, legal metrology regulations, and established standards. The activity combines discussions on the applicable legal and regulatory framework with practical demonstrations of the prescribed testing and verification procedures. Through this training, participants will gain a better understanding of legal metrology practices that support measurement accuracy, regulatory compliance, fair trade, and consumer protection.',
                'session_id' => 8,
                'created_at' => '2026-07-21 23:12:07',
                'updated_at' => '2026-07-21 23:12:07',
            ),
            8 => 
            array (
                'id' => 9,
                'capacity' => 40,
                'attendees' => 0,
                'description' => '"A one-day training program that aims to equip participants with comprehensive knowledge and practical skills on laboratory safety and 
hazardous waste management in compliance with Philippine regulations (RA 11058, DOLE OSH Standards, RA 6969, DAO 2013-22) and international frameworks (ISO 45001, 
ISO 14001, OSHA, Basel Convention). The program covers identification and control of laboratory hazards, waste minimization, segregation, and documentation, as well as 
emergency preparedness and response. A chemical spill drill is integrated to reinforce learning, ensuring participants are not only aware of safety and environmental requirements 
but are also competent in applying practical measures to safeguard health, the laboratory environment, and regulatory complianc"',
            'session_id' => 9,
            'created_at' => '2026-07-21 23:13:03',
            'updated_at' => '2026-07-21 23:13:03',
        ),
        9 => 
        array (
            'id' => 10,
            'capacity' => 100,
            'attendees' => 0,
        'description' => 'R&D Symposium (hosted by WESMAARDEC)',
            'session_id' => 10,
            'created_at' => '2026-07-21 23:13:47',
            'updated_at' => '2026-07-21 23:13:47',
        ),
        10 => 
        array (
            'id' => 11,
            'capacity' => 100,
            'attendees' => 0,
        'description' => 'Technology Forum (hosted by WESMAARDEC)',
            'session_id' => 11,
            'created_at' => '2026-07-21 23:14:06',
            'updated_at' => '2026-07-21 23:14:06',
        ),
        11 => 
        array (
            'id' => 12,
            'capacity' => 60,
            'attendees' => 0,
            'description' => '"Dialogue with Local Inventors thru LGUs to level the playing field so innovators from the countryside can compete based on merit, not location or access networks. It shall introduce programs of DOST-TAPI to ease the path from idea to market by supporting early-stage commercialization, which is often the hardest phase for local inventors with limited resources. 
The LGUs play a pivotal role in supporting Local Inventors in turning their ideas into marketable ventures. That means not only helping with permits and local regulations, but also building programs, partnerships, and protection systems that help innovations become real products and services. 

The Local Government Code gives LGUs broad authority to promote the general welfare, and that includes actions that support technological capability, economic prosperity, and community development. The Code also recognizes that LGUs deliver basic services and can foster local economic development, which supports innovation-related programs and partnerships. In addition, the Code’s decentralization framework allows local governments to act with flexibility, so long as they serve local needs and work within their powers.

The activity ensures that DOST shall catalyze the building of a stronger innovation ecosystem in the country by identifying inventors; provide technical and business support; and promote their innovations beyond the local level. "',
            'session_id' => 12,
            'created_at' => '2026-07-21 23:14:49',
            'updated_at' => '2026-07-21 23:14:49',
        ),
        12 => 
        array (
            'id' => 13,
            'capacity' => 60,
            'attendees' => 0,
        'description' => 'The CyberSAFE MSMEs: Strengthening Digital Security and Business Resilience for DOST-SETUP Assisted MSMEs is a one-day capability-building activity proposed during the Regional Science, Technology, and Innovation Week (RSTW) 2026 in Dipolog City. It aims to equip 50 DOST-SETUP-assisted MSMEs with essential knowledge on cybersecurity, digital transformation, and safe technology adoption to strengthen business resilience against emerging cyber threats. The activity will feature lectures from DOST Region IX and DICT, covering digital transformation, cybersecurity awareness, cyber hygiene, phishing prevention, data privacy, and practical security measures, followed by an interactive open forum.',
            'session_id' => 13,
            'created_at' => '2026-07-21 23:15:23',
            'updated_at' => '2026-07-21 23:15:23',
        ),
        13 => 
        array (
            'id' => 14,
            'capacity' => 500,
            'attendees' => 0,
            'description' => 'Viewing of Science Centrum',
            'session_id' => 14,
            'created_at' => '2026-07-21 23:15:51',
            'updated_at' => '2026-07-21 23:15:51',
        ),
        14 => 
        array (
            'id' => 15,
            'capacity' => 500,
            'attendees' => 0,
            'description' => 'Put your science knowledge to the test in Science Trivia Powered by STARBOOKS! Compete in an exciting, real-time quiz challenge through Wayground using your mobile device and race against fellow participants to answer questions on science, technology, innovation, and more. Whether you\'re a student, teacher, exhibitor, or visitor, this fast-paced side event promises fun, learning, and friendly competition. Discover fascinating science facts, sharpen your critical thinking skills, climb the leaderboard, and take home exciting prizes. Join the challenge and prove you have what it takes to become the next Science Trivia Champion!',
            'session_id' => 15,
            'created_at' => '2026-07-21 23:16:38',
            'updated_at' => '2026-07-21 23:16:38',
        ),
        15 => 
        array (
            'id' => 16,
            'capacity' => 30,
            'attendees' => 0,
        'description' => 'The End-User Training on VISSER is a technical training designed to equip science teachers from the six (6) recipient schools of VISSER under the Schools Division of Zamboanga del Norte with the knowledge and skills needed for the proper operation, routine maintenance, and effective classroom integration of VISSER equipment.',
            'session_id' => 16,
            'created_at' => '2026-07-21 23:17:03',
            'updated_at' => '2026-07-21 23:17:03',
        ),
    ));
        
        
    }
}