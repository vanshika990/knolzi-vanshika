<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model {

    protected $table = 'tbl_edu_qualification';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'degree', 'university', 'institute', 'stream', 'year', 'grade', 'created_at', 'updated_at'
    ];

}
