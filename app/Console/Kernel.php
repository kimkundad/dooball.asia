<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $filePath = url('output.log');
        $schedule->command('dooball:sync-match')->everyMinute();
        $schedule->command('dooball:sync-match')
         ->daily()
         ->sendOutputTo($filePath);
        $schedule->command('dooball:sync-db')->everyMinute();
        // $schedule->command('dooball:arrange-ffp-main')->everyFiveMinutes();
        $schedule->command('dooball:delete-ffp-db')->everyTenMinutes();
        // $schedule->command('dooball:delete-ffp-log')->everyTenMinutes();
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

