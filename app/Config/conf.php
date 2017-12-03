<?php
return $config = [
    'token' => '',
    'AppID' => '',
    'AppSecret' => '',
    'originID' => '',   // 公众号设置-> 帐号详情 -> 原始id


    'database' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'database' => 'wechat',
        'username' => 'root',
        'password' => 'mao555hg',
        'charset' => 'utf8',
        'collation' => 'utf8_general_ci',
        'prefix' => ''
    ],

    'app' => [
        'http' => 'http:',
        'https' => 'https:',
        'host' => '', // 项目访问的地址
        'images' => 'public/images/',
        'detail' => '//movie.douban.com/subject/'
    ],

    'douban' => [
        'https' => [
            'small' => '//img3.doubanio.com/view/photo/s/public/',
            'middle' => '//img3.doubanio.com/view/photo/m/public/',
            'large' => '//img3.doubanio.com/view/photo/l/public/',
        ]
    ],

    'page' => [
        'number' => 5
    ]
];

