<?php

namespace App\Handle\Bean;

class Mean
{
    public $parameter;
    public $index;
    public $action;
    public $other;

    public function __construct($parameter = false, $index = '', $action = '', $other = '')
    {
        $this->parameter = $parameter;
        $this->index = $index;
        $this->action = $action;
        $this->other = $other;
    }

    public static function copy($array)
    {
        if (is_array($array) && $array) {
            $selfClass = new static();
            foreach ($array as $key => $value){
                $selfClass->{$key} = $value;
            }
            return $selfClass;
        } else {
            return new static();
        }

    }
}