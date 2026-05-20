<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionHashint extends Model {

    use HasFactory;

    protected $table = 'tbl_question_has_hint';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_id', 'video', 'audio', 'pdf', 'image', 'link', 'video_type'
    ];

}
