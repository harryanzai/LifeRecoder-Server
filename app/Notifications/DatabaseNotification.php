<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

abstract class DatabaseNotification extends Notification
{

    use Queueable;

    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->format();
    }


    protected function content($message,$content,$user,$relation,$relation_type = null)
    {
        return [
            'message' => $message,
            'content' => $content,
            'user' => $user,
            'relation' => $relation,
            'relation_type' => $relation_type
        ];

    }

    abstract function format();

}