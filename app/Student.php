<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Student extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['users_count'];

    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }

    public function users()
    {
        return $this->hasMany(User::class, 'student_api_uuid', 'api_uuid');
    }

    public function categories()
    {
        return $this->hasManyThrough(Category::class, User::class, 'student_api_uuid', 'user_id', 'api_uuid', 'id');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'sender');
    }
}
