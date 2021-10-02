<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class LoginApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void //エラーになる？
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function should_returnRegisterdNewUser()
    {
        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(['name' => $this->user->name]);

        $this->assertAuthenticatedAs($this->user);
    }

}