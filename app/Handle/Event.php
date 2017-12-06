<?php

namespace App\Handle;

use Illuminate\Database\Eloquent\Model;

/**
 * 事件处理
 * Class Event
 * @package App\Handle
 */
class Event
{
    protected $model;
    protected $user;

    const TYPE_SUBSCRIBED = 'subscribed';
    const TYPE_FIRST = 'subscribe';
    const TYPE_UNSUBSCRIBE = 'unSubscribe';
    const TYPE_NORMAL = 'normal';

    public static function model($model, $more = null)
    {
        $event = new self();
        $event->setModel($model, $more);
        return $event;
    }

    private function setModel($model, $more = null)
    {
        $this->model = new $model();
        if ($more) {
            $this->user = new $more();
        }
    }

    /**
     * 用户关注
     * @param $msg
     * @return string
     */
    public function subscribe($msg)
    {
        if (is_array($msg && $this->model)) {
            $user = ($this->user)->select(['id'])->where('token', $msg['uid'])->first();

            // 判断是否是重新关注或新关注的用户
            if (!$user|| ($user->is_del == true)) {

                ($this->model)->where('id', $user->id)->update(['is_del' => false]);
                return self::TYPE_SUBSCRIBED;
            } else {

                ($this->model)->created_at = $msg['time'];
                ($this->model)->token = $msg['uid'];  // 用户唯一UID 用于在网页和公众号判断用户唯一
                ($this->model)->is_del = false;  // 用户是否已关注
                ($this->model)->admin = false;  // 管理员权限
                ($this->model)->save();
                return self::TYPE_FIRST;
            }
        }
    }

    /**
     * 用户取消关注
     * @param $msg
     * @return string
     */
    public function unSubscribe($msg)
    {
        if (is_array($msg && $this->model)) {
            $user = ($this->user)->select(['id'])->where('token', $msg['uid'])->first();

            if (!$user || ($user->is_del == true)) {
                return self::TYPE_UNSUBSCRIBE;
            } else {
                ($this->model)->where('id', $user->id)->update(['is_del' => true]);
            }
        }
        return self::TYPE_NORMAL;
    }

    /**
     * 用户发送消息
     *  用于保存用户发送消息 // 判断用户活性
     * @param $msg
     * @return string
     */
    public function message($msg)
    {
        if (is_array($msg) && $this->user && $this->model) {
            $user = ($this->user)->select(['id', 'is_del'])->where('token', $msg['uid'])->first();

            if (!$user || ($user->is_del == true)) return self::TYPE_UNSUBSCRIBE;

            ($this->user)->where('id', $user->id)->update(['updated_at' => $msg['time']]);

            $msg['uid'] = $user->id;
            $msg['is_del'] = false;
            ($this->model)->fill($msg)->save();
        }
        return self::TYPE_NORMAL;
    }
}