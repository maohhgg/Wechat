<?php

namespace Wechat\common;

/**
 *  设置和获取 配置
 *  config('a.b.c') config('a.d.c','abc')
 * @param $name
 * @param string $option
 * @return null
 */
function config($name, $option = '')
{
    if (!$option) {
        $array = explode('.', $name);
        $conf = $_ENV;
        foreach ($array as $item) {
            $conf = $conf[$item];
        }
        return $conf;
    } else {
        $array = explode('.', $name);
        $config = setConfig($array, $option);
        $_ENV = array_merge_recursive($_ENV, $config);
    }
    return null;
}

/**
 * 递归生成数组
 * e. 'a.b.c.d' = 100
 * setConfig(exploe('.', 'e.b.c.d'), 100) // retrun ['a' => ['b' => ['c' =>['d' => 100 ]]]]
 * @param string $array
 * @param $option
 * @return array
 */
function setConfig($array = '', $option)
{
    if (count($array) == 1) return [$array[0] => $option];

    return [array_shift($array) => setConfig($array, $option)];
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
    $class = '\App\Model\\' . ucfirst($name);
    return new $class;
}
