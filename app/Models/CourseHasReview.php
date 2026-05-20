<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CourseHasReview extends Model {

    use HasFactory;

    protected $table = 'tbl_course_has_review_rate';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id', 'user_id', 'rate', 'review', 'status'
    ];

    /**
     * Get the user record associated with course.
     * @return type
     */
    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
