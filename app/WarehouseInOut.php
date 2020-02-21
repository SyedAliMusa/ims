<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseInOut extends Model
{

    protected $fillable = ["*"];
    protected $table = "warehouse_in_out";


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }



}
