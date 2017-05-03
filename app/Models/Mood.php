<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mood extends Model
{
    protected $fillable = ['status'];


    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

}
