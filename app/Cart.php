<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];


    /**
     * Checks if given user can access this cart
     */
    public function isVisibleBy(User $user)
    {
        return $this->users()->where('id', $user->id)->count() > 0;
    }

    /**
     * Get just the visible items
     */
    public function scopeVisibleItems()
    {
        return $this->items()->where('visible', true);
    }

    /**
     * Get just the favorite items
     */
    public function scopeFavoriteItems()
    {
        return $this->items()->where('count', '>', 2);
    }

    /**
     * Get the items for the cart.
     */
    public function items()
    {
        return $this->hasMany('App\Item');
    }

    /**
     * The users that belong to the cart.
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

}
