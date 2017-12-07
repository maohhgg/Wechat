<?php

namespace app;


use App\Handle\Event;
use App\Model\Messages;
use App\Model\User;
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
        $msgContent = $message->getMessageContent();

        if ($message->isSubscribeEvent()) {
            $status = Event::model(User::class)->subscribe($msgContent);

            $status == Event::TYPE_FIRST ?
                $message->setMessageContent(config('tip.subscribe.first'))->send() :
                $message->setMessageContent(config('tip.subscribe.back'))->send();

        } else if ($message->isUnSubscribeEvent()) {

            Event::model(User::class)->unSubscribe($msgContent);

        } else {
            $status = Event::model(Messages::class, User::class)->message($msgContent);

            if ($status == Event::TYPE_UNSUBSCRIBE) {
                $message->setMessageContent(config('tip.subscribe.not'))->send();

            } else if ($status == Event::TYPE_NORMAL) {

                if (!isset($msgContent['error'])) {

                    $response = Handler::setText($msgContent['content'])->analyst();

                    $message->setMessageContent($response)->send();
                } else {
                    $message->setMessageContent(config('tip.user.support'))->send();
                }
            }

        }

    }
}