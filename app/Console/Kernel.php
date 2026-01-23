<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\User;
use App\Services\TradingRiskManager;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        $schedule->command('stocks:fetch-daily-prices')
            // ->dailyAt('17:00')
            ->timezone('Asia/Kolkata')
            ->withoutOverlapping();

        // // Auto-create next month trading risk plan
        // $schedule->call(function () {
        //     User::where('is_active', true)->each(function ($user) {
        //         app(TradingRiskManager::class)
        //             ->createNextMonthRiskPlan($user->id);
        //     });
        // })
        //     ->monthlyOn(1, '00:05')     // 1st day of month at 12:05 AM
        //     ->withoutOverlapping();

        // // Delete expired option contracts daily at 1:00 AM
        // $schedule->command('options:delete-expired')
        //     ->dailyAt('01:00')
        //     ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
