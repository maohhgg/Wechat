<?php

namespace App\Model;

use function \Wechat\common\config;
use \Wechat\Model\Model;

class Movie extends Model
{
    protected $table = 'movie';
    public $timestamps = false;


    public function bindArray($needle)
    {
        $format = "导演：%s\n主演：%s\n\n简介:\n  %s\n\n回复“下载”获取磁力链接";
        return [
            'title' => $needle['chinese_name'],
            'description' => sprintf($format, $needle['director'], $needle['actor'], $needle['description']),
            'picUrl' => config('app.http') . config('app.host') . config('app.images') . "g_" . $needle['img'] . '.webp',
            'url' => config('app.https') . config('app.detail') . $needle['douban']
        ];
    }

}