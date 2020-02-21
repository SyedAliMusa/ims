<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Problems extends Model
{
    protected $fillable = ["*"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testing()
    {
        return $this->belongsTo(Testing::class, 'testing_id');
    }

}
