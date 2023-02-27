<?php

namespace yzh52521\notification;

use yzh52521\Notification;

class SendQueuedNotifications
{

    public function __construct(protected $notifiables,protected Notification $notification,  protected array $channels = [])
    {
    }

    public function handle(Sender $sender)
    {
        $sender->sendNow($this->notifiables, $this->notification, $this->channels);
    }


    /**
     * 队列任务失败回调
     * @return void
     */
    public function failed()
    {
        if ( method_exists($this->notification, 'failed') ) {
            $this->notification->failed($this->notifiables);
        }
    }
}
