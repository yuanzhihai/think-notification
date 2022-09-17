<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

namespace yzh52521\notification\model;

use think\model\Collection;

class NotificationCollection extends Collection
{
    /**
     * 标记已读
     */
    public function markAsRead()
    {
        $this->each(function (Notification $notification) {
            $notification->markAsRead();
        });
    }
}