<?php

namespace yzh52521\notification;

use yzh52521\Notification;
use yzh52521\notification\model\SerializesModel;

class SendQueuedNotifications
{
    use SerializesModel;

    public function __construct(protected $notifiable, protected Notification $notification, protected array $channels = [])
    {
    }

    public function handle(Sender $sender)
    {
        $sender->sendNow($this->notifiable, $this->notification, $this->channels);
    }


    /**
     * 队列任务失败回调
     * @return void
     */
    public function failed()
    {
        if (method_exists($this->notification, 'failed')) {
            $this->notification->failed($this->notifiable);
        }
    }
}
