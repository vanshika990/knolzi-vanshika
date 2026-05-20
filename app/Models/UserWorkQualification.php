<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWorkQualification extends Model {

    protected $table = 'tbl_pro_qualification';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'company_name', 'domain', 'role', 'designation', 'year', 'experience', 'created_at', 'updated_at'
    ];

}
