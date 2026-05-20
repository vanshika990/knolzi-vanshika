<?php

namespace App\Models;

use Spatie\Permission\Models\Permission;

class Permissions extends Permission {

    /**
     * @var type 
     */
    protected $fillable = [
        'name', 'guard_name', 'display_name', 'module'
    ];

}
