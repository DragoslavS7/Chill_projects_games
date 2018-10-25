<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class InviteGameTokens extends Authenticatable
{

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_id', 'user_id', 'token'
    ];

    static function isUnique($gameId, $tokenId){
        return ClientPortalsGameTemplate::where('game_id', $gameId)->where('token_id', $tokenId)->count() == 0;
    }

    public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
