<?php
/**
 * Created by PhpStorm.
 * User: wangju
 * Date: 2017/5/9
 * Time: 下午10:46
 */

namespace App\Transformers;


use App\Models\Notification;
use League\Fractal\TransformerAbstract;

class NotificationsTransformer extends TransformerAbstract
{
    public function transform(Notification $noti)
    {
        return [
            'type' => snake_case(class_basename($noti->type)),
            'noti_id' => $noti->notifiable_id,
            'noti_type' => snake_case(class_basename($noti->notifiable_type)),
            'content' => json_decode($noti->data,true),
            'create_at' => $noti->created_at->toDateTimeString(),
            'read_at' => $noti->read_at

        ];
    }

}