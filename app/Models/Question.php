<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';

    protected $fillable = ['question', 'answer_1', 'answer_2', 'answer_3', 'correct_answer'];
}
