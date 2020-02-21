<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DumyBarcode extends Model
{

    protected $table = 'dumy_barcode';
    protected $fillable = ["*"];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}
