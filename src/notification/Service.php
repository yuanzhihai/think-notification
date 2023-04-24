<?php

namespace yzh52521\notification;

use think\facade\Config;
use yzh52521\notification\command\Notification;
use yzh52521\notification\command\NotificationTable;
use Overtrue\EasySms\EasySms;
class Service extends \think\Service
{

    public function boot()
    {
        $this->commands([
            NotificationTable::class,
            Notification::class
        ]);
    }
    public function register()
    {

        $this->app->bind(EasySms::class,function (){
            $config=Config::get('easysms');
            return new EasySms($config);
        });

    }
}
