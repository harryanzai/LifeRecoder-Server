<?php

namespace App\Models;

use App\Helpers\Traits\RecordsActivity;
use App\Helpers\Traits\Votable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{

    use SoftDeletes,RecordsActivity,Votable;
    protected $fillable = [
        'user_id',
        'body',
        'commentable_id',
        'commentable_type'
    ];

    public static $commentables = ['galleries','articles'];



    protected $dates = ['deleted_at'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function toComment(Request $request,$commentableId)
    {
        $comment_type = $request->comment_type;
        $relation = collect(Relation::morphMap())->toArray();

        $model_type = $relation[$comment_type];

        $model = $model_type::find($commentableId);

        return $model;
    }



    public static function store(Request $request,$commentableId)
    {
        return Auth::user()->comments()->create([
            'body' => $request->body,
            'commentable_type' => $request->comment_type,
            'commentable_id' => $commentableId
        ]);

    }

    protected static function boot()
    {
        parent::boot();

        if (Auth::guest()) return;

        static::creating(function ($comment){
            $comment->user_id = Auth::user()->id;
        });


        static::created(function ($comment){

            $commentType = $comment->commentable;
            $user = $commentType->user;
            $user->setCommentMessage($comment);

        });
    }

}
