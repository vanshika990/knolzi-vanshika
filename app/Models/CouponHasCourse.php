<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Coupon;
use App\Models\Course;


class CouponHasCourse extends Model {

    use HasFactory;
        

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_coupon_has_course';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coupon_id', 'course_id','status', 'created_at', 'updated_at'
    ];

   
    
    public function coupon() {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'coupon_id');
    }

    public function course() {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

}
