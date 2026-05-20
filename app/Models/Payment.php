<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseSubscription;

class Payment extends Model {

    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_payment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'order_id', 'transaction_id', 'type', 'per_licence_amount', 'price', 'amount_to_be_paid', 'discount_code', 'discount', 'payment_status', 'payment_mode', 'remark', 'subscription_data', 'generated_response', 'status'
    ];

    public function subscription() {
        return $this->hasMany(CourseSubscription::class, 'payment_id');
    }

}
