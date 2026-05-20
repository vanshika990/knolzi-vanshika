<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class InstituteHasAuthor extends Model {

    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_institute_user_has_author';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'institute_id', 'author_id'
    ];

    // get institute author data
    public function user() {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

}
