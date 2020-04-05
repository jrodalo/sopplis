<?php

namespace App\Http\Requests;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Validator;

class WebhookRequest extends FormRequest
{
    const RECIPIENT_FIELD = 'recipient';
    const SENDER_FIELD = 'sender';
    const BODY_FIELD = 'body-plain';

    public function notValid()
    {
        $validator = Validator::make($this->all(), [
            self::RECIPIENT_FIELD => 'required|email',
            self::SENDER_FIELD => 'required|email',
        ]);

        return $validator->fails();
    }

    public function getCartSlug()
    {
        $recipient = $this->input(self::RECIPIENT_FIELD);
        $cart_slug = Str::before($recipient, '@');
        $cart_slug = Str::after($cart_slug, 'list_');

        return $cart_slug;
    }

    public function getUserEmail()
    {
        return $this->input(self::SENDER_FIELD);
    }

    public function getItems()
    {
        $items = preg_split('/\r\n|\r|\n/', $this->input(self::BODY_FIELD));

        return collect($items)->unique();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }
}
