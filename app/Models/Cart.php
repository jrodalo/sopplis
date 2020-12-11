<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'created_at', 'updated_at', 'pivot',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['shared'];

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
     * Checks if given user can access this cart.
     */
    public function isVisibleBy(User $user)
    {
        return $this->users()->where('id', $user->id)->exists();
    }

    /**
     * Get just the visible items.
     */
    public function scopeVisibleItems()
    {
        return $this->items()->where('visible', true)->orderBy('done', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Get just the favorite items.
     */
    public function scopeFavoriteItems()
    {
        return $this->items()->where('count', '>', 2)->orderBy('name', 'asc');
    }

    /**
     * Get the shared flag for the cart.
     *
     * @return bool
     */
    public function getSharedAttribute()
    {
        return $this->users()->count() > 1;
    }

    /**
     * Get the items for the cart.
     */
    public function items()
    {
        return $this->hasMany(\App\Models\Item::class);
    }

    /**
     * The users that belong to the cart.
     */
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class);
    }

    public function findOrNew($name)
    {
        $item = $this->items()->where('name', 'LIKE', trim($name))->first();

        if (is_null($item)) {
            $item = new Item;
            $item->cart_id = $this->id;
            $item->count = 0;
        }

        return $item;
    }
}
