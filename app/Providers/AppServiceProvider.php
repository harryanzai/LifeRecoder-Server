<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Gallery;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('zh');

        // 字段映射
        Relation::morphMap([
            'galleries' => Gallery::class,
            'comments' => Comment::class,
            'favorites' => Favorite::class,
            'votes' =>Vote::class

        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
