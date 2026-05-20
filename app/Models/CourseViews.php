<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseViews extends Model {

    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_course_views';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id', 'ip'
    ];

}
