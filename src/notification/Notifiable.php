<?php

namespace yzh52521\notification;

use Exception;
use think\facade\Log;
use think\helper\Str;
use yzh52521\facade\Notification;

trait Notifiable
{
    public function notify($instance)
    {
        try {
            Notification::send($this, $instance);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function mustNotify($instance)
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
