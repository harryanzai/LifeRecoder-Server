<?php

namespace App\Models;

use App\Notifications\UserCommented;
use App\Notifications\UserFavorited;
use App\Notifications\UserFollowed;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'email', 'password','mobile'
    ];

    public function galleries()
    {

        return $this->hasMany(Gallery::class);
    }


    /**
     * 获取当前用户的所有照片
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function photos()
    {
        return $this->hasManyThrough(Photo::class,Gallery::class);

    }

    /**
     * 一个用户可以有很多评论
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 一个用户可以有很多关注者
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(User::class,'followers','user_id','follower_id');

    }

    /**
     * 一个用户可以关注很多人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followings()
    {
        return $this->belongsToMany(User::class,'followers','follower_id','user_id');

    }



    public function follow($user_ids)
    {
        if (!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }

        $this->followings()->sync($user_ids,false);


        User::whereIn('id',$user_ids)->get()->each->setFollowedMessage($this);

        Follower::where('follower_id',$this->id)
            ->whereIn('user_id',$user_ids)->get()->each->recordFollows();

    }

    public function setFollowedMessage(User $user)
    {
        $this->notify(new UserFollowed($user));
    }

    public function setCommentMessage($comment)
    {
        $this->notify(new UserCommented($comment));
    }

    public function setFavoritedMessage($favorited)
    {
        $this->notify(new UserFavorited($favorited));

    }

    public function unfollow($user_ids)
    {
        if (!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }

        $this->followings()->detach($user_ids,false);


    }

    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
