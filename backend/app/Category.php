<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'user_id', 'color'];

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(user::class);
    }

    public function timers()
    {
        return $this->hasMany(Timer::class);
    }

    public function scopeMine($query)
    {
        return $query->whereUserId(auth()->user()->id);
    }
}
