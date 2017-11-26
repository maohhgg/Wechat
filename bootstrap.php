<?php
ini_set('display_errors','on');
include(__DIR__ . '/wechat/common/function.php');
include __DIR__ . '/vendor/autoload.php';

\Wechat\common\configApp(__DIR__ . '/app/config/conf.php');

(new \App\Entry())->handler();