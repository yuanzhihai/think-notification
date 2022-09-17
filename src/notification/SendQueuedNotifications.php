<?php

namespace yzh52521\notification;

use yzh52521\Notification;

class SendQueuedNotifications
{
    /** @var Notifiable[] */
    protected $notifiables;

    /** @var Notification */
    protected $notification;

    /** @var array */
    protected $channels = null;

    public function __construct($notifiables, Notification $notification, array $channels = null)
    {
        $this->notifiables  = $notifiables;
        $this->notification = $notification;
        $this->channels     = $channels;
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
