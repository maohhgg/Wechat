<?php

namespace App\Handle;

/**
 * 处理中文语句与实际方法
 * Class Example
 * @package App\Handle
 */
class Example
{
    const MODEL_MOVIE = 'movie';
    const MODEL_USER = 'user';
    const MODEL_MAGNET = 'magnet';

    const TYPE_FUNC = 'function';
    const TYPE_OPTION = 'option';

    /**
     * TODO 1. 这个保存到配置文件
     * TODO 2. 以后这个改为Model 数据存到数据中
     * @var array
     */
    protected static $example = [
        'hello' => [['你好', '嗨', '您好', 'hello', 'hi'], self::MODEL_USER, self::TYPE_FUNC],
        'help' => [['/帮助', '/help', '帮助'], self::MODEL_USER, self::TYPE_FUNC],
        'getHistory' => [['/历史记录', '/history', '历史记录'], self::MODEL_USER, self::TYPE_FUNC],
        'messageHistory' => [['/对话记录', '/message', '对话记录'], self::MODEL_USER, self::TYPE_FUNC],
        'clearGetHistory' => ['/清空获取记录', self::MODEL_USER, self::TYPE_FUNC],
        'clearMessageHistory' => ['/清空对话记录', self::MODEL_USER, self::TYPE_FUNC],

        'movie_id' => [['获取', '下载'], self::MODEL_MAGNET, self::TYPE_OPTION],

        'chinese_name' => ['电影', self::MODEL_MOVIE, self::TYPE_OPTION, 10],
        'tv' => ['电视剧', self::MODEL_MOVIE, self::TYPE_OPTION, 10],
        'douban' => [['豆瓣', '豆瓣号'], self::MODEL_MOVIE, self::TYPE_OPTION, 5],
        'actor' => ['主演', self::MODEL_MOVIE, self::TYPE_OPTION, 3],
        'director' => ['导演', self::MODEL_MOVIE, self::TYPE_OPTION, 1],
        'local' => [['中国', '美国', '法国', '香港', '日本', '韩国'], self::MODEL_MOVIE, self::TYPE_OPTION, -1],
    ];

    /**
     * @param string $string
     * @return array
     */
    public static function getExample($string = '')
    {
        if (!$string) {
            return null;
        }
        foreach (self::$example as $key => $item) {
            if ($item[0] == $string || (is_array($item[0]) && (array_search($string, $item[0]) > -1))) {
                $array = [
                    'model' => $item[1],
                    'index' => $key,
                    'type' => $item[2],
                    'param' => $string,
                    'other' => $string,
                ];

                if ($item[3]) $array['level'] = $item[3];
                return $array;
            }
        }
        return [
            'model' => self::MODEL_USER,
            'index' => 'error',
            'type' => self::TYPE_FUNC,
            'other' => $string
        ];
    }
}