<?php

namespace App;

//use App\Hotspot;
use App\Hotspot_setting;
use App\Product;
use App\User_file;
use App\Media_file;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    // public function hotspots()
    // {
    //     return $this->belongsToMany(Hotspot::class);
    // }
    public function media_file()
    {
        return $this->belongsTo(Media_file::class);
        // return $this->hasMany(Media_file::class);
    }
    public function hotspot_setting()
    {
        return $this->hasMany(Hotspot_setting::class);
    }
}
