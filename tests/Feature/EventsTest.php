<?php

namespace Tests\Feature;

use App\Cart;
use App\Events\NewItemCreated;
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

        $response = $this->actingAs($user)->json('POST', "/api/v1/lists/$cart->slug/items", $item);

        Event::assertDispatched(NewItemCreated::class, function ($e) use ($item) {
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

        $response = $this->actingAs($user)->json('POST', "/api/v1/lists/$cart->slug/items", $item);

        Event::assertNotDispatched(NewItemCreated::class);
    }

}
