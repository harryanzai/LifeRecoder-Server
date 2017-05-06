<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class Photo extends Model
{
    protected $fillable = ['name','path','thumbnail_path','description'];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    /**
     * 所有的关联将会被连动。
     *
     * @var array
     */
    protected $touches = ['galleries'];



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


    public function delete()
    {
        File::delete([
            $this->path,
            $this->thumbnail_path
        ]);

        return parent::delete();

    }


}
