<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use App\Transformers\NotificationsTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class NotificationsController extends ApiController
{

    // 获取用户的所有动态
    public function index()
    {
        $limit = Input::get('limit') ?: 15;
        $notifactions = Notification::latest()->paginate($limit);

        return $this->respondWithPaginator($notifactions,new NotificationsTransformer);


    }
}
