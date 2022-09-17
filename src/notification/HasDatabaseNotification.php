<?php

namespace yzh52521\notification;

use think\Model;
use yzh52521\notification\model\Notification;

/**
 * Class HasDatabaseNotification
 * @package yzh52521\notification
 *
 * @mixin Model
 */
trait HasDatabaseNotification
{
    /**
     * 所有通知
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')
            ->order('create_time', 'desc');
    }

    /**
     * 未读通知
     */
    public function unreadNotifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')
            ->where('read_time', null)
            ->order('create_time', 'desc');
    }

    public function prepareDatabase()
    {
        return $this->notifications();
    }

}