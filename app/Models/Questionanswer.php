<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionanswer extends Model {

    use HasFactory;

    protected $table = 'tbl_question_answer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_id', 'answer_name', 'answer_order', 'choice_type'
    ];

}
