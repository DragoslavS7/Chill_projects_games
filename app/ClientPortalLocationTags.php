<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class ClientPortalLocationTags extends Authenticatable
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_portal_id', 'tag',
    ];

    static function isUnique($clientPortalId, $tag){
        return ClientPortalLocationTags::where('tag', $tag)->where('client_portal_id', $clientPortalId)->count() == 0;
    }
}