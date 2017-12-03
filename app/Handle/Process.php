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

    public function run($data, $param = [])
    {
        switch ($data['param']['type']){
            case Example::FUNC:
                $this->result = $this->model->help();
                break;
            case Example::OPTION:
                list($column, $operator, $value) = $this->getWhere($data['param']['index'], $data['index']);

                $this->result = ($this->model)->select($param)
                    ->where($column, $operator, $value)
                    ->limit(config('page.number'))->get()->toArray();//->toSql();
                $this->result = $this->getNews($this->result);
                break;
            default:
                break;
        }
        return $this->result;
    }

    protected function getWhere($where, $data)
    {
        if (preg_match('/^\d+$/i', $data)) {
            return [$where, '=', $data];
        } else {
            return [$where, 'LIKE', '%' . $data . '%'];
        }
    }

    protected function getNews($result)
    {
        $news = ['count' => 0, 'content' => []];
        if ($result) {
            if (isset($result[0]) && is_array($result[0])) {
                $news['count'] = count($result);
                foreach ($result as $item) {
                    array_push($news['content'], $this->model->bindArray($item));
                }
            } else {
                $news['count'] = 1;
                array_push($news['content'], $this->model->bindArray($result));
            }
        }
        return $news;
    }

}