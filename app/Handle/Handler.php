<?php

namespace App\Handle;


use \App\Model\Movie;
use App\Model\User;
use Wechat\bulid\Message;
use function Wechat\common\config;

/**
 * 消息处理类
 *
 * Class Handler
 * @package App\Handle
 */
class Handler
{
    const START = 'start';
    const PROCESS = 'processing';
    const END = 'end';

    const DEV = 'developer';
    const USER = 'user';


    protected $msgValue;

    protected $msgMean;

    protected $result;

    protected $status;
    protected $errorTip;

    /**
     * @param $text
     * @return $this
     */
    protected function textConvert($text)
    {
        $keys = ['param', 'index', 'action', 'other'];
        $values = explode(' ', $text);

        if (count($values) > count($keys)) {
            while (count($values) > count($keys)) {
                $values[count($keys) - 1] .= array_pop($values);
            }
        } else {
            while (count($values) < count($keys)) {
                array_push($values, null);
            }
        }

        $this->msgValue = array_combine($keys, $values);
        return $this;
    }

    /**
     * @return $this
     */
    protected function bind()
    {
        $this->msgMean = $this->msgValue;
        $this->msgMean['param'] = Example::getExample($this->msgValue['param']);
        $this->msgMean['action'] = Example::getExample($this->msgValue['action']);
        return $this;
    }

    /**
     * @return $this
     */
    protected function process()
    {
        $this->result = ['没有匹配的内容', Message::MSG_TYPE_TEXT];
        print_r($this->msgMean);
        switch ($this->msgMean['param']['model']) {

            case Example::MOVIE:
                $result = (new Process(Movie::class))->run($this->msgMean,['id','chinese_name','douban','description','img']);
//                print_r($result);
                if ($result) {
                    $this->result = [$result, Message::MSG_TYPE_NEWS];
                }
                break;
            case Example::USER:
                $result = (new Process(User::class))->run($this->msgMean);
                if ($result){
                    $this->result = [$result, Message::MSG_TYPE_TEXT];
                }
                break;
        }
        $this->status = self::END;
        return $this;
    }

    /**
     *
     * @param string $text
     * @return $this
     */
    public static function setText($text = '')
    {
        if (!$text) {
            return (new static())->error('没有内容')->getResult();
        }
        return (new static())->textConvert($text);
    }

    /**
     * @return $this
     */
    public function analyst()
    {
        return $this->bind()->process();
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        if ($this->status == self::END) {
            return $this->result;
        } else {
            return null;
        }
    }


    /**
     * @param string $tip
     * @param string $sendTo
     * @return $this
     */
    protected function error($tip = '', $sendTo = '')
    {
        $this->status = self::END;
        if ($sendTo == self::USER) {
            $this->result = $tip;
        } else {
            $this->errorTip = $tip;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->errorTip;
    }

}
