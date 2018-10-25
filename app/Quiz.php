<?php

namespace App;

use App\Traits\ModelValidation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Quiz extends Authenticatable
{
    use ModelValidation, SoftDeletes;

    public $timestamps = false;

    protected $table = 'quizzes';

    protected $hidden = ['pivot'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'is_enabled',
        'are_questions_randomized', 'client_portal_id',
        'author_id','updated_by_id'
    ];

    public $rules = [
        'name'=>'sometimes|required|min:3|max:100'
    ];

    public function questions(){
        return $this->belongsToMany('App\Question', 'quizzes_questions');
    }

    public function games(){
        return $this->belongsToMany('App\Game', 'games_quizzes');
    }

    public function tags(){
        return $this->hasMany('App\QuizzesTags', 'quiz_id','id');
    }

    public function quizSessions(){
        return $this->hasMany('App\QuizSessions','quiz_id','id');
    }

    public function getQuizzes($clientPortalId){
        $clientPortal = ClientPortal::find($clientPortalId);

        $quiz = [];

        $quiz['client_portal_id'] =  $clientPortalId;

        $quiz['quizzes_deployed'] = $clientPortal->quizzes->where('is_enabled',1)->count();

        $quiz['quizzes_started'] = $this->getTotal($clientPortalId, 'started');

        $quiz['quizzes_finished'] =  $this->getTotal($clientPortalId, 'finished');

        return $quiz;
    }

    public function getTotal($clientPortalId, $status)    {
        $total = 0;
        $quiz = Quiz::join('quiz_sessions', function ($join) {
            $join->on('quizzes.id', '=', 'quiz_sessions.quiz_id');
        })
            ->where('client_portal_id', $clientPortalId)
            ->where('quiz_sessions.status', $status)
            ->groupBy('quizzes.client_portal_id')
            ->selectRaw('count(*) as total')
            ->first();

        if($quiz){
            $total = $quiz->total;
        }

        return $total;
    }
}
