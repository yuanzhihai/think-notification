<?php

namespace yzh52521\facade;

use think\Facade;
use yzh52521\notification\Sender;

/**
 * Class Mail
 *
 * @package yzh52521\facade
 * @mixin Sender
 */
class Notification extends Facade
{
    protected static function getFacadeClass()
    {
        return Sender::class;
    }
}
