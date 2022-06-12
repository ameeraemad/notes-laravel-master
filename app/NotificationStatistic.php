<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationStatistic extends Model
{
    //
    protected $fillable = ['user_id'];

    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
