<?php

namespace App;

use App\Traits\ModelValidation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Question extends Authenticatable
{
    use ModelValidation, SoftDeletes;

    public $timestamps = false;
    protected $hidden = ['pivot'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name', 'description', 'question_type',
       'order_type', 'is_feedback_display_available',
       'correct_feedback',
       'incorrect_feedback',
        'client_portal_id',
        'is_enabled','is_saved_to_lrs',
        'is_puzzle',
        'question_image','question_video','question_meta'
    ];

    public $rules = [
        'name' => 'sometimes|required|min:3|max:100',
        'description' => 'max:240',
        'question_type' => 'sometimes|required',
        'order_type' => 'sometimes|required',
        'correct_feedback' => 'max:80',
        'incorrect_feedback' => 'max:80',

        'correct_answer'=> 'required_if:question_type,multiple_choice|required_if:question_type,multi_answer',

        'min' => 'required_if:question_type,slider',
        'max' => 'required_if:question_type,slider',
        'start' => 'required_if:question_type,slider',
        'correct_value' => 'required_if:question_type,slider',
        'increment' => 'required_if:question_type,slider',


        'answer_1' => 'max:80|required_if:question_type,multiple_choice|required_if:question_type,multi_answer',
        'answer_2' => 'max:80|required_if:question_type,multiple_choice|required_if:question_type,multi_answer',
        'answer_3' => 'max:80|required_if:question_type,multi_answer',
        'answer_4' => 'max:80',
    ];

    public $validationMessages = [
        'correct_answer.required_if' => 'The correct answer should be selected.'
    ];

    public function getFormattedQuestionTypeAttribute()
    {
        $questionType = $this->question_type;
        switch ($questionType) {
            case 'multiple_choice':
                $questionType = 'Multiple choice';
                break;
            case 'multi_answer':
                $questionType = 'Multi-answer';
                break;
            case 'slider':
                $questionType = 'Slider';
                break;
            case 'boolean':
                $questionType = 'True/False';
                break;
        }

        return $questionType;
    }

    public function answers(){
        return $this->hasMany('App\Answer', 'question_id', 'id');
    }

    public function correctAnswers(){
        return $this->answers()->where('is_correct', true);
    }

    public function questionSessions(){
        return $this->hasMany('App\QuestionSessions','question_id','id');
    }

    public function gameSessions(){
        return $this->hasMany('App\GameSessions','question_id','id');
    }

    public function quizzes(){
        return $this->belongsToMany('App\Quiz', 'quizzes_questions');
    }

    public function isPhotobombed(){
        return $this->question_image != '' || $this->question_video != '';
    }
}