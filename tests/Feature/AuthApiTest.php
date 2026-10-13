<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{

    public function test_user_can_login()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin123',
            'password' => 'admin1233',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'user']);
    }

    public function test_unauthenticated_user_cannot_access_blog()
    {
        $response = $this->getJson('/api/blog');
        $response->assertStatus(401);
    }
}
