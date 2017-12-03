<?php

namespace App\Model;

use Wechat\Model\Model;

class User extends Model
{
    protected $table = 'user';
    public $timestamps = false;

    public function help()
    {
        return
            "/help               获取帮助\n" .
            "/history          获取已查询电影记录\n".
            "/message      获取对话记录\n".
            "/清空电影      清空已查询电影记录\n".
            "/清空对话      清空对话记录\n\n".
            "电影 *         查询名为*的电影\n".
            "         例：电影 这个杀手不太冷\n\n".
            "电影 * 下载\n".
            "下载 *         获取电影*的磁力链接\n".
            "         例：电影 黑客帝国 下载\n".
            "                  下载 这个杀手不太冷\n\n".
            "豆瓣 id        根据豆瓣id查询\n".
            "         例：豆瓣 1295644\n\n".
            "豆瓣 id 下载    获取磁力链接\n".
            "         例：豆瓣 1295644 下载\n\n".
            "主演 **        搜索**主演的电影\n".
            "         例：主演 让·雷诺\n".
            "                  主演 让-雷诺\n\n".
            "下一页\n".
            "上一页            在搜索时翻页\n\n".
            "详细 n             第n项的详细\n".
            "下载 n             获取第n项";
    }
}