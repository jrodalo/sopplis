<?php

namespace Tests\Feature;

use App\Events\ItemCreated;
use App\Events\ItemUpdated;
use App\Http\Requests\WebhookRequest;
use App\Models\Cart;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use RefreshDatabase;

    public function test_se_genera_evento_al_crear_items_en_lista_compartida()
    {
        Event::fake();

        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $cart = Cart::factory()->create();
        $cart->users()->attach($user);
        $cart->users()->attach($other_user);
        $item = ['name' => 'test'];

        $this->actingAs($user)->json('POST', "/api/v1/lists/$cart->slug/items", $item);

        Event::assertDispatched(ItemCreated::class, function ($e) use ($item) {
            return $e->items[0]->name === $item['name'];
        });
    }

    public function test_se_genera_evento_al_crear_items_en_lista_compartida_desde_webhook()
    {
        Event::fake();

        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $cart = Cart::factory()->create();
        $cart->users()->attach($user);
        $cart->users()->attach($other_user);

        $response = $this->post('/webhooks/lists', [
            WebhookRequest::RECIPIENT_FIELD => "list_$cart->slug@sopplis.com",
            WebhookRequest::SENDER_FIELD => $other_user->email,
            WebhookRequest::BODY_FIELD => 'test',
        ]);

        Event::assertDispatched(ItemCreated::class, function ($e) {
            return $e->items[0]->name === 'test';
        });
    }

    public function test_no_se_genera_evento_al_crear_items_en_lista_privada()
    {
        Event::fake();

        $user = User::factory()->create();
        $cart = Cart::factory()->create();
        $cart->users()->attach($user);
        $item = ['name' => 'test'];

        $this->actingAs($user)->json('POST', "/api/v1/lists/$cart->slug/items", $item);

        Event::assertNotDispatched(ItemCreated::class);
    }

    public function test_se_genera_evento_al_modificar_items_en_lista_compartida()
    {
        Event::fake();

        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $cart = Cart::factory()->create();
        $cart->users()->attach($user);
        $cart->users()->attach($other_user);
        $item = Item::factory()->create([
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

        $user = User::factory()->create();
        $cart = Cart::factory()->create();
        $cart->users()->attach($user);
        $item = Item::factory()->create([
            'cart_id' => $cart->id,
            'name' => 'test',
            'done' => false,
            'visible' => true,
        ]);

        $this->actingAs($user)->json('PUT', "/api/v1/lists/$cart->slug/items/$item->id", ['done' => true]);

        Event::assertNotDispatched(ItemUpdated::class);
    }
}
