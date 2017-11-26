<?php
namespace app;
use wechat\bulid\Message;
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
        if ($message->isSubscribeEvent()) {
            $message->setMessageContent('感谢关注 发送“/帮助”获取帮助')->send();
        } elseif ($message->isUnSubscribeEvent()) {
        }
        $msgContent = $message->getMessageContent();
        if (!isset($msgContent['error'])) {
//            $message->setMessageContent([1,[$msgContent['content'],'test','http://104.194.65.219/wechat/1.png','magnet:?xt=urn:btih:40e11fc80842add338235ca8ff85afe8e861b983']], Message::MSG_TYPE_NEWS);
//            $message->send();
            $message->setMessageContent('magnet:?xt=urn:btih:40e11fc80842add338235ca8ff85afe8e861b983')->send();
            $message->setMessageContent('11')->send();
        }
    }
}