<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    public function test_se_retorna_la_api_key_cuando_se_presenta_un_usuario_valido()
    {
        $user = factory(User::class)->create([
                'email' => 'valid@sopplis.com',
                'password' => Hash::make('test'),
                'api_token' => 'valid_api_token',
            ]);

        $response = $this->json('POST', '/api/v1/sessions', ['email' => $user->email, 'password' => 'test']);

        $response
            ->assertStatus(200)
            ->assertJson([
                    'success' => true,
                    'token' => 'valid_api_token',
                ]);
    }

    public function test_no_se_retorna_la_api_key_cuando_se_presenta_un_usuario_no_valido()
    {
        $user = factory(User::class)->create([
                'email' => 'valid@sopplis.com',
                'password' => Hash::make('test'),
                'api_token' => 'valid_api_token',
            ]);

        $response = $this->json('POST', '/api/v1/sessions', ['email' => 'valid@sopplis.com', 'password' => 'invalid_password']);

        $response
            ->assertStatus(401)
            ->assertJson([
                    'success' => false,
                ]);
    }
}
