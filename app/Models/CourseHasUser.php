<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Course;

class CourseHasUser extends Model {

    use HasFactory;

    protected $table = 'tbl_course_has_user';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id', 'user_id'
    ];

    /**
     * Get the user record associated with course.
     * @return type
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    /**
     * Get the user record associated with course.
     * @return type
     */
    public function course() {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

}
