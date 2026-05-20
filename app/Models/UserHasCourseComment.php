<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasCourseComment extends Model {

    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_user_has_course_comment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'course_id', 'comment'
    ];

}
