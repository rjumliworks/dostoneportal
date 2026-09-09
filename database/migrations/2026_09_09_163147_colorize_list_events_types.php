<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Every "official event" type (Training, Workshop, Meeting, etc.) shared
     * the same bg-info color, so they were indistinguishable on the
     * /schedules calendar. Holiday (bg-dark) and Work Suspension (bg-danger)
     * are left untouched.
     *
     * Only the 8 standard Bootstrap theme colors (primary, secondary,
     * success, danger, warning, info, dark, light) actually have utility
     * classes compiled in this build - custom $colors map entries like
     * purple/pink/orange/teal do not. Their "-subtle" variants do exist
     * (used throughout the app for badges), which gives 13 real, visually
     * distinct combinations - enough to cover all 12 types with no reuse.
     */
    protected function colors(): array
    {
        return [
            'Training'     => ['bg-primary',        'text-white'],
            'Workshop'     => ['bg-secondary',       'text-white'],
            'Seminar'      => ['bg-success',         'text-white'],
            'Assembly'     => ['bg-warning',         'text-dark'],
            'Meeting'      => ['bg-info',            'text-white'],
            'Benchmarking' => ['bg-primary-subtle',  'text-primary'],
            'Fieldwork'    => ['bg-secondary-subtle','text-secondary'],
            'Audit'        => ['bg-success-subtle',  'text-success'],
            'RSTW'         => ['bg-warning-subtle',  'text-warning'],
            'NSTW'         => ['bg-info-subtle',     'text-info'],
            'Conference'   => ['bg-danger-subtle',   'text-danger'],
            'Forum'        => ['bg-dark-subtle',     'text-dark'],
        ];
    }

    public function up(): void
    {
        foreach ($this->colors() as $name => [$bg, $color]) {
            DB::table('list_events')->where('name', $name)->update([
                'bg' => $bg,
                'color' => $color,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('list_events')->whereIn('name', array_keys($this->colors()))->update([
            'bg' => 'bg-info',
            'color' => 'text-white',
        ]);
    }
};
