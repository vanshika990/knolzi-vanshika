<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewerCourse extends Model {

    protected $table = 'tbl_reviewer_course';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'course_id'
    ];

    /**
     * Get the course record associated with reviewer user.
     * @return type
     */
    public function course() {
        return $this->belongsTo('App\Models\Course', 'course_id')->select(array('course_id', 'course_name', 'course_description', 'course_image', 'course_price'));
    }

}
