<?php

namespace App;

use App\Item;
use App\User;
use App\Hotspot;
use App\Video;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

 

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function hotspots()
    {
        return $this->hasMany(Hotspot::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($product) {
            //remove related rows region and city
            $product->items->each(function($items) {
                $items->media_file()->delete();
                $items->hotspot_setting()->delete();
            });
            $product->items()->delete(); 
            $product->hotspots()->delete(); 
            $product->videos()->delete(); 
            return true;
        });
    }
}
