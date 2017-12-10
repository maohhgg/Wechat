<?php

namespace App\Handle;


use App\Model\Magnet;
use \App\Model\Movie;
use \App\Model\User;
use \Wechat\bulid\Message;
use function Wechat\common\config;
use function Wechat\common\model;

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
    protected $name;
    protected $status;
    private $userToken;

    /**
     * 将发送来的中文语句分解成数组
     * @param $text
     * @return $this
     */
    protected function textConvert($text)
    {
        $values = explode(' ', $text);
        $this->msgValue = $values;
        $length = count($values);

        for ($i = 0; $i < $length; $i++) {

            if (!isset($values[$i - 1]['type']) || $values[$i - 1]['type'] != Example::TYPE_OPTION) {
                $temp = Example::getExample($values[$i]);
                $values[$i] = (isset($temp['model']) && $temp['model'] == Example::MODEL_USER && $length > 1) ? $values[$i] : $temp;
            }
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
                        $temp = ['is_movie', (int)($item['index'] === Example::IS_MOVIE)];
                        $i = 1;
                        $string = [];
                        while (isset($this->msgMean[$key + $i]) && is_string($this->msgMean[$key + $i])) {
                            array_push($string, $this->msgMean[$key + $i]);
                            $i++;
                        }
                        $string = trim(implode(' ', $string));
                        var_dump($string);
                        array_push($array[$item['model']], ['chinese_name', $string]);
                        $this->name = $string;
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
     * 根据处理后的数组去数据库查询
     * @return $this
     */
    protected function process()
    {
        // 结果初始化
        $this->result = [
            'type' => Message::MSG_TYPE_TEXT,
            'content' => sprintf(config('tip.message.error'), implode(' ', $this->msgValue))
        ];


        if (isset($this->msgMean[Example::MODEL_MOVIE])) {
            $this->movieFunc($this->msgMean[Example::MODEL_MOVIE]);
        }

        if (isset($this->msgMean[Example::MODEL_MAGNET])) {

            if (isset($this->result['content']['count'])) {
                // 当 下载关键字 出现在 电影关键字后
                $this->result['type'] = Message::MSG_TYPE_TEXT;
                $this->magnetFunc();
            } else {
                // 当 用户只发来了 ‘下载’ 关键字

                $action = User::select('last_at')->where('token', $this->userToken)->first();
                $action = json_decode($action->last_at, true);

                if ($action) {
                    if ($action['func'] == Example::MODEL_MOVIE) {
                        $this->movieFunc($action['content']);
                    }
                    $id = isset($this->result['content']['content'][0]) ? $this->result['content']['content'][0]['id'] : null;
                } else {
                    $id = $action['content'];
                }

                $this->result['type'] = Message::MSG_TYPE_TEXT;   // 上面的movieFunc()方法重置了
                if (is_numeric($id)) {
                    $this->magnetFunc($id);
                } else {
                    $this->result['content'] = config('tip.movie.noContent');
                }
            }
        }

        if (isset($this->msgMean[Example::MODEL_USER])) {
            $this->userFunc($this->msgMean[Example::MODEL_USER][0][0]);
        }

        if (isset($this->msgMean[Example::MODEL_PAGINATION])) {
            $action = User::select('last_at')->where('token', $this->userToken)->first();
            $action = json_decode($action->last_at, true);
            $limit = config('page.number');

            if ($action) {
                $page = ($this->msgMean[Example::MODEL_PAGINATION][0][0] == 'nextPage') ? $action['page'] + 1 : $action['page'] - 1;

                if ($page <= 0) {
                    $this->result['content'] = config('tip.message.pageFirst');
                } else if (ceil($action['total'] / $limit) < $page) {
                    $this->result['content'] = config('tip.message.pageEnd');
                } else {
                    switch ($action['func']) {
                        case Example::MODEL_MOVIE:
                            $this->movieFunc($action['content'], $page);
                            break;
                        case Example::MODEL_MAGNET:
                            $this->magnetFunc($action['content'], $page);
                            break;
                    }
                }

            } else {
                $this->result['content'] = config('tip.movie.noContent');
            }
        }

        return $this;
    }

    /**
     * 根据$data中的参数查询电影信息
     * @param $data
     */
    protected function movieFunc($data, $page = 1)
    {
        $order = ($this->name) ? ['chinese_name', 'asc'] : ['id', 'desc'];
        $offset = ($page - 1) * config('page.number');
        $result = (new Process(Movie::class))->run(
            $data,
            Movie::$bindArrayParam,
            $order,
            $offset
        );

        if ($result) {
            $this->result['type'] = Message::MSG_TYPE_NEWS;
            $this->result['content'] = $result;
            $this->status = [
                'func' => Example::MODEL_MOVIE,
                'content' => $data,
                'page' => $page,
                'total' => $result['total']
            ];
        } else {
            $this->result['content'] = $this->getChinese($data);
        }

    }

    /**
     * 查询磁力链接
     * $id 存在查询根据id查询
     * $id 不存在既是 之前已经执行 movieFun 方法 $this->result中已经有结果
     * @param null $id
     */
    protected function magnetFunc($id = null, $page = 1)
    {
        if (!$id) {
            $movie = $this->result['content'];
            // 有多项是 查找是否有电影名和用户发来的数据一样
            foreach ($movie['content'] as $value) {
                if ($value['title'] == $this->name) {
                    $id = $value['id'];
                }
            }
        }

        $offset = ($page - 1) * config('page.number');

        if ($id) {
            $data = [0 => ['movie_id', $id]];

            $result = (new Process(Magnet::class))->run(
                $data,
                Magnet::$bindArrayParam,
                null,
                $offset
            );

            if ($result) {
                $this->status = [
                    'func' => Example::MODEL_MAGNET,
                    'content' => $id,
                    'page' => $page,
                    'total' => $result['total']
                ];

                $msgContent = sprintf(
                    config('tip.magnet.text'),
                    $result['total'],
                    $offset + 1,
                    $offset + $result['count'] ,
                    $result['total'] > $result['count'] ? config('tip.magnet.more') : "",
                    implode('', $result['content']));

            } else {
                $msgContent = sprintf(config('tip.magnet.noContent'), $this->name);
            }

        } else {
            $msgContent = sprintf(config('tip.magnet.dataMore'), $movie['count']);
        }
        $this->result['content'] = $msgContent;
    }

    protected function userFunc($data)
    {
        $result = (new Process(User::class))->run($data);
        if ($result) $this->result['content'] = $result;
    }


    /**
     * @param string $text
     * @return $this
     */
    public
    static function setText($text = '')
    {
        if (!$text) {
            return (new static())->error(config('tip.message.notGetContent'))->getResult();
        }
        return (new static())->textConvert($text);
    }

    public function setUser($user)
    {
        $this->userToken = $user;
    }

    /**
     * @return $this
     */
    public
    function analyst()
    {
        return $this->bind()->process()->getResult();
    }

    /**
     * @return mixed
     */
    protected
    function getResult()
    {
        return $this->result;
    }


    /**
     * @param string $tip
     * @param string $sendTo
     * @return $this
     */
    protected
    function error($tip = '')
    {
        $this->result = $tip;
        return $this;
    }

    /**
     * 运行中的错误 使用log保存下来
     * @return mixed
     */
    public
    function logError()
    {

    }

    /**
     * @return mixed
     */
    public
    function getStatus()
    {
        return $this->status;
    }

    /**
     * 返回可读的语句
     * @param $data
     * @return null|string
     */
    private
    function getChinese($data)
    {
        $array = [];
        $string = '';
        foreach ($data as $value) {
            $array[$value[0]] = $value[1];
        }
        var_dump($array);
        $string .= config('tip.movie.noSearch.init');
//        没有找到 名叫XXX的 XXX导演 XXX主演 xxx编剧的 中国 喜剧 电影

        $string .= isset($array['actor']) ? sprintf(config('tip.movie.noSearch.actor'), $array['actor']) : "";
        $string .= isset($array['director']) ? sprintf(config('tip.movie.noSearch.director'), $array['director']) : "";
        $string .= isset($array['screenwriter']) ? sprintf(config('tip.movie.noSearch.screenwriter'), $array['screenwriter']) : "";
        $string .= !empty($array['chinese_name']) ? sprintf(config('tip.movie.noSearch.chinese_name'), $array['chinese_name']) : "";
        $string .= isset($array['local']) ? $array['local'] : "";
        $string .= isset($array['type']) ? $array['type'] : "";
        $string .= isset($array['is_movie']) ? config('tip.movie.noSearch.or')[$array['is_movie']] : "";
        $string .= config('tip.movie.noSearch.tail');
        return $string;
    }


}
