<?php
ini_set('display_errors','on');
include(__DIR__ . '/wechat/common/function.php');

function __autoload($class)
{
    include str_replace('\\', '/', $class) . ".php";
}

\wechat\common\configApp(__DIR__ . '/app/config/conf.php');
(new \App\Entry())->handler();
