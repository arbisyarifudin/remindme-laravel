<?php

namespace App\Console;

use App\Jobs\SendReminderEmailJob;
use App\Models\Reminder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {

            // get reminder data that remind_at is not null and is_sent is false,
            // and time now is greater than or equal to remind_at AND time now is less than or equal to event_at
            $reminders = Reminder::whereNotNull('remind_at')
                ->where('is_sent', false)
                ->where('remind_at', '<=', now()->timestamp)
                ->where('event_at', '>=', now()->timestamp)
                ->get();

            foreach ($reminders as $reminder) {
                dispatch(new SendReminderEmailJob($reminder));
            }
        })->everyMinute();
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
