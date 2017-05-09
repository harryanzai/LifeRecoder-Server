<?php

namespace App\Models;

use App\Helpers\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Vote extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    public function voted()
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

        static::created(function ($vote){

            $vote_type = $vote->voted;
            $user = $vote->user;

            $user->setVotedMessage($vote);

        });


    }

}
