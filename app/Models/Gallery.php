<?php

namespace App\Models;

use App\Helpers\Traits\Commentable as CommentTrait;
use App\Helpers\Traits\Favoritable;
use App\Helpers\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{

    use CommentTrait,RecordsActivity,Favoritable;

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


    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($gallery){
            $gallery->photos->each->delete();
        });


    }


}
