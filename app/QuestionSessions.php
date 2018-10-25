<?php

namespace App;

use App\Traits\ModelValidation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class QuestionSessions extends Authenticatable
{
    use ModelValidation, SoftDeletes;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_session_id', 'quiz_session_id','user_id','game_id','quiz_id','question_id','answer',
        'question_start_time','question_end_time','is_correct', 'is_saved_to_lrs'
    ];

    public $rules = [
        'user_id' => 'required|integer',
        'game_id' => 'required|integer',
        'quiz_id' => 'required|integer',
        'question_id' => 'required|integer'
    ];

    public function gameSessions($game_id){
        return $this->hasMany('App\GameSessions')->where('game_id','=',$game_id);
    }

    public function questions(){
        return $this->belongsTo('App\Question', 'question_id');
    }

    public function answers(){
        return $this->belongsTo('App\Answer', 'answer_id');
    }
}
