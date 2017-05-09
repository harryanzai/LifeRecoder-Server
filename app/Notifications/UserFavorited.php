<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserFavorited extends DatabaseNotification
{

    function format()
    {

        $favorite = $this->data;

        $user = $favorite->user;

        $message = '@'.$user->nickname.' 喜欢了我的文章\图集';

        $content = $favorite->favorited;

        $relation = $content->id;

        return $this->content($message,null,$user,$relation);
    }
}
