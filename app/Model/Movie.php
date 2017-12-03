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
        return [
            'title' => $needle['chinese_name'],
            'description' => $needle['description'],
            'picUrl' => config('app.http') . config('app.host') . config('app.images') . $needle['img'],
            'url' => config('app.https') . config('app.detail') . $needle['douban']
        ];
    }

}