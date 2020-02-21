<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepairingProblem extends Model
{
    protected $fillable = ["repairing_id", "problem", "problem_name", "status"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function repairing()
    {
        return $this->hasOne(Repairing::class, 'repairing_id');
    }

}
