<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_usuario_no_validado_no_puede_leer_items()
    {
        $cart = factory(Cart::class)->create();
        $item = factory(Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'visible' => true,
        ]);

        $response = $this->json('GET', "/api/v1/lists/$cart->slug/items");

        $response
            ->assertStatus(401);
    }

    public function test_un_usuario_puede_ver_los_items_de_sus_listas()
    {
        $user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'visible' => true,
        ]);

        $response = $this->actingAs($user)->json('GET', "/api/v1/lists/$cart->slug/items");

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'test',
            ]);
    }

    public function test_un_usuario_no_puede_ver_los_items_de_otros_usuarios()
    {
        $user = factory(User::class)->create();
        $other_user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'visible' => true,
        ]);

        $response = $this->actingAs($other_user)->json('GET', "/api/v1/lists/$cart->slug/items");

        $response
            ->assertStatus(403)
            ->assertJsonMissing([
                'name' => 'test',
            ]);
    }

    public function test_un_usuario_puede_crear_items_en_sus_listas()
    {
        Event::fake();
        $user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);

        $response = $this->actingAs($user)->json('POST', "/api/v1/lists/$cart->slug/items", ['name' => 'test']);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_un_usuario_no_puede_crear_items_en_otras_listas()
    {
        $user = factory(User::class)->create();
        $other_user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);

        $response = $this->actingAs($other_user)->json('POST', "/api/v1/lists/$cart->slug/items", ['name' => 'test']);

        $response
            ->assertStatus(403);
    }

    public function test_un_usuario_puede_modificar_items_en_sus_listas()
    {
        $user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'done' => false,
            'visible' => true,
        ]);

        $response = $this->actingAs($user)->json('PUT', "/api/v1/lists/$cart->slug/items/$item->id", ['done' => true]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
                'done' => true,
            ]);
    }

    public function test_un_usuario_no_puede_modificar_items_en_otras_listas()
    {
        $user = factory(User::class)->create();
        $other_user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'done' => false,
            'visible' => true,
        ]);

        $response = $this->actingAs($other_user)->json('PUT', "/api/v1/lists/$cart->slug/items/$item->id", ['done' => true]);

        $response
            ->assertStatus(403)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_un_usuario_solo_puede_ver_los_items_visibles()
    {
        $user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'visible' => false,
        ]);

        $response = $this->actingAs($user)->json('GET', "/api/v1/lists/$cart->slug/items");

        $response
            ->assertStatus(200)
            ->assertJsonMissing([
                'name' => 'test',
            ]);
    }

    public function test_un_producto_es_favorito_cuando_se_ha_comprado_mas_de_dos_veces()
    {
        $user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test normal',
            'count' => 2,
            'visible' => true,
        ]);
        $item = factory(Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test fav',
            'count' => 3,
            'visible' => false,
        ]);

        $response = $this->actingAs($user)->json('GET', "/api/v1/lists/$cart->slug/favorites");

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
               'name' => 'test fav',
            ])
            ->assertJsonMissing([
                 'name' => 'test normal',
            ]);
    }

    public function test_al_insertar_un_item_existente_aumenta_su_contador()
    {
        Event::fake();
        $user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'count' => 1,
        ]);

        $response = $this->actingAs($user)->json('POST', "/api/v1/lists/$cart->slug/items", ['name' => 'TEST']);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
                'count' => 2,
            ]);
    }

    public function test_un_usuario_puede_eliminar_items_en_sus_listas()
    {
        $user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'done' => true,
            'visible' => true,
        ]);

        $response = $this->actingAs($user)->json('DELETE', "/api/v1/lists/$cart->slug/items?items=$item->id");

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_un_usuario_no_puede_eliminar_items_en_otras_listas()
    {
        $user = factory(User::class)->create();
        $other_user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'done' => false,
            'visible' => true,
        ]);

        $response = $this->actingAs($other_user)->json('DELETE', "/api/v1/lists/$cart->slug/items?items=$item->id");

        $response
            ->assertStatus(403)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_al_eliminar_items_completados_dejan_de_estar_visibles()
    {
        $user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'done' => true,
            'visible' => true,
        ]);

        $response = $this->actingAs($user)->delete("/api/v1/lists/$cart->slug/items", ['items' => "$item->id"]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('items', ['id' => $item->id, 'visible' => false, 'done' => false]);
    }

    public function test_no_se_pueden_crear_items_sin_nombre()
    {
        $user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);

        $response = $this->actingAs($user)->json('POST', "/api/v1/lists/$cart->slug/items", ['name' => '']);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'success' => false,
            ]);
    }

    public function test_no_se_pueden_crear_items_con_nombres_largos()
    {
        $user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);

        $response = $this->actingAs($user)->json('POST', "/api/v1/lists/$cart->slug/items", ['name' => Str::random(150)]);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'success' => false,
            ]);
    }
}
