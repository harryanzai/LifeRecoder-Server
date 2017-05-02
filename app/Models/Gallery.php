<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['title','content'];

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

}
