<?php

namespace Tests\Unit;

use App\Models\Reminder;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReminderCreateTest extends TestCase
{
  use DatabaseMigrations;
  use DatabaseTransactions;

  /** @test */
  public function it_returns_error_if_user_not_authenticated()
  {
    $response = $this->postJson('/api/reminders', [
      'user_id' => 1,
      'title' => 'Reminder title',
      'description' => 'Reminder description',
      'event_at' => now()->timestamp,
      'remind_at' => now()->subMinutes(15)->timestamp,
    ]);

    $response->assertStatus(401)
      ->assertJson([
        'msg' => 'invalid access token'
      ]);
  }

  /** @test */
  public function it_returns_error_if_title_not_provided()
  {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
      ->postJson('/api/reminders', [
        'user_id' => $user->id,
        'title' => '',
        'description' => 'Reminder description',
        'event_at' => now()->timestamp,
        'remind_at' => now()->subMinutes(15)->timestamp,
      ]);

    $response->assertStatus(422)
      ->assertJson([
        'msg' => [
          'title' => [
            'The title field is required.'
          ]
        ]
      ]);
  }

  /** @test */
  public function it_returns_error_if_event_at_not_provided()
  {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
      ->postJson('/api/reminders', [
        'user_id' => $user->id,
        'title' => 'Reminder title',
        'description' => 'Reminder description',
        'event_at' => '',
        'remind_at' => now()->subMinutes(15)->timestamp,
      ]);

    $response->assertStatus(422)
      ->assertJson([
        'msg' => [
          'event_at' => [
            'The event at field is required.'
          ]
        ]
      ]);
  }

  /** @test */
  public function it_returns_error_if_event_at_is_not_integer()
  {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
      ->postJson('/api/reminders', [
        'user_id' => $user->id,
        'title' => 'Reminder title',
        'description' => 'Reminder description',
        'event_at' => 'not integer',
        'remind_at' => now()->subMinutes(15)->timestamp,
      ]);

    $response->assertStatus(422)
      ->assertJson([
        'msg' => [
          'event_at' => [
            'The event at field must be an integer.'
          ]
        ]
      ]);
  }

  /** @test */
  public function it_returns_error_if_remind_at_is_not_integer()
  {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
      ->postJson('/api/reminders', [
        'user_id' => $user->id,
        'title' => 'Reminder title',
        'description' => 'Reminder description',
        'event_at' => now()->timestamp,
        'remind_at' => 'not integer',
      ]);

    $response->assertStatus(422)
      ->assertJson([
        'msg' => [
          'remind_at' => [
            'The remind at field must be an integer.'
          ]
        ]
      ]);
  }

  /** @test */
  public function it_returns_error_if_title_and_event_at_already_exists()
  {
    $user = User::factory()->create();

    $eventAt = now()->timestamp;

    $reminder = Reminder::factory()->create([
      'user_id' => $user->id,
      'title' => 'Reminder title',
      'description' => 'Reminder description',
      'event_at' => $eventAt,
      'remind_at' => now()->subMinutes(15)->timestamp,
    ]);

    $response = $this->actingAs($user)
      ->postJson('/api/reminders', [
        'user_id' => $user->id,
        'title' => 'Reminder title',
        'description' => 'Reminder description',
        'event_at' => $eventAt,
        'remind_at' => now()->subMinutes(15)->timestamp,
      ]);

    $response->assertStatus(422)
      ->assertJson([
        'msg' => 'Reminder with this title and schedule is already exists!'
      ]);
  }

  /** @test */
  public function it_returns_success_if_request_valid()
  {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
      ->postJson('/api/reminders', [
        'user_id' => $user->id,
        'title' => 'Reminder title',
        'description' => 'Reminder description',
        'event_at' => now()->timestamp,
        'remind_at' => now()->subMinutes(15)->timestamp,
      ]);

    $response->assertStatus(201)
      ->assertJson([
        'ok' => true,
        'data' => [
          'id' => 1,
          'title' => 'Reminder title',
          'description' => 'Reminder description',
        ]
      ]);
  }
}
