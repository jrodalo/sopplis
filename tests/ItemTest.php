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

        $this->json('GET', "/api/v1/lists/$cart->slug/items")
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
             ->json('GET', "/api/v1/lists/$cart->slug/items")
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
             ->json('GET', "/api/v1/lists/$cart->slug/items")
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
             ->json('POST', "/api/v1/lists/$cart->slug/items", ['name' => 'test'])
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
             ->json('POST', "/api/v1/lists/$cart->slug/items", ['name' => 'test'])
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
             ->json('PUT', "/api/v1/lists/$cart->slug/items/$item->id", ['done' => true])
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
             ->json('PUT', "/api/v1/lists/$cart->slug/items/$item->id", ['done' => true])
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
             ->json('GET', "/api/v1/lists/$cart->slug/items")
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
            'visible' => true,
        ]);
        $item = factory(App\Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test fav',
            'count' => 3,
            'visible' => false,
        ]);

        $this->actingAs($user)
             ->json('GET', "/api/v1/lists/$cart->slug/favorite")
             ->assertResponseOk()
             ->seeJson([
                'name' => 'test fav'
            ])
             ->dontSeeJson([
                 'name' => 'test normal',
            ]);
    }

    public function test_al_insertar_un_item_existente_aumenta_su_contador()
    {
        $user = factory(App\User::class)->create();
        $cart = factory(App\Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(App\Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'count' => 1,
        ]);

        $this->actingAs($user)
             ->json('POST', "/api/v1/lists/$cart->slug/items", ['name' => 'TEST'])
             ->assertResponseOk()
             ->seeJson([
                 'success' => true,
                 'count' => 2,
               ]);
    }


    public function test_un_usuario_puede_eliminar_items_en_sus_listas()
    {
        $user = factory(App\User::class)->create();
        $cart = factory(App\Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(App\Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'done' => true,
            'visible' => true,
        ]);

        $this->actingAs($user)
             ->json('DELETE', "/api/v1/lists/$cart->slug/items?items=$item->id")
             ->assertResponseOk()
             ->seeJson([
                 'success' => true,
               ]);
    }


    public function test_un_usuario_no_puede_eliminar_items_en_otras_listas()
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
             ->json('DELETE', "/api/v1/lists/$cart->slug/items?items=$item->id")
             ->assertResponseStatus(403)
             ->seeJson([
                 'success' => false,
               ]);
    }


    public function test_al_eliminar_items_completados_dejan_de_estar_visibles()
    {
        $user = factory(App\User::class)->create();
        $cart = factory(App\Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(App\Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'done' => true,
            'visible' => true,
        ]);

        $this->actingAs($user)
             ->delete("/api/v1/lists/$cart->slug/items", ['items' => "$item->id"])
             ->assertResponseOk()
             ->seeJson([
                 'success' => true
               ]);

        $this->seeInDatabase('items', ['id' => $item->id, 'visible' => false, 'done' => false]);
    }
}
