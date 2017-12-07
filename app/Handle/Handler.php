<?php

namespace App\Handle;


use App\Model\Magnet;
use \App\Model\Movie;
use \App\Model\User;
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

    protected $msgValue;
    protected $msgMean;

    protected $result;

    protected $status;

    /**
     * 将发送来的中文语句分解成数组
     * @param $text
     * @return $this
     */
    protected function textConvert($text)
    {
        $values = explode(' ', $text);
        $this->msgValue = $values;

        for ($i = 0; $i < count($values); $i++) {
            $values[$i] = Example::getExample($values[$i]);
        }
        $this->msgMean = $values;
        return $this;
    }

    /**
     * 根据中文分解后的数组判断处理方式
     * @return $this
     */
    protected function bind()
    {
        $array = [];

        foreach ($this->msgMean as $key => $item) {
            if (is_array($item)) {
                $temp = [];
                if (!isset($array[$item['model']])) $array[$item['model']] = [];

                switch ($item['type']) {
                    case Example::TYPE_OPTION:
                        $temp = ['is_movie', $item['index'] == Example::IS_MOVIE];
                        array_push($array[$item['model']], ['chinese_name', $this->msgValue[$key + 1]]);
                        break;

                    case Example::TYPE_INDEX:
                        $temp = [$item['index'], $this->msgValue[$key + 1]];
                        break;

                    case Example::TYPE_VALUE:
                        $temp = [$item['index'], $item['param']];
                        break;

                    default:
                        $temp = [$item['index']];
                        break;
                }
                array_push($array[$item['model']], $temp);
            }
        }
        $this->msgMean = $array;
        return $this;
    }

    /**
     * @return $this
     */
    protected function process()
    {
        $msgType = Message::MSG_TYPE_TEXT;
        $msgContent = sprintf(config('tip.message.error'), implode(' ', $this->msgValue));


        if (isset($this->msgMean[Example::MODEL_MOVIE])) {
            $msgType = Message::MSG_TYPE_NEWS;
            $result = (new Process(Movie::class))->run($this->msgMean[Example::MODEL_MOVIE], Movie::$bindArrayParam);
            if ($result) {
                $msgContent = $result;
                $this->status = [Example::MODEL_MOVIE, $this->msgMean[Example::MODEL_MOVIE]];
            } else {
                $msgContent = sprintf($msgContent, $this->getChinese($this->msgMean[Example::MODEL_MOVIE]));
            }
        }

        if (isset($this->msgMean[Example::MODEL_MAGNET])) {
            $result = null;
            $msgType = Message::MSG_TYPE_TEXT;
            if (isset($msgContent['count'])) {

                if ($msgContent['count'] == 1) {
                    array_push($this->msgMean[Example::MODEL_MAGNET][0], $msgContent['content'][0]['id']);

                    $result = (new Process(Magnet::class))->run($this->msgMean[Example::MODEL_MAGNET], Magnet::$bindArrayParam);

                } else {
                    $msgContent = sprintf(config('tip.magnet.dataMore'), $msgContent['count']);
                }
            } else {
                array_push($this->msgMean[Example::MODEL_MAGNET][0], User::select('last_at')->where());
            }

            if ($result) {
                $this->status = [Example::MODEL_MAGNET, $msgContent['content'][0]['id']];
                $msgContent = sprintf(
                    config('tip.magnet.text'),
                    $result['total'],
                    $result['count'],
                    $result['total'] > $result['count'] ? config('tip.magnet.more') : "",
                    implode('',$result['content']));
            } else {
                $msgContent = sprintf(config('tip.magnet.noContent'), $msgContent['content'][0]['title']);
            }
        }
        if (isset($this->msgMean[Example::MODEL_USER])) {
            $result = (new Process(User::class))->run($this->msgMean[Example::MODEL_USER][0][0]);
            if ($result) $msgContent = $result;

        }

        $this->result = [$msgContent, $msgType];
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
    protected function error($tip = '')
    {
        $this->result = $tip;
        return $this;
    }

    /**
     * 运行中的错误 使用log保存下来
     * @return mixed
     */
    public function logError()
    {

    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    private function getChinese($data)
    {
        $array = [];
//        return sprintf("周星驰主演刘镇伟导演刘镇伟编剧的中国喜剧电影",$array['actor'],$array['actor'])

    }


}
