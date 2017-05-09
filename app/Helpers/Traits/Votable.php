<?php

namespace App\Helpers\Traits;


use App\Models\Vote;
use Illuminate\Support\Facades\Auth;

trait Votable
{
    /**
     * 评论可以点赞
     * @return mixed
     */
    public function votes()
    {
        return $this->morphMany(Vote::class, 'voted');
    }

    /**
     * 赞当前的评论
     * @return mixed
     */
    public function vote()
    {
        $attributes = ['user_id' => Auth::id()];

        if ( ! $this->votes()->where($attributes)->exists()){
            return $this->votes()->create($attributes);
        }
    }


    /**
     * 取消点赞
     */
    public function unvote()
    {
        $attributes = ['user_id' => Auth::id()];
        $this->votes()->where($attributes)->delete();
    }

    /**
     * 判断是否赞了当前主题
     * @return bool
     */
    public function isVote()
    {
        return !! $this->votes->where('user_id', auth()->id())->count();
    }

    /**
     * 通过属性获取是否赞了当前主题
     * @return bool
     */
    public function getIsVotedAttribute(){
        return $this->isVote();
    }


    /**
     * 获取点赞的数量
     * @return mixed
     */
    public function getVotesCountAttribute(){
        return $this->votes->count();

    }

}