<?php

namespace App\Model;

use function Wechat\common\config;
use Wechat\Model\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = ['token','admin','is_del','last_at'];

    public function help($data)
    {
        return config('tip.user.help');
    }

    public function hello($data)
    {
        $msgArray = config('tip.user.hello');
        $index = random_int(0, count($msgArray) - 1);
        return $msgArray[$index];
    }

    public function error($data)
    {
        return sprintf(config('tip.message.error'), $data['other']);
//        return json_encode($data);
    }

    public function bug($data){
        return sprintf(config('tip.message.bug'));
    }
}