<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CouponHasCourse;


class Coupon extends Model {

    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_coupon';
    protected $primaryKey = 'coupon_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coupon_title', 'coupon_code', 'coupon_type', 'coupon_duration', 'coupon_percentage', 'coupon_start_date', 'coupon_end_date', 'coupon_used', 'status', 'created_at', 'updated_at'
    ];

   
    public function coupon_has_course() {
        return $this->hasMany(CouponHasCourse::class, 'course_id');
    }

}
