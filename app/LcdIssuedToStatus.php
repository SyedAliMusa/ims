<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LcdIssuedToStatus extends Model
{

    protected $table = "lcd_issued_to_status";
    protected $fillable = ["*"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function lcd_issued_to()
    {
        return $this->belongsTo(LcdIssuedTo::class, 'lcd_issued_to_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
public function statuses()
    {
        return $this->belongsTo(Status::class, 'status');
    }


}
