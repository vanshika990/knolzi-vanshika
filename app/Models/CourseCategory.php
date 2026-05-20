<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Category;

class CourseCategory extends Model {

    use HasFactory;

    protected $table = 'tbl_has_course_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cat_id', 'course_id'
    ];

    /**
     * The course that belong to the CourseCategory.
     */
    public function course() {
        return $this->hasMany(Course::class,'course_id');
    }
    
    public function category(){
        return $this->belongsTo(Category::class,'cat_id','id');
    }
    
    /**
     * The roles that belong to the user.
     */
    public function CourseCategory() {
        return $this->hasMany(Course::class,'course_id');
    }

}
