<?php

namespace App\Model;

use function \Wechat\common\config;
use \Wechat\Model\Model;

class Movie extends Model
{
    protected $table = 'movie';
    public $timestamps = false;

    public static $bindArrayParam = ['id', 'director', 'actor', 'chinese_name', 'local', 'type', 'douban', 'description', 'img'];

    public function bindArray($needle)
    {
        $format = config('tip.movie.news');
        return [
            'id' => $needle['id'],
            'title' => $needle['chinese_name'],
            'description' => sprintf($format, $needle['director'], $needle['actor'], $needle['type'], $needle['local'], $needle['description']),
            'picUrl' => config('app.http') . config('app.host') . config('app.images') . "g_" . $needle['img'] . '.webp',
            'url' => config('app.https') . config('app.detail') . $needle['douban']
        ];
    }

}