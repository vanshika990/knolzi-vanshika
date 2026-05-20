<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\QuestionHashelp;
use App\Models\Questionanswer;
use App\Models\QuestionHashint;
use App\Models\QuestionhasImagehelp;
use App\Models\QuestionhasImagehint;

class CourseQuestion extends Model {

    use HasFactory;

    protected $table = 'tbl_course_question';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id', 'user_id', 'question_name', 'question_desc', 'question_type', 'question_media_type', 'question_media', 'question_media_multi', 'que_level', 'que_toc_no', 'que_toc_text', 'correct_question_ans', 'question_intent_id', 'status', 'is_delete'
    ];

    /**
     * Get the question record associated with course question.
     * @return type
     */
    public function course() {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Get the question record associated with question help.
     * @return type
     */
    public function questionhashelp() {
        return $this->hasOne(QuestionHashelp::class, 'question_id', 'id');
    }

    /**
     * Get the question record associated with question answer.
     * @return type
     */
    public function questionanswer() {
        return $this->hasMany(Questionanswer::class, 'question_id', 'id');
    }

    /**
     * Get the question record associated with question help.
     * @return type
     */
    public function questionhashint() {
        return $this->hasOne(QuestionHashint::class, 'question_id', 'id');
    }

    /**
     * Get the question record associated with question help.
     * @return type
     */
    public function quehasimghelp() {
        return $this->hasMany(QuestionhasImagehelp::class, 'question_id', 'id');
    }

    /**
     * Get the question record associated with question help.
     * @return type
     */
    public function quehasimghint() {
        return $this->hasMany(QuestionhasImagehint::class, 'question_id', 'id');
    }

}
