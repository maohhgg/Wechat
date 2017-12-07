<?php

namespace App\Handle;

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
        switch ($data['type']) {
            case Example::TYPE_FUNC:
                $this->result = $this->model->{$data['index']}($data);
                break;
            case Example::TYPE_OPTION:

                $total = ($this->model)
                    ->select($param)
                    ->where(
                        $this->getWhere($data['index'], $data['param'])
                    )->count();
                if ($total > 0) {
                    $this->result = ($this->model)
                        ->select($param)
                        ->where(
                            $this->getWhere($data['index'], $data['param'])
                        )->limit($limit)
                        ->get()
                        ->toArray();
                    var_dump($total);
                    $this->result = $this->getNews($this->result, $total);
                } else {
                    $this->result = null;
                }

                break;
            default:
                break;
        }
        return $this->result;
    }

    protected function getWhere($where, $data)
    {
        $result = [];
        if (is_array($where) && count($where) == count($data)) {
            for ($i = 0; $i < count($where); $i++) {
                array_push($result, $this->getWhereIn($where[$i], $data[$i]));
            }
        } else {
            array_push($result, $this->getWhereIn($where, $data));
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