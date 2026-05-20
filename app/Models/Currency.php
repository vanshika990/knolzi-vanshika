<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_currency';
    protected $fillable = [
        'name', 'inr_value', 'rate', 'symbol', 'short_name'
    ];
}
