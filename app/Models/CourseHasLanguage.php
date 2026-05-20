<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Language;

class CourseHasLanguage extends Model {

    use HasFactory;

    protected $table = 'tbl_course_has_language';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id', 'language_id'
    ];

    public function course_language() {
        return $this->hasOne(Language::class, 'id', 'language_id');
    }

}
