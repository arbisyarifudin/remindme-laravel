<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function it_returns_error_if_email_not_provided()
    {
        $response = $this->postJson('/api/session', [
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'msg' => [
                    'email' => [
                        'The email field is required.'
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_returns_error_if_password_not_provided()
    {
        $response = $this->postJson('/api/session', [
            'email' => 'random@example.com',
            'password' => '',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'msg' => [
                    'password' => [
                        'The password field is required.'
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_returns_error_if_email_not_registered()
    {
        $response = $this->postJson('/api/session', [
            'email' => 'nonexistent@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'msg' => 'incorrect email or password'
            ]);
    }

    /** @test */
    public function it_returns_error_if_password_does_not_match()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/session', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'msg' => 'incorrect email or password',
            ]);
    }

    /** @test */
    public function it_returns_user_and_tokens_if_credentials_match()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/session', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'access_token',
                    'refresh_token',
                ]
            ]);
    }

    /** @test */
    public function it_returns_error_if_token_not_provided_when_fetch_user()
    {
        $response = $this->getJson('/api/session');

        $response->assertStatus(401)
            ->assertJson([
                'msg' => 'invalid access token'
            ]);
    }

    /** @test */
    public function it_returns_user_data_when_fetch_user()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user)->getJson('/api/session');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                ]
            ]);
    }

    /** @test */
    public function it_returns_new_access_token_and_refresh_token_when_refresh_token()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user)->putJson('/api/session');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'refresh_token',
                ]
            ]);
    }

    /** @test */
    public function it_returns_error_if_token_not_provided_when_logout()
    {
        $response = $this->deleteJson('/api/session');

        $response->assertStatus(401)
            ->assertJson([
                'msg' => 'invalid access token'
            ]);
    }

    /** @test */
    public function it_returns_success_when_logout()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user)->deleteJson('/api/session');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => []
            ]);
    }
}
