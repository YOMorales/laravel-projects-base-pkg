<?php

namespace App\Console\Schedules;

use Illuminate\Console\Scheduling\Schedule;

class MiscSchedule
{
    /**
     * Define the command schedule for Misc processes.
     */
    public static function schedule(Schedule $schedule): void
    {
        $schedule->command('queue:monitor redis:default,redis:queue --max=20')->everySixHours();

        $schedule->command('cache:prune-stale-tags')->hourly();
    }
}
