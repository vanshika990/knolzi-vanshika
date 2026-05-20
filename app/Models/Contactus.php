<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contactus extends Model {

    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_contact_us';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject', 'name', 'email', 'mobile_no', 'who_are_you', 'gender', 'hear_about_us', 'message'
    ];

}
