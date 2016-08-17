<?php

use Hash;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    public function test_se_retorna_la_api_key_cuando_se_presenta_un_usuario_valido()
    {
        $user = factory(App\User::class)->create([
                'email' => 'valid@sopplis.com',
                'password' => Hash::make('test'),
                'api_token' => 'valid_api_token',
            ]);

        $this->post("/api/v1/users", ['email' => $user->email, 'password' => 'test'])
             ->assertResponseOk()
             ->seeJson([
                    'success' => true,
                    'token' => 'valid_api_token',
                ]);
    }


    public function test_no_se_retorna_la_api_key_cuando_se_presenta_un_usuario_no_valido()
    {
        $user = factory(App\User::class)->create([
                'email' => 'valid@sopplis.com',
                'password' => Hash::make('test'),
                'api_token' => 'valid_api_token',
            ]);

        $this->post("/api/v1/users", ['email' => 'valid@sopplis.com', 'password' => 'invalid_password'])
             ->assertResponseStatus(400)
             ->dontSeeJson([
                    'success' => true,
                    'token' => 'valid_api_token',
                ]);
    }
}
