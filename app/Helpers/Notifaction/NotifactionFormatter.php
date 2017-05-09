<?php

namespace App\Helpers\Notifaction;

use App\Models\User;
use App\Notifications\UserCommented;
use App\Notifications\UserFollowed;

class NotifactionFormatter
{

    protected $data;
    protected $noti;

    public function __construct($noti,$data)
    {
        $this->data = $data;
        $this->noti = $noti;
    }

    public function format()
    {

        if ($this->noti instanceof UserFollowed){
            return $this->formatFollow();
        }

        if ($this->noti instanceof UserCommented){
            return $this->formatComment();
        }

        return [];

    }

    public function formatFollow()
    {
        $user = $this->data;
        return [
            'type' => snake_case(class_basename($this->noti)),
            'message' => '@'.$user->nickname.' 关注了我',
            'user' => [
                'id' => $user->id,
                'nickname' => $user->nickname,
                'email' => $user->email,
                'avatar' => url($user->avatar),
                'gender' => $user->gender,
            ]

        ];
    }

    private function content($message,$content,$user,$relacted)
    {
        return [
            'message' => $message,
            'content' => $content,
            'user' => $user,
            'relacted' => $relacted
        ];

    }

    public function formatComment(){

        $comment = $this->data;
        $user = $this->data->user;

        $message = '@'.$user->nickname.' 评论了我的图集';
        $content = $comment->body;
        $relacted = $comment->commentable->id;

        return $this->content($message,$content,$user,$relacted);

    }

    public function formatVote()
    {


    }

    public function formatFavorite()
    {

    }


}