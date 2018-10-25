<?php

namespace App;

use App\Traits\ModelValidation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class GameSessions extends Authenticatable
{
    use ModelValidation, SoftDeletes;

    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'game_id','points',
        'game_start_time', 'game_end_time',
        'correct_answers','incorrect_answers','total_questions',
        'status', 'is_saved_to_lrs'
    ];

    public $rules = [
        'user_id'=> 'required|integer',
        'game_id' => 'required|integer',
        'points' => 'required|integer',
    ];

    function game(){
        return $this->belongsTo('App\Game', 'game_id');
    }

    function quizSession(){
        return $this->hasMany('App\QuizSessions');
    }

    function user(){
        return $this->hasOne('App\User' , 'id','user_id');
    }

}
