<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class QuizzesTags extends Authenticatable
{

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quiz_id', 'tag',
    ];

    static function isUnique($quizId, $tag){
        return QuizzesTags::where('tag', $tag)->where('quiz_id', $quizId)->count() == 0;
    }
}
