<?php

namespace Tests\Feature;

use App\Cart;
use App\Events\ItemCreated;
use App\Events\ItemUpdated;
use App\Item;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_se_genera_evento_al_crear_items_en_lista_compartida()
    {
        Event::fake();

        $user = factory(User::class)->create();
        $other_user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);
        $cart->users()->attach($other_user);
        $item = ['name' => 'test'];

        $this->actingAs($user)->json('POST', "/api/v1/lists/$cart->slug/items", $item);

        Event::assertDispatched(ItemCreated::class, function ($e) use ($item) {
            return $e->item->name === $item['name'];
        });
    }

    public function test_no_se_genera_evento_al_crear_items_en_lista_privada()
    {
        Event::fake();

        $user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);
        $item = ['name' => 'test'];

        $this->actingAs($user)->json('POST', "/api/v1/lists/$cart->slug/items", $item);

        Event::assertNotDispatched(ItemCreated::class);
    }


    public function test_se_genera_evento_al_modificar_items_en_lista_compartida()
    {
        Event::fake();

        $user = factory(User::class)->create();
        $other_user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);
        $cart->users()->attach($other_user);
        $item = factory(Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'done' => false,
            'visible' => true,
        ]);

        $this->actingAs($user)->json('PUT', "/api/v1/lists/$cart->slug/items/$item->id", ['done' => true]);

        Event::assertDispatched(ItemUpdated::class, function ($e) use ($item) {
            return $e->item->done === true;
        });
    }


    public function test_no_se_genera_evento_al_modificar_items_en_lista_privada()
    {
        Event::fake();

        $user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);
        $item = factory(Item::class)->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'done' => false,
            'visible' => true,
        ]);

        $this->actingAs($user)->json('PUT', "/api/v1/lists/$cart->slug/items/$item->id", ['done' => true]);

        Event::assertNotDispatched(ItemUpdated::class);
    }

}
