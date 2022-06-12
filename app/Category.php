<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //

    protected $appends = ['notes_count'];

    public function getNotesCountAttribute()
    {
        return $this->notes()->count();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'category_id', 'id');
    }
}
