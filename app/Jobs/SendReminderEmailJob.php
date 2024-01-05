<?php

namespace App\Jobs;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendReminderEmailJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $reminder;

  /**
   * Create a new job instance.
   */
  public function __construct(Reminder $reminder)
  {
    $this->reminder = $reminder;
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    $userName = $this->reminder->user->name;
    $userEmail = $this->reminder->user->email;
    $reminderTitle = $this->reminder->title;
    $reminderDescription = $this->reminder->description;
    $reminderEventDate = date('d F Y', $this->reminder->event_at);
    $reminderEventTime = date('h:i A', $this->reminder->event_at);

    Mail::send('emails.reminder', [
      'userName' => $userName,
      'title' => $reminderTitle,
      'description' => $reminderDescription,
      'eventDate' => $reminderEventDate,
      'eventTime' => $reminderEventTime,
    ], function ($message) use ($userEmail, $reminderTitle) {
      $message->to($userEmail)->subject('🔔 Reminder: ' . $reminderTitle);

      $this->reminder->is_sent = true;
      $this->reminder->save();
    });
  }
}
