<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Userinvitation extends Model {

    use HasFactory;

    protected $table = 'tbl_users_invitation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_email', 'company_id', 'company_code', 'resend', 'status'
    ];

}
