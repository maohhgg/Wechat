<?php

namespace App\Handle;


use App\Model\Magnet;
use \App\Model\Movie;
use \App\Model\User;
use Illuminate\Support\Collection;
use \Wechat\bulid\Message;
use function Wechat\common\config;

/**
 * 消息处理类
 *
 * Class Handler
 * @package App\Handle
 */
class Handler
{
    const ERROR_DEV = 'developer';
    const ERROR_USER = 'user';

    protected $msgValue;
    protected $msgMean;

    protected $result;

    protected $status;
    protected $errorTip;

    /**
     * 将发送来的中文语句分解成数组
     * @param $text
     * @return $this
     */
    protected function textConvert($text)
    {
        $keys = ['param', 'param_index', 'action', 'action_index', 'other'];
        $values = explode(' ', $text);

        // 保证中文处理后长度始终等于$keys的长度
        if (count($values) > count($keys)) {
            while (count($values) > count($keys)) {
                $values[count($keys) - 1] .= array_pop($values);
            }
        } else {
            while (count($values) < count($keys)) {
                array_push($values, null);
            }
        }

        $this->msgValue = array_combine($keys, $values); // $keys作为key $values作为value 合并数组
        return $this;
    }

    /**
     * 根据中文分解后的数组中的 param 判断处理方式
     * @return $this
     */
    protected function bind()
    {
        $this->msgMean = $this->msgValue;
        $this->msgMean['param'] = Example::getExample($this->msgValue['param']);
        $this->msgMean['param']['param'] = $this->msgValue['param_index'] ? $this->msgValue['param_index'] : null;

        $this->msgMean['action'] = Example::getExample($this->msgValue['action']);
        if ($this->msgValue['action_index']) {
            $this->msgMean['action']['param'] = $this->msgValue['action_index'];
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function process()
    {
        $msgType = Message::MSG_TYPE_TEXT;
        $result = config('tip.message.notGetContent');

        $param = $this->msgMean['param'];
        $action = $this->msgMean['action'];
        var_dump($this->msgMean);
        switch ($param['model']) {
            // 查找电影
            case Example::MODEL_MOVIE:

                // 附加条件为movie
                if ($action && $action['model'] == Example::MODEL_MOVIE && $action['type'] == Example::TYPE_OPTION) {

                    $param['index'] = [$action['index'], $param['index']];
                    $param['param'] = [$action['param'], $param['param']];
                }

                $msgType = Message::MSG_TYPE_NEWS;
                // 传入模型去处理 得到电影或电视剧信息
                $result = (new Process(Movie::class))->run($param, Movie::$bindArrayParam);

                if (!$result) {
                    $msgType = Message::MSG_TYPE_TEXT;
                    $result = sprintf(config('tip.movie.noContent'), $this->msgMean['param_index']."+".$this->msgMean['action_index']);
                    break;
                }

                // 附加条件 "下载"
                if ($action && $action['model'] == Example::MODEL_MAGNET && $result['count'] == 1) {

                    $action['param'] = $result['content'][0]['id'];

                    $msgType = Message::MSG_TYPE_TEXT;
                    $array = (new Process(Magnet::class))->run($action, Magnet::$bindArrayParam);

                    if ($array) {
                        $result = sprintf(
                            config('tip.magnet.text'),
                            $array['total'],
                            $array['count'],
                            implode('', $array['content'])
                        );
                    } else {
                        $result = $result = sprintf(config('tip.user.noContent'), $result['content'][0]['title']);
                    }
                }

                break;


            case Example::MODEL_USER:
                $result = (new Process(User::class))->run($param);
                break;
        }

        $this->result = [$result, $msgType];
        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public static function setText($text = '')
    {
        if (!$text) {
            return (new static())->error(config('tip.message.notGetContent'))->getResult();
        }
        return (new static())->textConvert($text);
    }

    /**
     * @return $this
     */
    public function analyst()
    {
        return $this->bind()->process()->getResult();
    }

    /**
     * @return mixed
     */
    protected function getResult()
    {
        return $this->result;
    }


    /**
     * @param string $tip
     * @param string $sendTo
     * @return $this
     */
    protected function error($tip = '', $sendTo = '')
    {
        $sendTo == self::ERROR_USER ? $this->result = $tip : $this->errorTip = $tip;

        return $this;
    }

    /**
     * 运行中的错误 使用log保存下来
     * @return mixed
     */
    public function logError()
    {

    }


}
