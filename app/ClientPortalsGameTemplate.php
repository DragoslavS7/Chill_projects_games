<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class ClientPortalsGameTemplate extends Authenticatable
{

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_portal_id', 'game_template_id',
    ];

    static function isUnique($clientPortalId, $gameTemplateId){
        return ClientPortalsGameTemplate::where('client_portal_id', $clientPortalId)->where('game_template_id', $gameTemplateId)->count() == 0;
    }
}
