<?php

namespace App\Handle;


use \App\Handle\Bean\Mean;
use function Wechat\Common\model;

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
        $keys = ['parameter', 'index', 'action', 'other'];
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
        $this->msgValue = Mean::copy(array_combine($keys, $values));
        return $this;
    }

    /**
     * @return $this
     */
    protected function bind()
    {
        $this->msgMean = $this->msgValue;
        $this->msgMean->parameter = Example::getExample($this->msgValue->parameter);
        $this->msgMean->action = Example::getExample($this->msgValue->action);
        return $this;
    }

    /**
     * @return $this
     */
    protected function process()
    {
        switch ($this->msgMean->parameter->type) {
            case Example::MOVIE:
//                $movie = model('movie')->where($this->msgMean->parameter->index, $this->msgMean->index)->get();
//                if ($this->msgMean->action && $this->msgMean->action->index == 'get') {
//                    $magnet = model()->where;
//                }
//                $this->result = $movie;
                break;
            case Example::USER:

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
        $this->bind()->process();
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        if ($this->status == self::END) {
            return $this->result;
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
