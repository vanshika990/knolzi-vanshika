<?php

namespace App\Models;

use Spatie\Permission\Models\Role;

class Roles extends Role {

    protected $fillable = [
        'name', 'guard_name', 'role_category_id', 'display_name', 'description'
    ];

}
