<?php

namespace Wechat\Stone\Bootstrap;

use Wechat\Stone\Application;
use Illuminate\Database\Capsule\Manager as Capsule;

class LoadConfiguration
{
    public function bootstrap(Application $app)
    {

        // Eloquent ORM
        $capsule = new Capsule;
        $capsule->addConnection(require APP_PATH . '/Config/database.php');
        $capsule->bootEloquent();

        \Wechat\Common\configApp(APP_PATH . '/Config/conf.php');
    }
}