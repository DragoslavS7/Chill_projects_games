<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class GamesQuizzes extends Authenticatable
{

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_id', 'quiz_id',
    ];

    static function isUnique($gameId, $quizId){
        return GamesQuizzes::where('game_id', $gameId)->where('quiz_id', $quizId)->count() == 0;
    }
}
