<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class PasswordReset extends Authenticatable
{

    public $timestamps = false;
    protected $primaryKey = 'email';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'token',
    ];
}
