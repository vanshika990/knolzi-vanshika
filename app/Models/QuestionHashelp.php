<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionHashelp extends Model {

    use HasFactory;

    protected $table = 'tbl_question_has_help';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_id', 'video', 'audio', 'pdf', 'image', 'link', 'video_type'
    ];

}
