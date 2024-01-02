<?php

namespace Database\Seeders;

use App\Models\Reminder;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReminderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // if env is local, truncate reminders table
        if (app()->environment('local')) {
            Reminder::truncate();
        }

        $faker = FakerFactory::create();
        $users = User::all();

        $remindDates = [
            'today 09:00 am',
            'today 12:00 pm',
            'tomorrow 10:00 am',
            '2024/01/04 20:00:00',
        ];

        foreach ($users as $user) {
            for ($i = 0; $i < 3; $i++) {
                // get reminder event_at unix timestamp (int) from remindDates array
                $eventAt = strtotime($remindDates[array_rand($remindDates)]);

                // get remind_at unix timestamp (int) from event_at unix timestamp (int) minus 15 minutes
                $remindAt = $eventAt - (15 * 60);

                // get description with multiple paragraphs
                $description = '';
                for ($j = 0; $j < 3; $j++) {
                    $description .= $faker->paragraph;
                }

                Reminder::create([
                    'title' => $faker->sentence,
                    'user_id' => $user->id,
                    'description' => $description,
                    'remind_at' => $remindAt,
                    'event_at' => $eventAt,
                ]);
            }
        }
    }
}
