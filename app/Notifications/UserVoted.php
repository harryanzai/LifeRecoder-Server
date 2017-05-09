<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserVoted extends DatabaseNotification
{
    function format()
    {
        $vote = $this->data;

        $user = $vote->user;

        $message = '@'.$user->nickname.' 赞了我的评论';

        $content = $vote->voted;

        $relation = -1;
        $relation_type = null;
        if ($content instanceof Comment){
            $relation = $content->commentable->id;

            $relation_type = snake_case(class_basename($content->commentable));

        }

        return $this->content($message,$content,$user,$relation,$relation_type);
    }

}
