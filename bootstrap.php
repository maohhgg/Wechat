<?php
ini_set('display_errors', 'on');
include __DIR__ . '/vendor/autoload.php';

define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app/');

$app = new Wechat\Stone\Application();

$app->singleton(
    Wechat\Contracts\Kernel::class,
    App\Kernel::class
);
// App\Kernel::class($app);
$aa = new ReflectionClass(App\Kernel::class);
$bb = $aa->getConstructor();
print_r($bb);
$handle = $app->bulid(App\Kernel::class);
$handle->handle();