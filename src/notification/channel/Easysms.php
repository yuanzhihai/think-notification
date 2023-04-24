<?php

namespace yzh52521\notification\channel;

use yzh52521\Notification;
use yzh52521\notification\Channel;
use yzh52521\notification\Notifiable;
use yzh52521\notification\trait\PhoneNumber;

class Easysms extends Channel
{
    /**
     * 发送通知
     * @param Notifiable $notifiable
     * @param Notification $notification
     */
    public function send($notifiable,Notification $notification)
    {

        $message = $this->getMessage( $notifiable,$notification );

        if ($message instanceof \yzh52521\notification\message\Easysms) {
            $message->send();
        }

    }
}