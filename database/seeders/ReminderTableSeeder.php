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
      '+2 days 10:00 am',
      '+3 days 10:00 am',
    ];

    foreach ($users as $user) {
      for ($i = 0; $i < 3; $i++) {
        // get eventAt as unix timestamp from $remindDates array of string
        $randomIndex = array_rand($remindDates);
        $eventAt = strtotime($remindDates[$randomIndex]);

        // get remindAt - 15 minutes as unix timestamp
        $remindAt = strtotime('-15 minutes', $eventAt);

        // get description with multiple paragraphs
        $description = '';
        for ($j = 0; $j < 3; $j++) {
          $description .= $faker->paragraph;
        }

        Reminder::create([
          'title' => str_replace('.', '', $faker->sentence),
          'user_id' => $user->id,
          'description' => $description,
          'remind_at' => $remindAt,
          'event_at' => $eventAt,
        ]);
      }
    }
  }
}
