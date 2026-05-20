<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model {

    use HasFactory;

    /**
     * @var type 
     */
    protected $table = 'tbl_feedback';
    protected $fillable = [
        'id', 'user_email', 'feedback_type', 'feedback_message'
    ];

}
