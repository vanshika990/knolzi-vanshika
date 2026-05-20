<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEOmeta extends Model
{
    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_seo_meta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'title', 'description', 'keyword', 'slug','created_at', 'updated_at'
    ];
}
