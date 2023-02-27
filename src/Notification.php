<?php

namespace yzh52521;

use yzh52521\notification\Notifiable;

/**
 * Class Notification
 * @package yzh52521
 * @property string $queue
 * @property integer $delay
 * @property string $connection
 */
abstract class Notification
{

    public $id;

    /**
     * 发送渠道
     * @param Notifiable $notifiable
     * @return array
     */
    abstract public function channels($notifiable);

}
