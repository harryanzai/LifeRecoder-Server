<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['title','bio'];


    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function galleriesCount()
    {
        return $this->galleries->count();
    }

    protected $appends = ['galleriesCount'];

    public function getGalleriesCountAttribute()
    {
        return $this->galleriesCount();
    }




}
