<?php

namespace App\Model;

use function Wechat\common\config;
use Wechat\Model\Model;

class User extends Model
{
    protected $table = 'users';
    public $timestamps = false;


    public function help($data)
    {
        return config('tip.user.help');
    }

    public function error($data)
    {
        return sprintf(config('tip.user.error'), $data['param']['other']);
//        return json_encode($data);
    }
}