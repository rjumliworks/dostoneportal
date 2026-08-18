<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

// Schedule::command('signatory:update')->weekdays()->dailyAt('20:00');
Schedule::command('dtr:finalize')->weekdays()->dailyAt('20:00');
Schedule::command('signatory:update')->weekdays()->dailyAt('23:00');

// Guard shift rotation: runs just after Sunday's extended coverage shift begins its
// overnight leg, so the new week's assignment is in place well before Monday 7am.
Schedule::command('shift:rotate-guards')->weeklyOn(1, '00:05');