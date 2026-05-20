<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiResponseTimes extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_logs';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'api_name',
        'total_time',
        'method',
    ];

}
