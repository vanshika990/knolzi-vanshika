<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;

class Wishlist extends Model {

    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_wishlists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'course_id'
    ];

    public function course() {
        return $this->belongsTo(Course::class, 'course_id');
    }

}
