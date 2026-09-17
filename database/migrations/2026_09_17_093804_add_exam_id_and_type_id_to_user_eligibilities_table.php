<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * user_eligibilities.exam_name used to be free text (or a Multiselect create-option
 * value). Going forward the "Eligibility / Exam" field is a real list_data lookup
 * (type=Eligibility), with a second type_id lookup (list_data type=Exam) for the
 * specific PRC board/bar exam when the RA 1080 "Bar and Board Examination" entry is
 * picked. exam_name is kept and still populated on save (denormalized) so existing
 * reports/views that read it keep working untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_eligibilities', function (Blueprint $table) {
            $table->unsignedSmallInteger('exam_id')->nullable()->after('exam_name');
            $table->unsignedSmallInteger('type_id')->nullable()->after('exam_id');
            $table->foreign('exam_id')->references('id')->on('list_data')->onDelete('set null');
            $table->foreign('type_id')->references('id')->on('list_data')->onDelete('set null');
        });

        // Best-effort backfill: only where the old free-text name is an exact match
        // against an existing Eligibility lookup row. Anything that doesn't match
        // (typos, custom text typed via the old create-option dropdown) is left with
        // a null exam_id and keeps displaying via the legacy exam_name column.
        DB::statement(<<<SQL
            UPDATE user_eligibilities ue
            INNER JOIN list_data ld ON ld.name = ue.exam_name AND ld.type = 'Eligibility'
            SET ue.exam_id = ld.id
            WHERE ue.exam_id IS NULL
        SQL);
    }

    public function down(): void
    {
        Schema::table('user_eligibilities', function (Blueprint $table) {
            $table->dropForeign(['exam_id']);
            $table->dropForeign(['type_id']);
            $table->dropColumn(['exam_id', 'type_id']);
        });
    }
};
