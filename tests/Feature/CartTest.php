<?php

namespace Tests\Unit;

use App\Cart;
use App\Item;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CartTest extends TestCase
{
    use DatabaseTransactions;

    public function test_no_se_pueden_crear_listas_sin_nombre()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('POST', "/api/v1/lists", ['name' => '']);

        $response
            ->assertStatus(500)
            ->assertJsonFragment([
                'success' => false,
            ]);
    }


    public function test_no_se_pueden_crear_listas_con_nombres_largos()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('POST', "/api/v1/lists", ['name' => str_random(150)]);

        $response
            ->assertStatus(500)
            ->assertJsonFragment([
                'success' => false,
            ]);
    }

}
