<?php

namespace App\Handle;


use function Wechat\common\config;

class MsgHandle
{
    const ERROR_DEV = 'developer';
    const ERROR_USER = 'user';

    protected $msgValue;
    protected $msgMean;
    protected $result;

    public static function setText($text = '')
    {
        if (!$text) {
            return (new static())->error(config('tip.message.notGetContent'))->getResult();
        }
        return (new static())->textConvert($text);
    }

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
            $values[$i] = Example::getExample($values[$i] ) || $values[$i];
        }
        $this->msgMean = $values;
        return $this;
    }

    protected function bind()
    {

        return $this;
    }

    /**
     * @return $this
     */
    protected function process()
    {

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

}