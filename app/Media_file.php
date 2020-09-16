<?php

namespace App;

use App\Item;
use Illuminate\Database\Eloquent\Model;

class Media_file extends Model
{
    protected $guarded = [];

    // public function item()
    // {
    //     // return $this->belongsTo(Item::class);
    //     return $this->hasMany(Item::class);
    // }
}
