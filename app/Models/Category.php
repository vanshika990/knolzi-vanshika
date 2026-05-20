<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class Category extends Model
{

    use HasFactory, Sluggable;

    protected $table = 'tbl_course_category';
    protected $fillable = [
        'name',
        'category_order',
        'slug',
        'parent_id',
        'status',
        'category_sub_description',
        'category_description',
        'meta_title',
        'meta_keyword',
        'meta_description'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name' // Change 'title' to 'name' if you want to use the 'name' field for slug generation
            ]
        ];
    }

    public function parents()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subcategory()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

}
