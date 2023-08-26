<?php

namespace yzh52521\notification;

use Symfony\Component\Mime\Email;
use think\helper\Str;
use think\view\driver\Twig;
use yzh52521\mail\Mailable;
use yzh52521\Notification;
use yzh52521\notification\message\Mail;

class MailableMessage extends Mailable
{

    public function __construct(protected Mail $message,protected Notification $notification)
    {
    }

    public function build()
    {
        $message = $this->message;

        if ( $message->view ) {
            $this->view($message->view, $message->data());
        }

        if ( !empty($message->from) ) {
            $this->from($message->from[0], isset($message->from[1]) ? $message->from[1] : null);
        }

        if ( is_array($message->to) ) {
            $this->bcc($message->to);
        } else {
            $this->to($message->to);
        }

        $this->subject($message->subject ?: Str::title(
            Str::snake(class_basename($this->notification), ' ')
        ));

        foreach ( $message->attachments as $attachment ) {
            $this->attach($attachment['file'], $attachment['options']);
        }

        foreach ( $message->rawAttachments as $attachment ) {
            $this->attachData($attachment['data'], $attachment['name'], $attachment['options']);
        }

        $this->withSymfonyMessage(function (Email $message) {
            if ( !is_null($this->message->priority) ) {
                $message->priority((int)$this->message->priority);
            }
        });
    }

}
