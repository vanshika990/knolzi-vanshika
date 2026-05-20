<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserHasOrganization extends Model {

    use HasFactory;

    /**
     *
     * @var type 
     */
    protected $table = 'tbl_user_has_org';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'org_id', 'user_id'
    ];

    /**
     * get user data associated with organization
     * @return type
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

}
