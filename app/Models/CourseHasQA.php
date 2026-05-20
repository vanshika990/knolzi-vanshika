<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CourseHasQA extends Model {

    use HasFactory;

    protected $table = 'tbl_course_has_user_qa';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'course_id', 'author_id', 'review', 'question_name', 'answer', 'status'
    ];

    /**
     * Get the user record associated with course.
     * @return type
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
