<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['name','path','thumbnail_path','description'];

    public function gallery()
    {
        return $this->belongsTo(Photo::class);
    }
}
