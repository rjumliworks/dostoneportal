<?php

namespace App\Console\Commands;

use App\Models\OrgSignatorySchedule;
use Illuminate\Console\Command;

class SignatoryCommand extends Command
{
    protected $signature = 'signatory:update';
    protected $description = 'Automatically update scheduled signatory';

    public function handle()
    {
        $schedules = OrgSignatorySchedule::whereDate('end_at', today())
        ->where('end_at', '<=', now())
        ->where('is_completed', 0)
        ->where('is_ongoing', 1)
        ->with('signatory')
        ->get();

        foreach ($schedules as $schedule) {
            $signatory = $schedule->signatory;
            $parent    = $signatory?->designationable;

            if ($signatory->is_oic == 1) {
                $signatory->update([
                    'oic_id' => null,
                    'is_oic' => 0,
                ]);
            }else{
                $signatory->update([
                    'user_id' => null
                ]);
                $parent->update([
                    'oic_id' => null,
                    'is_oic' => 0,
                ]);
            }
             $schedule->update([
                'is_ongoing'   => 0,
                'is_completed' => 1,
            ]);
            // $signatory = $schedule->signatory;
            // $parent    = $signatory?->designationable;

            // if ($schedule->signatory) {
            //     $schedule->signatory->update([
            //         'oic_id' => null,
            //     ]);
            // }
            // $schedule->update([
            //     'is_ongoing'   => 0,
            //     'is_completed' => 1,
            // ]);
        }

    }
}
