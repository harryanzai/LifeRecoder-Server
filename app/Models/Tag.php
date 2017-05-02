<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name','bio'];


    // 获取该标签下的所有图集
    public function galleries()
    {
        return $this->morphedByMany(Gallery::class,'taggable');
    }

}
