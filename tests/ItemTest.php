<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ItemTest extends TestCase
{
    use DatabaseTransactions;

    public function test_un_usuario_no_validado_no_puede_leer_items()
    {
        $cart = factory(App\Cart::class)->create();
        $item = factory(App\Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'visible' => true,
        ]);

        $this->json('GET', "/api/v1/lists/$cart->id/items")
             ->assertResponseStatus(401);
    }

    public function test_un_usuario_puede_ver_los_items_de_sus_listas()
    {
        $user = factory(App\User::class)->create();
        $cart = factory(App\Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(App\Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'visible' => true,
        ]);

        $this->actingAs($user)
             ->json('GET', "/api/v1/lists/$cart->id/items")
             ->assertResponseOk()
             ->seeJson([
                 'name' => 'test',
                ]);
    }

    public function test_un_usuario_no_puede_ver_los_items_de_otros_usuarios()
    {
        $user = factory(App\User::class)->create();
        $other_user = factory(App\User::class)->create();
        $cart = factory(App\Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(App\Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'visible' => true,
        ]);

        $this->actingAs($other_user)
             ->json('GET', "/api/v1/lists/$cart->id/items")
             ->assertResponseStatus(403)
             ->dontSeeJson([
                 'name' => 'test',
                ]);
    }

    public function test_un_usuario_puede_crear_items_en_sus_listas()
    {
        $user = factory(App\User::class)->create();
        $cart = factory(App\Cart::class)->create();
        $cart->users()->attach($user);

    	$this->actingAs($user)
             ->post("/api/v1/lists/$cart->id/items", ['name' => 'test'])
             ->assertResponseOk()
             ->seeJson([
                 'success' => true,
        	   ]);
    }

    public function test_un_usuario_no_puede_crear_items_en_otras_listas()
    {
        $user = factory(App\User::class)->create();
        $other_user = factory(App\User::class)->create();
        $cart = factory(App\Cart::class)->create();
        $cart->users()->attach($user);

        $this->actingAs($other_user)
             ->post("/api/v1/lists/$cart->id/items", ['name' => 'test'])
             ->assertResponseStatus(403);
    }

    public function test_un_usuario_puede_modificar_items_en_sus_listas()
    {
        $user = factory(App\User::class)->create();
        $cart = factory(App\Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(App\Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'done' => false,
            'visible' => true,
        ]);

        $this->actingAs($user)
             ->json('PUT', "/api/v1/lists/$cart->id/items/$item->id", ['done' => true])
             ->assertResponseOk()
             ->seeJson([
                 'success' => true,
                 'done' => true,
               ]);
    }

    public function test_un_usuario_no_puede_modificar_items_en_otras_listas()
    {
        $user = factory(App\User::class)->create();
        $other_user = factory(App\User::class)->create();
        $cart = factory(App\Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(App\Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'done' => false,
            'visible' => true,
        ]);

        $this->actingAs($other_user)
             ->json('PUT', "/api/v1/lists/$cart->id/items/$item->id", ['done' => true])
             ->assertResponseStatus(403)
             ->seeJson([
                 'success' => false,
               ]);
    }

    public function test_un_usuario_solo_puede_ver_los_items_visibles()
    {
        $user = factory(App\User::class)->create();
        $cart = factory(App\Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(App\Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'visible' => false,
        ]);

        $this->actingAs($user)
             ->json('GET', "/api/v1/lists/$cart->id/items")
             ->assertResponseOk()
             ->dontSeeJson([
                 'name' => 'test',
            ]);
    }

    public function test_un_producto_es_favorito_cuando_se_ha_comprado_mas_de_dos_veces()
    {
        $user = factory(App\User::class)->create();
        $cart = factory(App\Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(App\Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test normal',
            'count' => 2,
        ]);
        $item = factory(App\Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test fav',
            'count' => 3,
        ]);

        $this->actingAs($user)
             ->json('GET', "/api/v1/lists/$cart->id/items", ['favorite' => true])
             ->assertResponseOk()
             ->dontSeeJson([
                 'name' => 'test normal',
            ]);
    }
}
