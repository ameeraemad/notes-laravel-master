<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    public function sender()
    {
        return $this->morphTo();
    }

    public function notificationStatistics()
    {
        return $this->hasMany(NotificationStatistic::class, 'notification_id', 'id');
    }

    public function receivers()
    {
        return $this->belongsToMany(User::class, NotificationStatistic::class, 'notification_id', 'user_id');
    }
}
