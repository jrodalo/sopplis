<?php

namespace App\Http\Requests;

use Validator;
use App\Cart;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

class WebhookRequest extends FormRequest
{
    const RECIPIENT_FIELD = 'recipient';
    const SENDER_FIELD = 'sender';
    const BODY_FIELD = 'body-plain';

    public function notValid()
    {
        $validator = Validator::make($this->all(), [
            WebhookRequest::RECIPIENT_FIELD => 'required|email',
            WebhookRequest::SENDER_FIELD => 'required|email',
        ]);

        return $validator->fails();
    }

    public function getCartSlug()
    {
        $recipient = $this->input(self::RECIPIENT_FIELD);
        $cart_slug = str_before($recipient, '@');
        $cart_slug = str_after($cart_slug, 'list_');

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
