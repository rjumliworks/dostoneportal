<?php

namespace App\Services\Profile;

use App\Models\User;

class PrintClass
{
    public function pds($userId)
    {
        $user = User::with([
            'profile.sex', 'profile.marital', 'profile.blood', 'profile.suffix',
            'addresses.region', 'addresses.province', 'addresses.municipality', 'addresses.barangay',
            'information',
            'academics.school', 'academics.course', 'academics.level',
            'eligibilities',
            'contracts.position', 'contracts.type',
            'workExperiences',
            'voluntaryWorks',
            'trainings',
            'otherInformation',
            'references',
            'declaration',
        ])->findOrFail($userId);

        $permanent = $user->addresses->firstWhere('is_permanent', 1);
        $residential = $user->addresses->firstWhere('is_permanent', 0);

        $accounts = collect($user->information->accounts ?? [])->keyBy('name');
        $backgrounds = $user->information->backgrounds ?? [];
        $personal = $user->information->personal ?? [];

        $systemWork = $user->contracts->map(function ($c) {
            return (object) [
                'start_at' => $c->start_at,
                'end_at' => $c->is_active ? null : $c->end_at,
                'position_title' => $c->position?->name,
                'department_agency' => 'Department of Science and Technology',
                'monthly_salary' => null,
                'salary_grade' => null,
                'appointment_status' => $c->type?->name,
                'is_government' => true,
            ];
        });
        $selfWork = $user->workExperiences->map(fn ($w) => (object) $w->toArray());
        $workExperiences = $systemWork->concat($selfWork)->sortByDesc('start_at')->values();

        $otherByType = $user->otherInformation->groupBy('type');

        // The paper form has five fixed LEVEL rows; map each academic record into
        // the matching slot so the table keeps its exact shape no matter what the
        // user selected from the (differently-named) Level dropdown.
        $education = [
            'ELEMENTARY' => collect(),
            'SECONDARY' => collect(),
            'VOCATIONAL / TRADE COURSE' => collect(),
            'COLLEGE' => collect(),
            'GRADUATE STUDIES' => collect(),
        ];
        foreach ($user->academics as $a) {
            $level = strtolower($a->level->name ?? '');
            $slot = match (true) {
                str_contains($level, 'elementary'), str_contains($level, 'primary') => 'ELEMENTARY',
                str_contains($level, 'high school'), str_contains($level, 'secondary') => 'SECONDARY',
                str_contains($level, 'vocational'), str_contains($level, 'trade'), str_contains($level, 'technical') => 'VOCATIONAL / TRADE COURSE',
                str_contains($level, 'bachelor'), str_contains($level, 'associate'), str_contains($level, 'college') => 'COLLEGE',
                str_contains($level, 'master'), str_contains($level, 'doctor'), str_contains($level, 'graduate'), str_contains($level, 'post') => 'GRADUATE STUDIES',
                default => 'VOCATIONAL / TRADE COURSE',
            };
            $education[$slot]->push($a);
        }

        $declaration = $user->declaration;
        $questions = [
            ['label' => '34.a Are you related by consanguinity or affinity to the appointing or recommending authority, or to the chief of bureau or office, or to the person who has immediate supervision over you, within the third degree?', 'v' => $declaration?->related_third_degree, 'd' => $declaration?->related_third_degree_details],
            ['label' => '34.b Within the fourth degree (for Local Government Unit - Career Employees)?', 'v' => $declaration?->related_fourth_degree, 'd' => $declaration?->related_fourth_degree_details],
            ['label' => '35.a Have you ever been found guilty of any administrative offense?', 'v' => $declaration?->admin_offense_found_guilty, 'd' => $declaration?->admin_offense_details],
            ['label' => '35.b Have you been criminally charged before any court?', 'v' => $declaration?->criminally_charged, 'd' => $declaration?->criminal_charge_details],
            ['label' => '36. Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?', 'v' => $declaration?->convicted_crime, 'd' => $declaration?->convicted_crime_details],
            ['label' => '37. Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?', 'v' => $declaration?->separated_from_service, 'd' => $declaration?->separated_from_service_details],
            ['label' => '38.a Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?', 'v' => $declaration?->election_candidate, 'd' => $declaration?->election_candidate_details],
            ['label' => '38.b Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?', 'v' => $declaration?->resigned_to_campaign, 'd' => $declaration?->resigned_to_campaign_details],
            ['label' => '39. Have you acquired the status of an immigrant or permanent resident of another country?', 'v' => $declaration?->immigrant_status, 'd' => $declaration?->immigrant_status_country],
            ['label' => '40.a Are you a member of any indigenous group?', 'v' => $declaration?->indigenous_group_member, 'd' => $declaration?->indigenous_group_details],
            ['label' => '40.b Are you a person with disability?', 'v' => $declaration?->is_pwd, 'd' => $declaration?->pwd_id_number],
            ['label' => '40.c Are you a solo parent?', 'v' => $declaration?->is_solo_parent, 'd' => $declaration?->solo_parent_id_number],
        ];

        $pdf = \PDF::loadView('prints.pds', [
            'user' => $user,
            'profile' => $user->profile,
            'permanent' => $permanent,
            'residential' => $residential,
            'accounts' => $accounts,
            'backgrounds' => $backgrounds,
            'personal' => $personal,
            'academics' => $user->academics,
            'education' => $education,
            'eligibilities' => $user->eligibilities,
            'workExperiences' => $workExperiences,
            'voluntaryWorks' => $user->voluntaryWorks,
            'trainings' => $user->trainings,
            'skills' => $otherByType->get('skill', collect()),
            'distinctions' => $otherByType->get('distinction', collect()),
            'organizations' => $otherByType->get('organization', collect()),
            'references' => $user->references,
            'declaration' => $declaration,
            'questions' => $questions,
        ])->setPaper('legal', 'portrait');

        return $pdf->stream('PDS-' . $user->username . '.pdf');
    }
}
