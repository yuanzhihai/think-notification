<?php

namespace yzh52521\notification;

use yzh52521\notification\command\Notification;
use yzh52521\notification\command\NotificationTable;
class Service extends \think\Service
{

    public function boot()
    {
        $this->commands([
            NotificationTable::class,
            Notification::class
        ]);
    }
}
