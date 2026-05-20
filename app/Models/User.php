<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use App\Models\CourseSubscription;
use App\Models\Userinvitation;

class User extends Authenticatable {

    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasRoles;

    protected $table = 'tbl_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'password', 'author_slug', 'status', 'mobile_no', 'age_group', 'date_of_birth', 'profile_image', 'source_from', 'company_name', 'address', 'company_code', 'company_id', 'email_verified_at', 'profile_title', 'about_me','apple_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the Organization record associated with the user.
     * @return type
     */
    public function OrgData() {
        return $this->belongsTo(User::class, 'company_id');
    }

    /**
     * Get the Course Subscription record associated with the user.
     * @return type
     */
    public function coursesubscription() {
        return $this->hasOne(CourseSubscription::class);
    }

}
