<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cart_id', 'visible', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'done' => 'boolean',
    ];

    /**
     * Appends the selected attribute used by favorite page.
     */
    protected $appends = ['selected'];

    public function getSelectedAttribute()
    {
        return false;
    }

    /**
     * Get the cart that owns the item.
     */
    public function cart()
    {
        return $this->belongsTo(\App\Cart::class);
    }
}
