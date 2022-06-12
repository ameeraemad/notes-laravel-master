<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $guard = 'users_api';

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

    protected $appends = ['categories_count', 'notes_count'];

    public function getCategoriesCountAttribute()
    {
        return $this->categories()->count();
    }

    public function getNotesCountAttribute()
    {
        return $this->notes()->count();
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'user_id', 'id');
    }

    public function notes()
    {
        return $this->hasManyThrough(Note::class, Category::class, 'user_id', 'category_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_api_uuid', 'api_uuid');
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, NotificationStatistic::class, 'user_id', 'notification_id');
    }
}
