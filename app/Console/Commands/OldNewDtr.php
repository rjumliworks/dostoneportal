<?php

namespace App\Console\Commands;

use App\Models\Dtr;
use App\Models\OldDtr;
use App\Models\OldUser;
use Carbon\Carbon;
use Illuminate\Console\Command;

class OldNewDtr extends Command
{
     protected $signature = 'dtr:update';
    protected $description = 'Link new DTR records to old DTR records using old_id';

    public function handle()
    {
        $this->info('Starting DTR linking...');

        // preload old users
        $oldUsers = OldUser::pluck('id', 'username');

        $normalizedOldUsers = $oldUsers->mapWithKeys(function ($id, $username) {
            return [str_replace('-', '', $username) => $id];
        });

        $dtrs = Dtr::with('user.profile')->get();

        $count = 0;

        foreach ($dtrs as $dtr) {

            $username = $dtr->user->username ?? null;

            if (!$username) {
                continue;
            }

            $normalized = str_replace('-', '', $username);

            $oldUserId = $normalizedOldUsers[$normalized] ?? null;

            if (!$oldUserId) {
                continue;
            }

            $date = Carbon::parse($dtr->date)->format('Y-m-d');

            $oldDtr = OldDtr::where('user_id', $oldUserId)
                ->whereDate('date', $date)
                ->first();

            if ($oldDtr) {
                $dtr->old_id = $oldDtr->id;
                $dtr->save();
                $count++;
            }
        }

        $this->info("Done. Linked {$count} records.");
    }
}
