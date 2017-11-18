<?php

namespace app;

use wechat\Wechat;

class Entry
{
    protected $wechat;

    public function __construct($config = [])
    {
        $this->wechat = new Wechat($config);
        $this->wechat->valid();
    }

    public function handler()
    {

        $message = $this->wechat->instance('message');
        if ($message->isTextMsg()) {
            $message->text('你发送的文本消息 + 你的AccessToken：' . $this->wechat->getAccessToken());
        } elseif ($message->isImageMsg()) {
            $message->text('你发送的图片消息');
        } elseif ($message->isVoiceMsg()) {
            $message->text('你发送的语音消息');
        } elseif ($message->isLocationMsg()) {
            $message->text('你发送的定位消息');
        } elseif ($message->isLinkMsg()) {
            $message->text('你发送的链接');
        }

    }
}