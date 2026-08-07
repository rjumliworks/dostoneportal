<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetUserInformation extends Command
{
    protected $signature = 'users:reset-information {--force : Skip the confirmation prompt}';
    protected $description = 'Delete every row in user_information and recreate a fresh default row for each user. Only user_information is touched.';

    public function handle()
    {
        $userCount = User::count();

        if (! $this->option('force') && ! $this->confirm(
            "This will permanently delete ALL {$userCount} existing user_information rows (accounts, backgrounds, contacts, personal data) and replace them with blank defaults. No other table is touched. This cannot be undone. Continue?"
        )) {
            $this->info('Aborted. No changes were made.');
            return self::SUCCESS;
        }

        DB::transaction(function () {
            Schema::disableForeignKeyConstraints();
            UserInformation::truncate();
            Schema::enableForeignKeyConstraints();

            $bar = $this->output->createProgressBar(User::count());
            $bar->start();

            User::select('id')->chunkById(200, function ($users) use ($bar) {
                foreach ($users as $user) {
                    UserInformation::createDefaultFor($user->id);
                    $bar->advance();
                }
            });

            $bar->finish();
        });

        $this->newLine(2);
        $this->info('Done. Every user now has a fresh user_information row.');

        return self::SUCCESS;
    }
}
