<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseSubscription;
use App\Models\CourseQuestion;
use App\Models\Questionanswer;
use App\Models\CourseHasUser;
use Cviebrock\EloquentSluggable\Sluggable;

class Course extends Model
{

    use HasFactory, Sluggable;

    protected $table = 'tbl_course';
    protected $primaryKey = 'course_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_code',
        'course_name',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'slug',
        'course_sub_description',
        'course_description',
        'course_requirement',
        'course_image',
        'course_type',
        'course_applications',
        'course_price',
        'subscription_day',
        'status',
        'course_include',
        'course_featured',
        'course_tag'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'course_name' // Change 'title' to 'name' if you want to use the 'name' field for slug generation
            ]
        ];
    }


    public function coursesubscription()
    {
        return $this->belongsTo(CourseSubscription::class);
    }

    public function course_has_user()
    {
        return $this->hasMany(CourseHasUser::class, 'course_id');
    }

}
