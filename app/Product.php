<?php

namespace App;

use App\Item;
use App\User;
use App\Scene;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function scenes()
    {
        return $this->hasMany(Scene::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
