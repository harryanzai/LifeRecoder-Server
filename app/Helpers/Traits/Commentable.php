<?php

namespace App\Helpers\Traits;

use App\Models\Comment;


trait Commentable
{
    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments->count();
    }



}