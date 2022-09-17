<?php

namespace yzh52521\notification;

use think\helper\Str;
use yzh52521\facade\Notification;

trait Notifiable
{
    public function notify($instance)
    {
        Notification::send($this, $instance);
    }

    public function getPreparedData($channel)
    {
        if (method_exists($this, $method = 'prepare' . Str::studly($channel))) {
            return $this->{$method}();
        }
    }

}
