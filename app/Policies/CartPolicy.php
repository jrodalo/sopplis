<?php

namespace App\Policies;

use App\Cart;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the given user can read the given cart.
     *
     * @param  User  $user
     * @param  Cart  $cart
     * @return bool
     */
    public function read(User $user, Cart $cart)
    {
        return $cart->isVisibleBy($user);
    }

    /**
     * Determine if the given user can write the given cart.
     *
     * @param  User  $user
     * @param  Cart  $cart
     * @return bool
     */
    public function write(User $user, Cart $cart)
    {
        return $cart->isVisibleBy($user);
    }
}
