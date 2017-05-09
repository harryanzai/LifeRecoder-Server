<?php

namespace App\Models;

use App\Helpers\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Favorite extends Model
{

    use RecordsActivity;

    protected $guarded = [];

    public function favorited()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    protected static function boot()
    {
        parent::boot();
        if (Auth::guest()) return;

        static::created(function ($favorite){


            $favorite_type = $favorite->favorited;
            $user = $favorite_type->user;

            $user->setFavoritedMessage($favorite);

        });



    }


}
