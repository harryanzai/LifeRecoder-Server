<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Gallery;
use App\Models\Photo;
use App\Policies\CommentPolicy;
use App\Policies\GalleryPolicy;
use App\Policies\PhotoPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Photo::class => PhotoPolicy::class,
        Gallery::class => GalleryPolicy::class,
        Comment::class => CommentPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
