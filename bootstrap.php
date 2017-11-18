<?php

function __autoload($class)
{
    include str_replace('\\', '/', $class) . ".php";
}

$config = include(__DIR__ . '/conf.php');
(new \app\Entry($config))->handler();