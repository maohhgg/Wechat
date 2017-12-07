<?php

namespace App\Handle;

use App\Model\Magnet;
use App\Model\Movie;
use App\Model\User;

use function Wechat\common\config;

class Process
{
    protected $model = null;
    protected $result = null;
    protected $param = null;

    public function __construct($model)
    {
        $this->model = new $model;
    }

    public function run($data, $param = [], $limit = null)
    {
        $limit = $limit ? $limit : config('page.number');

        switch (get_class($this->model)) {
            case User::class:
                $this->result = $this->model->{$data}($data);
                break;
            case Movie::class:
                $this->get($data, $param, $limit);
                break;
            case Magnet::class:
                $this->get($data, $param, $limit);
                break;
        }
        return $this->result;
    }

    private function get($data, $param, $limit)
    {
        $total = $this->model->select($param)->where($this->getWhere($data))->count();

        if ($total > 0) {
            $this->result = $this->model
                ->select($param)
                ->where($this->getWhere($data))
                ->limit($limit)
                ->get()
                ->toArray();

            $this->result = $this->getNews($this->result, $total);
        } else {
            $this->result = null;
        }
    }

    protected function getWhere($data)
    {
        $result = [];
        if (is_array($data)) {
            for ($i = 0; $i < count($data); $i++) {
                array_push($result, $this->getWhereIn($data[$i][0], $data[$i][1]));
            }
        }
        return $result;
    }

    protected function getWhereIn($where, $data)
    {
        if (preg_match('/^\d+$/i', $data)) {
            return [$where, '=', $data];
        } else {
            return [$where, 'LIKE', '%' . $data . '%'];
        }
    }

    protected function getNews($result, $total = 0)
    {
        $news = ['total' => $total, 'count' => 0, 'content' => []];
        if ($result) {
            if (isset($result[0]) && is_array($result[0])) {
                $news['count'] = count($result);
                foreach ($result as $item) {
                    array_push($news['content'], ($this->model)->bindArray($item));
                }
            } else {
                $news['count'] = 1;
                array_push($news['content'], ($this->model)->bindArray($result));
            }
        }
        return $news;
    }

}