<?php

namespace App;

use App\Company;
use Illuminate\Database\Eloquent\Model;

class Watermark extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Company::class);
    }

}
