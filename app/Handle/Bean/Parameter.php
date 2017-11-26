<?php

namespace App\Handle\Bean;

class Parameter
{
    public $index;
    public $model;
    public $type;
    public $origin;

    public function __construct($model, $index, $type, $origin = '')
    {
        $this->model = $model;
        $this->index = $index;
        $this->type = $type;
        $this->origin = $origin;
    }
}
