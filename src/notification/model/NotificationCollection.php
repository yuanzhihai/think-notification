<?php

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