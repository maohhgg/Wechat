<?php

namespace App\Model;


use function Wechat\common\config;
use Wechat\Model\Model;

class Magnet extends Model
{
    protected $table = 'magnet';
    public $timestamps = false;

    public static $bindArrayParam = ['name', 'file_size', 'link'];

    public function bindArray($needle)
    {
        return sprintf(
            config('tip.magnet.news'),
            $needle['name'],
            $needle['file_size'],
            $needle['link']
        );
    }
}