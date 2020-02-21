<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repairing extends Model
{
    protected $fillable = ["*"];

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
    public function problems(){
        return $this->hasMany(RepairingProblem::class, 'repairing_id');
    }

}
