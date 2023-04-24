<?php

namespace yzh52521\notification\message;

use Overtrue\EasySms\Message;

class Easysms extends Message
{

    protected $template;

    protected $to;

    protected $data;

    protected $content;

    protected $gateway = [];


    public function template($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * 收信人
     * @param $to
     * @return $this
     */
    public function to($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * 替换变量或者语音短信里的验证码
     * @param $data
     * @return $this
     */
    public function data($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param array $gateway
     * @return $this
     */
    public function gateway(array $gateway)
    {
        $this->gateway = $gateway;

        return $this;
    }

    public function send()
    {
        app()->Easysms->send( $this->to,[
            'content'  => $this->content,
            'template' => $this->template,
            'data'     => $this->data,
        ],$this->gateway );
    }
}