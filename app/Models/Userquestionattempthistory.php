<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userquestionattempthistory extends Model {

    use HasFactory;

    protected $table = 'tbl_user_question_attempt_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id', 'question_id', 'user_id', 'course_attempt_id', 'time_taken', 'rightanswer', 'answer', 'sentiment', 'usable_help'
    ];

}
