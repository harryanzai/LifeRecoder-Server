<?php

namespace App\Notifications;

use App\Helpers\Notifaction\NotifactionFormatter;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserFollowed extends DatabaseNotification
{

    function format()
    {
        $user = $this->data;

        $message = '@'.$user->nickname.' 关注了我';

        $user = [
            'id' => $user->id,
            'nickname' => $user->nickname,
            'email' => $user->email,
            'avatar' => url($user->avatar),
            'gender' => $user->gender,
        ];

        $relation = $user->id;

        return $this->content($message,null,$user,$relation);
    }
}
