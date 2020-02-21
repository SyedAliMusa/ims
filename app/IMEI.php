<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IMEI extends Model
{

    protected $table = ["imei"];
 protected $fillable = [
        'imei', 'code'
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    



}
