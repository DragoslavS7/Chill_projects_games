<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserDepartmentTags extends Authenticatable
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','client_portal_department_tag_id'
    ];

    static function isUnique($userId, $clientPortalDepartmentTagId){
        return UserDepartmentTags::where('user_id', $userId)->where('client_portal_department_tag_id', $clientPortalDepartmentTagId)->count() == 0;
    }
}