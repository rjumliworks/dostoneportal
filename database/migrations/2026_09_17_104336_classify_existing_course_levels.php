<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The 178 list_academics rows (type_id=174, Course) seeded before level_id existed
 * are a mix of Bachelor's, Associate, TESDA/vocational (NC-rated), and K-12 Senior
 * High strand/track entries, all left unscoped (level_id null). This backfills
 * level_id by name pattern so "Basic Education / Degree / Course" only offers the
 * relevant programs per selected level, same as the dedicated Doctorate/Master's
 * lists. Level ids are looked up by name (never hardcoded), same reasoning as the
 * Doctorate/Master's seeder. The first-professional "Doctor of ___" / Juris Doctor
 * degrees are folded into Doctorate too. Rows that still don't clearly belong to any
 * of these ("Diploma in Midwifery", "Not Available") are left as-is rather than
 * guessed at.
 *
 * Idempotent by construction — re-running only touches rows still at level_id null,
 * so it's safe to run again on an environment that's already been partially or fully
 * classified.
 */
return new class extends Migration
{
    private const SHS_STRAND_NAMES = [
        'Accountancy, Business and Management Strand',
        'General Academic Strand',
        'Humanities and Social Sciences Strand',
        'Science, Technology, Engineering and Mathematics Strand',
        'Technical-Vocational Livelihood Track',
    ];

    // First-professional "Doctor of ___" / "Juris Doctor" degrees — entered after a
    // Bachelor's rather than a Master's like PhD/EdD, but this system has no separate
    // "Professional" tier, so Doctorate is the closest real fit.
    private const PROFESSIONAL_DOCTORATE_NAMES = [
        'Doctor of Medicine',
        'Doctor of Dental Medicine',
        'Doctor of Optometry',
        'Doctor of Veterinary Medicine',
        'Juris Doctor',
    ];

    public function up(): void
    {
        $this->applyByLikePrefix('Bachelor%', 'Bachelor’s Degree');
        $this->applyByLikePrefix('Associate%', 'Associate Degree');
        $this->applyToShsStrands();
        $this->applyToVocational();
        $this->applyToProfessionalDoctorates();
    }

    public function down(): void
    {
        // Not reversed — this only fills in previously-null level_id values, and we
        // can't distinguish "was null before this migration" from "set to null by
        // something else afterward" to safely revert.
    }

    private function levelId(string $levelName): ?int
    {
        return DB::table('list_data')->where('type', 'Level')->where('name', $levelName)->value('id');
    }

    private function applyByLikePrefix(string $likePattern, string $levelName): void
    {
        $levelId = $this->levelId($levelName);

        if (!$levelId) {
            return;
        }

        DB::table('list_academics')
            ->where('type_id', 174)
            ->whereNull('level_id')
            ->where('name', 'like', $likePattern)
            ->update(['level_id' => $levelId]);
    }

    private function applyToShsStrands(): void
    {
        $levelId = $this->levelId('Senior High School');

        if (!$levelId) {
            return;
        }

        DB::table('list_academics')
            ->where('type_id', 174)
            ->whereNull('level_id')
            ->whereIn('name', self::SHS_STRAND_NAMES)
            ->update(['level_id' => $levelId]);
    }

    private function applyToVocational(): void
    {
        $levelId = $this->levelId('Vocational');

        if (!$levelId) {
            return;
        }

        DB::table('list_academics')
            ->where('type_id', 174)
            ->whereNull('level_id')
            ->where(function ($query) {
                $query->whereRaw("name REGEXP ' NC (I|II|III)$'")
                    ->orWhere('name', 'like', 'Trainers Methodology%');
            })
            ->update(['level_id' => $levelId]);
    }

    private function applyToProfessionalDoctorates(): void
    {
        $levelId = $this->levelId('Doctorate Degree');

        if (!$levelId) {
            return;
        }

        DB::table('list_academics')
            ->where('type_id', 174)
            ->whereNull('level_id')
            ->whereIn('name', self::PROFESSIONAL_DOCTORATE_NAMES)
            ->update(['level_id' => $levelId]);
    }
};
