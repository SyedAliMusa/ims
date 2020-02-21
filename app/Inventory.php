<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{

    protected $fillable = ["*"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function lot()
    {
        return $this->belongsTo(Lot::class, 'lots_primary_key');
    }
    public function dispatch()
    {
        return $this->belongsTo(Dispatch::class, 'id');
    }
    public function testing()
    {
        return $this->belongsTo(Testing::class, 'id');
    }

    public function getInventoryByIMEI($imei)
    {
        return Inventory::where('imei','=', $imei)->first();
    }



}
