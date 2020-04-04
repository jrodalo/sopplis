<?php

namespace Tests\Unit;

use App\Http\Requests\WebhookRequest;
use App\Models\Cart;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class WebhookTest extends TestCase
{
    use DatabaseTransactions;

    public function test_un_usuario_puede_enviar_items_en_sus_listas()
    {
        $user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);

        $response = $this->post('/webhooks/lists', [
            WebhookRequest::RECIPIENT_FIELD => "list_$cart->slug@sopplis.com",
            WebhookRequest::SENDER_FIELD => $user->email,
            WebhookRequest::BODY_FIELD => 'test',
        ]);

        $response
                ->assertStatus(200)
                ->assertJson(['success' => true]);
    }

    public function test_un_usuario_no_puede_enviar_items_a_las_listas_de_otros_usuarios()
    {
        $user = factory(User::class)->create();
        $other_user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();
        $cart->users()->attach($user);

        $response = $this->post('/webhooks/lists', [
            WebhookRequest::RECIPIENT_FIELD => "list_$cart->slug@sopplis.com",
            WebhookRequest::SENDER_FIELD => $other_user->email,
            WebhookRequest::BODY_FIELD => 'test',
        ]);

        $response->assertStatus(406);
    }

    public function test_debe_validar_los_datos_de_entrada()
    {
        $response = $this->post('/webhooks/lists', [
            WebhookRequest::RECIPIENT_FIELD => 'notvalidemail',
            WebhookRequest::SENDER_FIELD => 'notvalidemail',
        ]);

        $response->assertStatus(406);
    }
}
