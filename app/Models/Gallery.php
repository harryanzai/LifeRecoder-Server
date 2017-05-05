<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['title','content','mood_id'];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }


    /**
     * 获取该文章的所有标签
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class,'taggable');
    }

    public function mood()
    {
        return $this->hasOne(Mood::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

}
