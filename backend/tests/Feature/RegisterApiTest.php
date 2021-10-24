<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_createAndReturnNewUser()
    {
        $data = [
             'name' => 'Recordable User',
             'email' => 'dummy@dummy.com',
             'password' => 'dummydegozaru',
             'password_confirmation' => 'dummydegozaru',
         ];

        $response = $this->json('POST', route('register'), $data);

        $user = User::first();
        $this->assertEquals($data['name'], $user->name);

        $response
            ->assertStatus(201)
            ->assertJson(['name' => $user->name]);
    }
}
