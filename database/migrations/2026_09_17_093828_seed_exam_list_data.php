<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Seeds list_data (type=Exam) with the specific PRC board / bar examinations for
 * PDS item 27, selected when a user picks the RA 1080 "Bar and Board Examination"
 * entry (list_data type=Eligibility) in their eligibility record. Cross-checked
 * against PRC's summary list of professional regulatory boards (posted 18 March
 * 2026): https://www.prc.gov.ph/article/summary-list-professional-regulatory-boards-names-licensure-examinations-and-abbreviations
 *
 * Guarded to skip entirely if this environment already has Exam-type rows, so it's
 * safe to run on an environment that already got this data some other way.
 */
return new class extends Migration
{
    private const NAMES = [
        // Supreme Court
        'Lawyer (Bar Examination)',
        "Shari'a Counselor (Shari'a Bar Examination)",

        // Health
        'Dental Hygienist / Dental Technologist (RA 1080 Board)',
        'Dentist (RA 1080 Board)',
        'Medical Technologist (RA 1080 Board)',
        'Midwife (RA 1080 Board)',
        'Nurse (RA 1080 Board)',
        'Nutritionist-Dietitian (RA 1080 Board)',
        'Occupational Therapist (RA 1080 Board)',
        'Optometrist (RA 1080 Board)',
        'Pharmacist (RA 1080 Board)',
        'Physical Therapist (RA 1080 Board)',
        'Physician (RA 1080 Board)',
        'Radiologic Technologist (RA 1080 Board)',
        'Respiratory Therapist (RA 1080 Board)',
        'Veterinarian (RA 1080 Board)',
        'X-Ray Technologist (RA 1080 Board)',

        // Engineering
        'Aeronautical Engineer (RA 1080 Board)',
        'Agricultural and Biosystems Engineer (RA 1080 Board)',
        'Certified Plant Mechanic (RA 1080 Board)',
        'Chemical Engineer (RA 1080 Board)',
        'Civil Engineer (RA 1080 Board)',
        'Electrical Engineer (RA 1080 Board)',
        'Electronics Engineer (RA 1080 Board)',
        'Electronics Technician (RA 1080 Board)',
        'Geodetic Engineer (RA 1080 Board)',
        'Master Electrician (RA 1080 Board)',
        'Mechanical Engineer (RA 1080 Board)',
        'Metallurgical Engineer (RA 1080 Board)',
        'Mining Engineer (RA 1080 Board)',
        'Naval Architect and Marine Engineer (RA 1080 Board)',
        'Sanitary Engineer (RA 1080 Board)',

        // Technology / Built & Natural Environment / Sciences
        'Agriculturist (RA 1080 Board)',
        'Architect (RA 1080 Board)',
        'Chemical Technician (RA 1080 Board)',
        'Chemist (RA 1080 Board)',
        'Environmental Planner (RA 1080 Board)',
        'Fisheries Professional (RA 1080 Board)',
        'Food Technologist (RA 1080 Board)',
        'Forester (RA 1080 Board)',
        'Geologist (RA 1080 Board)',
        'Interior Designer (RA 1080 Board)',
        'Landscape Architect (RA 1080 Board)',
        'Master Plumber (RA 1080 Board)',

        // Business
        'Certified Public Accountant (RA 1080 Board)',
        'Customs Broker (RA 1080 Board)',
        'Real Estate Appraiser (RA 1080 Board)',
        'Real Estate Broker (RA 1080 Board)',
        'Real Estate Consultant (RA 1080 Board)',

        // Education / Social Sciences
        'Criminologist (RA 1080 Board)',
        'Guidance Counselor (RA 1080 Board)',
        'Librarian (RA 1080 Board)',
        'Professional Teacher - Elementary (RA 1080 Board)',
        'Professional Teacher - Secondary (RA 1080 Board)',
        'Psychologist (RA 1080 Board)',
        'Psychometrician (RA 1080 Board)',
        'Social Worker (RA 1080 Board)',

        // Catch-all
        'Other Board / Bar Examination (RA 1080)',
    ];

    public function up(): void
    {
        if (DB::table('list_data')->where('type', 'Exam')->exists()) {
            return;
        }

        $now = now();

        DB::table('list_data')->insert(array_map(fn ($name) => [
            'name' => $name,
            'type' => 'Exam',
            'is_active' => 1,
        ], self::NAMES));
    }

    public function down(): void
    {
        DB::table('list_data')->where('type', 'Exam')->whereIn('name', self::NAMES)->delete();
    }
};
