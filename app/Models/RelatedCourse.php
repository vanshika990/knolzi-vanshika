<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Category;

class RelatedCourse extends Model {

    use HasFactory;

    protected $table = 'tbl_has_related_course';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id', 'related_course_id','created_at','updated_at'
    ];

    /**
     * The course that belong to the CourseCategory.
     */
    public function course() {
        return $this->belongsTo(Course::class,'related_course_id','course_id');
    }
    
    
    
   

}
