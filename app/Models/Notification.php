<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

    use HasFactory;

    /**
     * @var type 
     */
    protected $table = 'tbl_notification';
    protected $fillable = [
        'id', 'title', 'body', 'status','created_by'
    ];

}
