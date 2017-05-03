<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Photo extends Model
{
    protected $fillable = ['name','path','thumbnail_path','description'];

    public function gallery()
    {
        return $this->belongsTo(Photo::class);
    }


    public function baseDir()
    {
        $time = date("Ym", time()) .'/'.date("d", time()).'/'.Auth::id();
        return "upload/photos/".$time;
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;

        $this->path = $this->baseDir()."/".$name;

        $this->thumbnail_path = $this->baseDir().'/tn-'.$name;

    }
}
