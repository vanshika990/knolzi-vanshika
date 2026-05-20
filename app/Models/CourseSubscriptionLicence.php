<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CourseSubscriptionLicence extends Model {

    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_course_subscription_licence';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_subscription_id', 'user_id', 'course_id', 'status'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

}
