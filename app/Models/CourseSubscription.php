<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseSubscriptionLicence;
use App\Models\Payment;

class CourseSubscription extends Model {

    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_course_subscription';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id', 'user_id', 'payment_id', 'sub_plan_id', 'no_of_licence', 'sub_expire_date','discount_code','amount_to_be_paid', 'status'
//        'course_id', 'user_id', 'payment_id', 'sub_plan_id', 'no_of_licence', 'per_licence_amount', 'actual_price', 'amount_to_be_paid', 'sub_expire_date', 'remark', 'status', 'payment_status', 'payment_mode', 'payment_type', 'generated_response'
    ];

    /**
     * 
     * @return type
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    // get course data
    public function course() {
        return $this->hasOne(Course::class, 'course_id', 'course_id');
    }

    // Get course subscription licence data
    public function coursesublicence() {
        return $this->hasMany(CourseSubscriptionLicence::class, 'course_subscription_id', 'id')->where('status', '1');
    }

    public function licence() {
        return $this->hasMany('App\Models\CourseSubscriptionLicence');
    }

    public function payment(){
        return $this->belongsTo(Payment::class, 'payment_id');
    }

}
