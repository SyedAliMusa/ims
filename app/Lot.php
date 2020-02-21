<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    protected $fillable = ["*"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'lots_primary_key');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function network()
    {
        return $this->belongsTo(Network::class, 'network_id');
    }
    public function storage()
    {
        return $this->belongsTo(Storages::class, 'storage_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function check_lot_exist($lot_id){
       return self::where('lot_id','=',$lot_id)->first();
    }

}
