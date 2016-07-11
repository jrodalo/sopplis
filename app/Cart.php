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
