<?php

namespace App\Console\Commands;

use App\Models\EventSessionParticipant;
use App\Models\ParticipantPoint;
use App\Models\ParticipantPointLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetParticipantPoints extends Command
{
    protected $signature = 'participants:reset-points {--force : Skip the confirmation prompt}';
    protected $description = 'Truncate participant_points and participant_point_logs, and mark session 1 participants with both attended_at and image set as status_id 58.';

    public function handle()
    {
        $pointCount = ParticipantPoint::count();
        $logCount = ParticipantPointLog::count();
        $attendanceCount = EventSessionParticipant::whereNotNull('attended_at')
            ->whereNotNull('image')
            ->count();

        if (! $this->option('force') && ! $this->confirm(
            "This will permanently delete all {$pointCount} participant_points row(s) and {$logCount} participant_point_logs row(s), and set status_id = 58 on {$attendanceCount} session 1 participant(s) with both attended_at and image already recorded. This cannot be undone. Continue?"
        )) {
            $this->info('Aborted. No changes were made.');
            return self::SUCCESS;
        }

        DB::transaction(function () use ($attendanceCount) {
            Schema::disableForeignKeyConstraints();
            ParticipantPointLog::truncate();
            ParticipantPoint::truncate();
            Schema::enableForeignKeyConstraints();

            EventSessionParticipant::whereNotNull('attended_at')
                ->whereNotNull('image')
                ->update(['status_id' => 58, 'image' => null, 'attended_at' => null]);

            $this->info("Reset participant_points and participant_point_logs. Marked {$attendanceCount} session 1 participant(s) as status_id 58.");
        });

        return self::SUCCESS;
    }
}
