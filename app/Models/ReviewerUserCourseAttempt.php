<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\User;

class ReviewerUserCourseAttempt extends Model {

    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_reviewer_user_course_attempt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id', 'user_id', 'state', 'start_time', 'end_time'
    ];

}
