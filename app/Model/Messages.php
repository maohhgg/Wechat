<?php


namespace App\Model;


use Wechat\Model\Model;

class Messages extends Model
{
    protected $table = 'messages';
    public $timestamps = false;
    protected $fillable = ['uid','time','type','content','origin','msg_id','is_del'];

}