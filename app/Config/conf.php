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
        'username' => '',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => ''
    ],

    'app' => [
        'http' => 'http:',
        'https' => 'https:',
        'host' => '', // 项目访问的地址
        'images' => 'public/g/',
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
    ],


    // 程序中所有会出现的文本
    'tip' => [
        'subscribe' => [
            'first' => "🎉感谢关注！🎉 😘 \n发送“/帮助 or /help”获取帮助",  // 第一次关注
            'back' => "欢迎回来！	😘🎉 \n发送“/帮助 or /help”获取帮助",   // 重新关注
            'not' => "你还没有关注本宝宝 😭"  // 没有关注发送消息
        ],
        'user' => [
            'help' => "/help               获取帮助\n" .
                "/history          获取已查询电影记录\n" .
                "/message      获取对话记录\n" .
                "/清空电影      清空已查询电影记录\n" .
                "/清空对话      清空对话记录\n\n" .
                "电影 *         查询名为*的电影\n" .
                "         例：电影 这个杀手不太冷\n\n" .
                "电影 * 下载\n" .
                "下载 *         获取电影*的磁力链接\n" .
                "         例：电影 黑客帝国 下载\n" .
                "                  下载 这个杀手不太冷\n\n" .
                "豆瓣 id        根据豆瓣id查询\n" .
                "         例：豆瓣 1295644\n\n" .
                "豆瓣 id 下载    获取磁力链接\n" .
                "         例：豆瓣 1295644 下载\n\n" .
                "主演 **        搜索**主演的电影\n" .
                "         例：主演 让·雷诺\n" .
                "                  主演 让-雷诺\n\n" .
                "下一页\n" .
                "上一页            在搜索时翻页\n\n" .
                "详细 n             第n项的详细\n" .
                "下载 n             获取第n项\n",
            'error' => "“%s”意义不明！ 😱🙅\n" .       // 发送消息没有匹配提示 %s内为操作内容
                "回复“bug *” 将您的建议或意见反馈给我 🙏",
            'noContent' => "没有get到你的消息 😱"
        ]
    ]
];

