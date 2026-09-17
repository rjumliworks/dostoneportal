<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Seeds list_academics (type_id=174, Course) with common Doctorate and Master's
 * degree programs, scoped via level_id to the matching list_data (type=Level) row
 * so the "Basic Education / Degree / Course" field only offers relevant programs
 * once a Doctorate or Master's level is picked. Level ids are looked up by name
 * (never hardcoded) since list_data rows are added ad-hoc via the admin UI and can
 * differ between environments. Guarded to skip a level's set if it already has any
 * scoped courses, so it's safe to re-run on live.
 */
return new class extends Migration
{
    private const DOCTORATE_COURSES = [
        'Doctor of Philosophy (Ph.D.)',
        'Doctor of Education (Ed.D.)',
        'Doctor of Business Administration (DBA)',
        'Doctor of Public Administration (DPA)',
        'Doctor of Science (D.Sc.)',
        'Doctor of Engineering (D.Eng.)',
        'Doctor of Information Technology (DIT)',
        'Doctor of Public Health (Dr.PH)',
        'Doctor of Laws (LL.D.)',
        'Doctor of Nursing Science (DNS)',
        'Doctor of Criminal Justice (DCrim)',
        'Doctor of Social Work (DSW)',
        'Doctor of Theology (Th.D.)',
        'Doctor of Arts (D.A.)',
        'Other Doctorate Program',
    ];

    private const MASTERS_COURSES = [
        'Master of Arts (MA)',
        'Master of Science (MSc)',
        'Master in Business Administration (MBA)',
        'Master in Public Administration (MPA)',
        'Master of Education (MEd)',
        'Master of Engineering (MEng)',
        'Master of Information Technology (MIT)',
        'Master of Information Systems (MIS)',
        'Master of Public Health (MPH)',
        'Master of Social Work (MSW)',
        'Master of Laws (LLM)',
        'Master of Science in Nursing (MSN)',
        'Master in Development Management',
        'Master of Science in Criminology',
        'Master of Arts in Teaching (MAT)',
        'Master in Environmental Management',
        'Master in Urban and Regional Planning',
        'Master of Fine Arts (MFA)',
        'Other Master\'s Program',
    ];

    public function up(): void
    {
        $this->seedLevel('Doctorate Degree', self::DOCTORATE_COURSES);
        $this->seedLevel('Master’s Degree', self::MASTERS_COURSES);
    }

    public function down(): void
    {
        foreach (['Doctorate Degree', 'Master’s Degree'] as $levelName) {
            $levelId = DB::table('list_data')->where('type', 'Level')->where('name', $levelName)->value('id');

            if ($levelId) {
                DB::table('list_academics')->where('type_id', 174)->where('level_id', $levelId)->delete();
            }
        }
    }

    private function seedLevel(string $levelName, array $courseNames): void
    {
        $levelId = DB::table('list_data')->where('type', 'Level')->where('name', $levelName)->value('id');

        if (!$levelId) {
            // Level row doesn't exist on this environment — nothing sensible to attach to.
            return;
        }

        if (DB::table('list_academics')->where('type_id', 174)->where('level_id', $levelId)->exists()) {
            return;
        }

        DB::table('list_academics')->insert(array_map(fn ($name) => [
            'name' => $name,
            'type_id' => 174,
            'level_id' => $levelId,
        ], $courseNames));
    }
};
