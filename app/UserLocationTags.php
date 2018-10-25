<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserLocationTags extends Authenticatable
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','client_portal_location_tag_id'
    ];

    static function isUnique($userId, $clientPortalLocationTagId){
        return UserLocationTags::where('user_id', $userId)->where('client_portal_location_tag_id', $clientPortalLocationTagId)->count() == 0;
    }
}