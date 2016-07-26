<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function test_se_retorna_la_api_key_cuando_se_presenta_un_token_valido()
    {
        $user = factory(App\User::class)->create([
                'remember_token' => 'valid_remember_token',
                'api_token' => 'valid_api_token',
            ]);

        $this->json('GET', "/api/v1/users", ['token' => $user->remember_token])
             ->assertResponseOk()
             ->seeJson([
                    'success' => true,
                    'token' => 'valid_api_token',
                ]);
    }


    public function test_no_se_retorna_la_api_key_cuando_se_presenta_un_token_no_valido()
    {
        $user = factory(App\User::class)->create([
                'remember_token' => 'valid_remember_token',
                'api_token' => 'valid_api_token',
            ]);

        $this->json('GET', "/api/v1/users", ['token' => 'invalid_remember_token'])
             ->assertResponseStatus(400)
             ->dontSeeJson([
                    'success' => true,
                    'token' => 'valid_api_token',
                ]);
    }


    public function test_el_token_se_invalida_cuando_se_consulta_la_api_key()
    {
        $user = factory(App\User::class)->create([
                'remember_token' => 'valid_remember_token',
                'api_token' => 'valid_api_token',
            ]);

        $this->json('GET', "/api/v1/users", ['token' => $user->remember_token])
             ->assertResponseOk()
             ->seeJson([
                    'success' => true,
                    'token' => 'valid_api_token',
                ]);

        $this->dontSeeInDatabase('users', ['remember_token' => 'valid_remember_token']);
    }

    public function test_se_produce_un_evento_al_registrar_un_usuario()
    {
        $this->expectsEvents(App\Events\UserWasCreated::class);

        $this->post("/api/v1/users", ['email' => 'jrodalo@gmail.com'])
             ->assertResponseOk()
             ->seeJson([
                'success' => true,
            ]);
    }

}
