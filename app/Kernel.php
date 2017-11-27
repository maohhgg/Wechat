<?php

namespace App;

use \Wechat\Contracts\Kernel as Wechat;
use Wechat\Stone\Application;
use Wechat\Stone\Bootstrap\LoadConfiguration;

class Kernel implements Wechat
{

    protected $app;
    protected $bootstrappers = [
        LoadConfiguration::class
    ];

    public function __construct(Application $app)
    {
        $this->app = $app;
//        $this->app->valid();
    }

    public function bootstrap()
    {
        if (!$this->app->hasBeenBootstrapped()) {
            $this->app->bootstrapWith($this->bootstrappers);
        }
    }

    public function handle()
    {
        print_r($this->app);
        echo "App is runing!";
    }

    public function getApplication()
    {
        return $this->app;
    }
}