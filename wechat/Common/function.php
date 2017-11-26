<?php

namespace Wechat\common;

function config($name, $option = '')
{
    if (!$option) {
        return $_ENV[$name];
    } else {
        $_ENV[$name] = $option;
    }
}

function configApp($file)
{
    $config = include($file);
    foreach ($config as $key => $item) {
        config($key, $item);
    }
}

function model($name)
{
    $class = '\App\model\\' . ucfirst($name);
    return new $class;
}
