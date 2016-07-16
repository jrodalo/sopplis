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
        'cart_id', 'count', 'visible', 'created_at', 'updated_at',
    ];


    /**
     * Get the cart that owns the item.
     */
    public function cart()
    {
        return $this->belongsTo('App\Cart');
    }

}
