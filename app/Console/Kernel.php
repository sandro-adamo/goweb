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
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call('App\Http\Controllers\RepresentanteController@atualizaRepresentantes')->cron("00 06 * * *");
        $schedule->call('App\Http\Controllers\JobController@atualizaVendasCML')->cron("30 23 * * *");
        $schedule->call('App\Http\Controllers\ItemController@faltaFoto')->cron("00 08 * * 1");

		//$schedule->call('App\Http\Controllers\StatusProcessaKeringController@atualizaprocessa')->cron("30 19 * * *");
        //$schedule->call('App\Http\Controllers\CompraController@enviaatrasos')->cron("15 12 * * *");


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
