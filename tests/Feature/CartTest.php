<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_se_pueden_crear_listas_sin_nombre()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('POST', '/api/v1/lists', ['name' => '']);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'success' => false,
            ]);
    }

    public function test_no_se_pueden_crear_listas_con_nombres_largos()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('POST', '/api/v1/lists', ['name' => Str::random(150)]);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'success' => false,
            ]);
    }
}
