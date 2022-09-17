<?php

namespace yzh52521\notification;

use RuntimeException;
use yzh52521\Notification;

abstract class Channel
{

    /**
     * 发送通知
     * @param Notifiable $notifiable
     * @param Notification $notification
     */
    abstract public function send($notifiable, Notification $notification);

    /**
     * 获取通知数据
     * @param Notifiable $notifiable
     * @param Notification $notification
     * @return mixed
     */
    protected function getMessage($notifiable, Notification $notification)
    {
        $toMethod = 'to' . class_basename($this);

        if ( method_exists($notification, $toMethod) ) {
            return $notification->$toMethod($notifiable);
        }

        throw new RuntimeException(
            "Notification is missing {$toMethod} method."
        );
    }

}
