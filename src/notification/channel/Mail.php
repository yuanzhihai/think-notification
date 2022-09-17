<?php

namespace yzh52521\notification\channel;

use yzh52521\mail\Mailable;
use yzh52521\mail\Mailer as Mailer;
use yzh52521\Notification;
use yzh52521\notification\Channel;
use yzh52521\notification\MailableMessage;
use yzh52521\notification\message\Mail as MailMessage;
use yzh52521\notification\Notifiable;

class Mail extends Channel
{
    /** @var Mailer */
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * 发送通知
     * @param Notifiable $notifiable
     * @param Notification $notification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $this->getMessage($notifiable, $notification);

        if ( $message instanceof MailMessage ) {
            $message = new MailableMessage($message, $notification);
        }

        if ( $message instanceof Mailable ) {
            $this->mailer->send($message);
        }
    }
}
