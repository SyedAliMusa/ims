<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttachIMEIToLCD extends Model
{

    protected $table = 'attach_imei_to_lcds';
    protected $fillable = ["*"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lcdInventory()
    {
        return $this->belongsTo(LcdInventory::class, 'lcd_inventory_id');
    }
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


}
