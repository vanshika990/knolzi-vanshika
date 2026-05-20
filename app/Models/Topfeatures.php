<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topfeatures extends Model
{
    use HasFactory;

    protected $table = 'tbl_features';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'title', 'sub_title', 'image', 'created_at', 'updated_at'
    ];
}
