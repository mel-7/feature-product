<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    // protected $dates = [
    //     'converted_for_downloading_at',
    //     'converted_for_streaming_at',
    // ];

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
        // return $this->hasMany(Media_file::class);
    }
}
