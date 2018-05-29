<?php

namespace App\Console;

use App\Console\Commands\CreateAdmin;
use App\Console\Commands\RefreshDatabase;
use Chief\Authorization\Console\GeneratePermissionCommand;
use Chief\Authorization\Console\GenerateRoleCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $daysBeforeDeletion = 15;
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
//        ChiefSetupCommand::class,
        GeneratePermissionCommand::class,
        GenerateRoleCommand::class,
        CreateAdmin::class,
        RefreshDatabase::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Page::onlyTrashed()->where('deleted_at', '<', Carbon::today()->subDays($this->daysBeforeDeletion))->forceDelete();
        })->dailyAt('03:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
