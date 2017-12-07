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


    // TODO 1.搭建后台管理
    // TODO 2.下面数据最好保存到数据库中，使用后台管理来管理
    // 程序中所有会出现的文本
    'tip' => [
        'subscribe' => [
            'first' => "🎉感谢关注！🎉 😘 \n发送“/帮助 or /help”获取帮助",  // 第一次关注
            'back' => "欢迎回来！	😘🎉 \n发送“/帮助 or /help”获取帮助",   // 重新关注
            'not' => "你还没有关注本宝宝 😭"  // 没有关注发送消息
        ],
        'user' => [
            'hello' => ['こんにちは','안녕하세요.','bonjour','Guten Tag','hallo','Ciao','привет','Hola','Saluton','สวัสดี'],

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
                "下载 n             获取第n项\n"
        ],
        'message' => [
            'error' => "“%s”意义不明！ 😱🙅\n" .       // 发送消息没有匹配提示 %s内为操作内容
                "回复“bug *” 将您的建议或意见反馈给我 🙏",

            'support' => "😱😱 暂时不支持这种消息",   // 用户发送来的视频或者图片
            'notGetContent' => "没有get到你的消息 😱"   // 用户发送空的消息
        ],
        'movie' => [
            'news' => "导演：%s\n" .
                "主演：%s\n\n" .
                "简介:\n  %s\n\n" .
                "回复“下载”获取磁力链接",
            'noContent' => "没有 “%s” 的内容 😱😱",   // 数据库中查询不到
//            'errorContent' => "%s%s叫%s的%s"       // 根据发送来的条件拼接可读的语句
        ],
        'magnet' => [
            'text' => "共搜索到 %d 个种子, 当前显示 %d 个\n".
                "回复文字 “下一页” 进行翻页\n\n %s".
                "双击文本，进行选定复制",

            'news' => "--------------------------------------------------\n".
                "[%s]\n".
                "                                           %s\n".
                "%s\n".
                "--------------------------------------------------\n\n",
        ]
    ]
];

