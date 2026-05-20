<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teaching extends Model {

    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_teaching';
    protected $fillable = [
        'contact_name', 'email', 'phone_number','online_teaching_experience','own_audience','hear_about_us','teaching_provide'
    ];

}
