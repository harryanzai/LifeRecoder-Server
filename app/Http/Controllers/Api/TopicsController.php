<?php

namespace App\Http\Controllers\Api;

use App\Transformers\Topictransformer;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TopicsController extends ApiController
{

    public function __construct()
    {
        parent::__construct();

    }


    // 获取所有的话题
    public function index()
    {
        $topics = Topic::all();
        return $this->respondWithCollection($topics,new Topictransformer);
    }


    // 获取热门话题
    public function hotTopics()
    {

        $topics = Topic::all()->sortByDesc(function ($topic){
            return $topic->galleriesCount;
        })->slice(0,4)->values();

        return $this->respondWithCollection($topics,new Topictransformer);

    }


}
