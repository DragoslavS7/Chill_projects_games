<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class QuizzesQuestion extends Authenticatable
{

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_id', 'quiz_id',
    ];

    static function isUnique($questionId, $quizId){
        return QuizzesQuestion::where('question_id', $questionId)->where('quiz_id', $quizId)->count() == 0;
    }
}
