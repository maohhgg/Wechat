<?php

namespace app;


use Illuminate\Database\Capsule\Manager as Capsule;
// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

use App\Handle\Handler;
use Wechat\Bulid\Message;
use function Wechat\common\config;
use Wechat\Wechat;

class Entry
{
    protected $wechat;

    public function __construct()
    {
        $capsule = new Capsule;

        $capsule->addConnection(config('database'));

        $capsule->setEventDispatcher(new Dispatcher(new Container));
        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();
        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();

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
                Handler::setText($msgContent['content'])
                    ->Analyst()
                    ->getResult()
            )->send();

        } else {
            $message->setMessageContent($msgContent['error'])->send();
        }

    }
}