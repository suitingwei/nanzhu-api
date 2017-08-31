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
        Commands\Inspire::class,
        Commands\FulfillGroupUserSparePhones::class,
        Commands\FulfillHxUserAndGroup::class,
        Commands\ClearHxUsersAndGroups::class,
        Commands\MakeDirectorDepartmentEssential::class,
        Commands\DeployVersion3_4_1::class,
        Commands\CheckPurchaseTimeLeftToPay::class,
        Commands\DeployVersion3_5_1::class,
        Commands\FulfillMissingMessageReceivers::class,
        Commands\AddMovieClothServiceUserFriend::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('purchase:cancel_time_expired')->everyMinute()
                 ->appendOutputTo(storage_path('schedule/schedule.log'));
    }
}
