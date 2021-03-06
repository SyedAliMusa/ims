<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Status extends Authenticatable
{
    use Notifiable;

    protected $table = 'status';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
}
