<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

namespace yzh52521\notification\channel;

use think\Model;
use yzh52521\Notification;
use yzh52521\notification\Channel;
use yzh52521\notification\Notifiable;

class Database extends Channel
{

    /**
     * 发送通知
     * @param Notifiable   $notifiable
     * @param Notification $notification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $this->getMessage($notifiable, $notification);

        /** @var Model $model */
        $model = $notifiable->getPreparedData('database');

        $model->save([
            'id'        => $notification->id,
            'type'      => get_class($notification),
            'data'      => $message,
            'read_time' => null
        ]);

    }
}