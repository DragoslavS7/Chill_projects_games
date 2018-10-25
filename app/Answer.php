<?php

namespace App;

use App\Traits\ModelValidation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Answer extends Authenticatable
{
    use ModelValidation, SoftDeletes;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'type', 'answer', 'question_type',
       'question_id', 'is_correct',
       'min',
       'max',
       'start',
       'correct_value',
       'increment',
       'is_saved_to_lrs'
    ];
}
