<?php

namespace app;

use Wechat\bulid\Handler;
use Wechat\bulid\Message;
use Wechat\Wechat;

class Entry
{
    protected $wechat;

    public function __construct()
    {
        // 每次进行的微信验证
        $this->wechat = new Wechat();
        $this->wechat->valid();
    }

    public function handler()
    {

        $message = new Message();
        if ($message->isSubscribeEvent()) {
            $message->setMessageContent('感谢关注 发送“/帮助”获取帮助')->send();
        } else if ($message->isUnSubscribeEvent()) {
            echo "MMP";
        }

        $msgContent = $message->getMessageContent();
        if (!isset($msgContent['error'])) {

            $message->setMessageContent(
                json_encode(
                    (new Handler())->setText($msgContent['content'])
                        ->Analyst()
                        ->getResult()
                )
            )->send();

        }

    }
}