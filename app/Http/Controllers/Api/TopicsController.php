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


    public function index()
    {
        $topics = Topic::all();
        return $this->respondWithCollection($topics,new Topictransformer);
    }


}
