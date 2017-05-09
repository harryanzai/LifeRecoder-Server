<?php

namespace App\Helpers\Notifaction;

use App\Models\User;
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

    public function formatCommented(){


    }

    public function formatVoted()
    {

    }

    public function formatFovorited()
    {

    }


}