<?php

namespace App\Notifications;

use App\Helpers\Notifaction\NotifactionFormatter;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserCommented extends DatabaseNotification
{
    function format()
    {
        $comment = $this->data;
        $user = $this->data->user;

        $message = '@'.$user->nickname.' 评论了我的图集';
        $content = $comment->body;
        $relation = $comment->commentable->id;

        return $this->content($message,$content,$user,$relation);
    }

}
