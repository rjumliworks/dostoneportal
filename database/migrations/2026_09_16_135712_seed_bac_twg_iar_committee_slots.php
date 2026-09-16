<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Seeds the vacant BAC / TWG / IAR committee designation rows and their
 * org_charts + org_signatories slots, so /key-officials/{bac,twg,iar} have
 * something to render on any environment. Everything is looked up by
 * name/classification (never hardcoded IDs) and guarded so it's safe to
 * run on an environment that already has some or all of this data.
 */
return new class extends Migration
{
    /**
     * name => Chairperson/Vice Chairperson/Member count per committee, and
     * whether members are seated per-station (IAR) or as one flat pool.
     */
    private const COMMITTEES = [
        'BAC' => ['chairperson' => 1, 'vice_chairperson' => 1, 'members' => 4, 'per_station' => false],
        'TWG' => ['chairperson' => 1, 'vice_chairperson' => 0, 'members' => 3, 'per_station' => false],
        'IAR' => ['chairperson' => 1, 'vice_chairperson' => 0, 'members' => 3, 'per_station' => true],
    ];

    private const STATIONS_FOR_IAR = [
        'Regional Office',
        'Zamboanga Sibugay',
        'Zamboanga Del Norte',
        'Zamboanga Del Sur',
    ];

    public function up(): void
    {
        $regionalOfficeId = DB::table('list_dropdowns')
            ->where('classification', 'Station')
            ->where('name', 'Regional Office')
            ->value('id');

        if (!$regionalOfficeId) {
            // Base station reference data isn't present on this environment
            // (e.g. a bare fresh install) — nothing sensible to attach to.
            return;
        }

        foreach (self::COMMITTEES as $type => $shape) {
            if ($this->committeeAlreadySeeded($type)) {
                continue;
            }

            $chairpersonId = $this->ensureDesignation('Chairperson', $type);
            $viceChairpersonId = $shape['vice_chairperson'] > 0
                ? $this->ensureDesignation('Vice Chairperson', $type)
                : null;
            $memberId = $this->ensureDesignation('Member', $type);

            $now = now();
            $slots = [];

            for ($i = 0; $i < $shape['chairperson']; $i++) {
                $slots[] = ['order' => 1, 'designation_id' => $chairpersonId, 'assigned_id' => $regionalOfficeId];
            }

            for ($i = 0; $i < $shape['vice_chairperson']; $i++) {
                $slots[] = ['order' => 2, 'designation_id' => $viceChairpersonId, 'assigned_id' => $regionalOfficeId];
            }

            if ($shape['per_station']) {
                foreach (self::STATIONS_FOR_IAR as $stationName) {
                    $stationId = DB::table('list_dropdowns')
                        ->where('classification', 'Station')
                        ->where('name', $stationName)
                        ->value('id');

                    if (!$stationId) {
                        continue;
                    }

                    for ($i = 0; $i < $shape['members']; $i++) {
                        $slots[] = ['order' => 2, 'designation_id' => $memberId, 'assigned_id' => $stationId];
                    }
                }
            } else {
                for ($i = 0; $i < $shape['members']; $i++) {
                    $slots[] = ['order' => 3, 'designation_id' => $memberId, 'assigned_id' => $regionalOfficeId];
                }
            }

            foreach ($slots as $slot) {
                $chartId = DB::table('org_charts')->insertGetId([
                    'order' => $slot['order'],
                    'designation_id' => $slot['designation_id'],
                    'assigned_id' => $slot['assigned_id'],
                    'user_id' => null,
                    'oic_id' => null,
                    'is_oic' => 0,
                    'is_active' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                DB::table('org_signatories')->insert([
                    'designationable_id' => $chartId,
                    'designationable_type' => 'App\\Models\\OrgChart',
                    'user_id' => null,
                    'oic_id' => null,
                    'is_oic' => 0,
                    'is_topmanagement' => 0,
                    'is_active' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        foreach (array_keys(self::COMMITTEES) as $type) {
            $chartIds = DB::table('org_charts')
                ->join('list_dropdowns', 'list_dropdowns.id', '=', 'org_charts.designation_id')
                ->where('list_dropdowns.classification', 'Designation')
                ->where('list_dropdowns.type', $type)
                ->whereNull('org_charts.user_id')
                ->whereNull('org_charts.oic_id')
                ->pluck('org_charts.id');

            DB::table('org_signatories')
                ->where('designationable_type', 'App\\Models\\OrgChart')
                ->whereIn('designationable_id', $chartIds)
                ->delete();

            DB::table('org_charts')->whereIn('id', $chartIds)->delete();
        }
    }

    private function committeeAlreadySeeded(string $type): bool
    {
        return DB::table('org_charts')
            ->join('list_dropdowns', 'list_dropdowns.id', '=', 'org_charts.designation_id')
            ->where('list_dropdowns.classification', 'Designation')
            ->where('list_dropdowns.type', $type)
            ->exists();
    }

    private function ensureDesignation(string $name, string $type): int
    {
        $id = DB::table('list_dropdowns')
            ->where('classification', 'Designation')
            ->where('type', $type)
            ->where('name', $name)
            ->value('id');

        if ($id) {
            return $id;
        }

        return DB::table('list_dropdowns')->insertGetId([
            'name' => $name,
            'classification' => 'Designation',
            'type' => $type,
            'color' => 'n/a',
            'others' => 'n/a',
            'is_active' => 1,
        ]);
    }
};
