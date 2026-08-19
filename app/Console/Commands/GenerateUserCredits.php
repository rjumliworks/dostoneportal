<?php

namespace App\Console\Commands;

use App\Services\HumanResource\Credit\SaveClass;
use Illuminate\Console\Command;

class GenerateUserCredits extends Command
{
    protected $signature = 'credits:generate {user? : ID of a single, just-created employee to generate credits for. Omit to bulk-populate every existing active regular/non-regular employee}';
    protected $description = 'Generate initial leave credit balances and logs for regular/non-regular employees';

    public function handle(SaveClass $save)
    {
        $userId = $this->argument('user');

        $result = $save->store($userId);

        $this->info($result['message']);

        return self::SUCCESS;
    }
}
