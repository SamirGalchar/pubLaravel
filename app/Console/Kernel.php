<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SubscriptionCron;
use Illuminate\Support\Stringable;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SubscriptionCron::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        
        //$schedule->command('subscription:cron')->everyMinute();
        $schedule->command('mailafter48hours:cron')->daily();
        /* For queue job worker process */
        $schedule->command('queue:work')->everyMinute()->runInBackground()
                ->onSuccess(function (Stringable $output) {  })
                ->onFailure(function (Stringable $output) { \Log::info($output); }); 
        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

}
