<?php

namespace yzh52521\notification\message;


use yzh52521\EasyHttp\Http;

class Sendcloud
{
    protected $user;
    protected $key;

    protected $host = "http://www.sendcloud.net/";

    protected $template;

    protected $msgType = 0;

    protected $to;

    protected $data;

    protected $isVoice = false;

    public function __construct($user, $key)
    {
        $this->user = $user;
        $this->key  = $key;
    }

    /**
     * 短信模板
     * @param $template
     * @return $this
     */
    public function template($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * 设置为彩信
     * @return $this
     */
    public function isMultimedia()
    {
        $this->msgType = 1;
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
     * 设为语音短信
     * @return $this
     */
    public function isVoice()
    {
        $this->isVoice = true;
        return $this;
    }

    protected function signature(&$params)
    {
        $sParamStr = "";
        ksort($params);
        foreach ($params as $sKey => $sValue) {
            if (is_array($sValue)) {
                $value     = implode(";", $sValue);
                $sParamStr .= $sKey . '=' . $value . '&';
            } else {
                $sParamStr .= $sKey . '=' . $sValue . '&';
            }
        }
        $sParamStr           = trim($sParamStr, '&');
        $sSignature          = md5($this->key . "&" . $sParamStr . "&" . $this->key);
        $params['signature'] = $sSignature;
    }

    /**
     * @internal
     */
    public function send()
    {
        if ($this->isVoice) {
            $params = [
                'phone'   => $this->to,
                'code'    => $this->data,
                'smsUser' => $this->user
            ];
            $url    = $this->host . 'smsapi/send';
        } else {
            $params = [
                'templateId' => $this->template,
                'msgType'    => $this->msgType,
                'phone'      => $this->to,
                'vars'       => json_encode($this->data),
                'smsUser'    => $this->user
            ];
            $url    = $this->host . 'smsapi/sendVoice';
        }

        $this->signature($params);

        $result = Http::post($url,$params)->array();

        if (!$result['result'] || $result['statusCode'] != 200) {
            throw new \RuntimeException($result['message'], $result['statusCode']);
        }

    }

}