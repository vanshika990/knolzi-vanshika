<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDemo extends Model {

    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_request_demo';
    protected $fillable = [
        'contact_name', 'email', 'phone_number', 'institute_name', 'no_of_students', 'hear_about_us', 'state', 'message'
    ];

}
