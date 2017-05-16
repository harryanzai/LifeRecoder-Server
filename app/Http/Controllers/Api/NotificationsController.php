<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use App\Transformers\NotificationsTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class NotificationsController extends ApiController
{

    // 获取用户的所有动态
    public function index()
    {
//        $user = Auth::user();
//
//        $unreadNoti = $user->unreadNotifications;

        $limit = Input::get('limit') ?: 15;
        $notifactions = Notification::latest()->paginate($limit);

        return $this->respondWithPaginator($notifactions,new NotificationsTransformer);


    }

    // 全部标记为已读
    public function makeAsRead()
    {

        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return $this->respondWithMessage('标记成功');

    }

    //未读的消息数量
    public function unreadCount()
    {

        $user = Auth::user();
        return $this->respondWithSuccess([
            'count' => $user->unreadNotifications->count
        ]);

    }


}
