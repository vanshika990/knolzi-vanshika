<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationHistory extends Model {

    use HasFactory;

    /**
     * @var type 
     */
    protected $table = 'tbl_notification_history';
    protected $fillable = [
        'id', 'notification_id', 'user_id', 'status'
    ];

}
