<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCompleteHasUserRate extends Model {

    use HasFactory;

    /**
     * @var type 
     */
    protected $table = 'tbl_course_complete_has_user_rate';
    protected $fillable = [
        'id', 'attempt_id', 'course_id', 'user_id', 'course_rate', 'author_rate', 'new_skill_rate', 'overall_rate', 'accessing_rate', 'recommend_rate'
    ];

}
