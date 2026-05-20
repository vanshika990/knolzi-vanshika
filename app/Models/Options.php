<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Options extends Model {

    use HasFactory;

    protected $table = 'tbl_options';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'option_name', 'option_value', 'created_at' ,'updated_at'
    ];

}
