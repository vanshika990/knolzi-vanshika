<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model {

    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_subscriber';
    protected $fillable = [
        'email', 'created_at','updated_at'
    ];

}
