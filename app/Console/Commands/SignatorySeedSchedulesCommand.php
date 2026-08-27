<?php

namespace App\Console\Commands;

use App\Models\OrgSignatory;
use App\Models\OrgSignatorySchedule;
use App\Models\RequestSignatory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SignatorySeedSchedulesCommand extends Command
{
    protected $signature = 'signatory:seed-schedules {--force : Proceed even if request_signatories rows reference existing schedules}';
    protected $description = 'Empty org_signatory_schedules and rebuild one full-year schedule per currently designated signatory';

    public function handle()
    {
        $referenced = RequestSignatory::whereNotNull('recommended_id')
            ->orWhereNotNull('approved_id')
            ->orWhereNotNull('disapproved_id')
            ->exists();

        if ($referenced && ! $this->option('force')) {
            $this->error('request_signatories rows reference existing org_signatory_schedules. Deleting schedules would cascade-delete those approval records. Re-run with --force to proceed anyway.');
            return self::FAILURE;
        }

        $start = now()->startOfYear()->toDateString();
        $end = now()->endOfYear()->toDateString();

        $count = DB::transaction(function () use ($start, $end) {
            OrgSignatorySchedule::query()->delete();

            $signatories = OrgSignatory::whereNotNull('user_id')->get();

            foreach ($signatories as $signatory) {
                $signatory->schedules()->create([
                    'start_at' => $start,
                    'end_at' => $end,
                    'user_id' => $signatory->user_id,
                    'is_designated' => 1,
                    'is_ongoing' => 1,
                    'is_completed' => 0,
                ]);
            }

            return $signatories->count();
        });

        $this->info("Rebuilt {$count} designated signatory schedule(s) for {$start} to {$end}.");
        return self::SUCCESS;
    }
}
