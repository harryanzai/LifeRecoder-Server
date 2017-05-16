<?php

namespace App\Http\Controllers\Api;


use App\Transformers\GalleryTransformer;
use App\Models\Article;
use App\Models\Gallery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class FollowsController extends ApiController
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth.api');

    }


    // 获取已关注用户发送的文章，图集
    public function index()
    {

        $limit = Input::get('limit') ?: 15;

        $user_ids = Auth::user()->followings->pluck('id')->toArray();

        array_push($user_ids, Auth::user()->id);

        $galleries = Gallery::whereIn('user_id', $user_ids)
            ->with('user')->orderBy('created_at', 'desc')
            ->paginate($limit);
        return $this->respondWithPaginator($galleries,new GalleryTransformer);

    }

    


}
