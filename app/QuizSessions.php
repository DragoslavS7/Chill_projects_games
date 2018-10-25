<?php

namespace App;

use App\Traits\ModelValidation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class QuizSessions extends Authenticatable
{
    use ModelValidation, SoftDeletes;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_session_id', 'user_id','game_id','quiz_id','correct_answers',
        'incorrect_answers', 'total_questions',
        'quiz_start_time','quiz_end_time','status', 'is_saved_to_lrs'
    ];

    public $rules = [
        'user_id' => 'required|integer',
        'game_id' => 'required|integer',
        'quiz_id' => 'required|integer',
    ];

    function questionSession(){
        return $this->hasMany('App\QuestionSessions');
    }

    function quiz(){
        return $this->belongsTo('App\Quiz', 'quiz_id');
    }
}
