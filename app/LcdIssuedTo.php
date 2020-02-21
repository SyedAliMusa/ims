<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LcdIssuedTo extends Model
{

    protected $table = "lcd_issued_to";
    protected $fillable = ["*"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function lcd_inventory()
    {
        return $this->belongsTo(LcdInventory::class, 'lcd_inventory_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to_account');
    }
    public function statuses()
    {
        return $this->belongsTo(Status::class, 'status');
    }


}
