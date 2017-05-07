<?php

namespace App\Helpers\Traits;


use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

trait Favoritable
{
    /**
     * 文章和图集可以被喜欢
     * @return mixed
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * 喜欢当前的图集或文章
     * @return mixed
     */
    public function favorite()
    {
        $attributes = ['user_id' => Auth::id()];

        if ( ! $this->favorites()->where($attributes)->exists()){
            return $this->favorites()->create($attributes);
        }
    }


    /**
     * 取消喜欢当前的图集或文章
     */
    public function unfavorite()
    {
        $attributes = ['user_id' => Auth::id()];
        $this->favorites()->where($attributes)->delete();
    }

    /**
     * 判断是否喜欢当前主题
     * @return bool
     */
    public function isFavourite()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    /**
     * 通过属性获取是否喜欢当前主题
     * @return bool
     */
    public function getIsFavoritedAttribute(){
        return $this->isFavourite();
    }


    /**
     * 获取喜欢的数量
     * @return mixed
     */
    public function getFavoritesCountAttribute(){
        return $this->favorites->count();

    }


}