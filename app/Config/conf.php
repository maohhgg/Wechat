<?php
return $config = [
    'token' => '',
    'AppID' => '',
    'AppSecret' => '',
    'originID' => '',   // 公众号设置-> 帐号详情 -> 原始id


    'database' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'database' => '',
        'username' => '',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => ''
    ],

    'app' => [
        'http' => 'http:',
        'https' => 'https:',
        'host' => '//104.194.65.219/wechat/', // 项目访问的地址
        'images' => 'public/g/',
        'detail' => '//movie.douban.com/subject/'
    ],

    /*'douban' => [
        'https' => [
            'small' => '//img3.doubanio.com/view/photo/s/public/',
            'middle' => '//img3.doubanio.com/view/photo/m/public/',
            'large' => '//img3.doubanio.com/view/photo/l/public/',
        ]
    ],*/

    'page' => [
        'number' => 5  //最大不能超过 7 超过微信服务器不会接受
    ]
];

