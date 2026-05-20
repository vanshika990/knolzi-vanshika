<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\User;

class Usercourseattempt extends Model {

    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_user_course_attempt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id', 'user_id', 'is_mail_send', 'state', 'start_time', 'end_time'
    ];

    public function course() {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

}
