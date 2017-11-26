<?php
ini_set('display_errors', 'on');
include(__DIR__ . '/wechat/Common/function.php');
include __DIR__ . '/vendor/autoload.php';

// BASE_PATH
define('BASE_PATH', __DIR__);
// VIEW_BASE_PATH
define('APP_PATH', BASE_PATH . '/app/');

\Wechat\common\configApp(APP_PATH . '/Config/conf.php');
(new \App\Entry())->handler();