<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class RegisterApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
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