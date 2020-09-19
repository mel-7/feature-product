<?php

namespace App;

use App\Company;
use App\Media_file;
use Illuminate\Database\Eloquent\Model;

class Watermark extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Company::class);
    }
    public function media_file()
    {
        return $this->belongsTo(Media_file::class);
    }
}
