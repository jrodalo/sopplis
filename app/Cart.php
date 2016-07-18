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
        'id', 'created_at', 'updated_at', 'pivot',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

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
        return $this->items()->where('visible', true)->orderBy('done', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Get just the favorite items
     */
    public function scopeFavoriteItems()
    {
        return $this->items()->where('count', '>', 2)->orderBy('name', 'asc');
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
