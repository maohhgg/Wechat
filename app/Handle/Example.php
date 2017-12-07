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

    const TYPE_VALUE = 'value';
    const TYPE_INDEX = 'index';
    const TYPE_OPTION = 'option';

    const IS_MOVIE = 'movie';

    /**
     * TODO 1. 这个保存到配置文件
     * TODO 2. 以后这个改为Model 数据存到数据中
     * @var array
     */
    protected static $example = [
        'hello' => [['你好', '嗨', '您好', 'hello', 'hi'], self::MODEL_USER],
        'help' => [['/帮助', '/help', '帮助'], self::MODEL_USER],
        'getHistory' => [['/历史记录', '/history', '历史记录'], self::MODEL_USER],
        'messageHistory' => [['/对话记录', '/message', '对话记录'], self::MODEL_USER],
        'clearGetHistory' => ['/清空获取记录', self::MODEL_USER],
        'clearMessageHistory' => ['/清空对话记录', self::MODEL_USER],

        'movie_id' => [['获取', '下载', '下一页'], self::MODEL_MAGNET],

        'movie' => ['电影', self::MODEL_MOVIE, self::TYPE_OPTION],
        'tv' => ['电视剧', self::MODEL_MOVIE, self::TYPE_OPTION],
        'douban' => [['豆瓣', '豆瓣号'], self::MODEL_MOVIE, self::TYPE_INDEX],
        'actor' => ['主演', self::MODEL_MOVIE, self::TYPE_INDEX],
        'director' => ['导演', self::MODEL_MOVIE, self::TYPE_INDEX],
        'local' => [['中国', '美国', '法国', '香港', '日本', '韩国'], self::MODEL_MOVIE, self::TYPE_VALUE],
        'type' => [['喜剧', '剧情', '悬疑', '犯罪', '歌舞', '科幻', '惊悚'], self::MODEL_MOVIE, self::TYPE_VALUE],
    ];

    /**
     * @param string $string
     * @return array
     */
    public static function getExample($string = '')
    {
        foreach (self::$example as $key => $item) {
            if ($item[0] == $string || (is_array($item[0]) && (array_search($string, $item[0]) > -1))) {
                return [
                    'model' => $item[1],
                    'index' => $key,
                    'type' =>  isset($item[2]) ? $item[2] : null,
                    'param' => $string,
                ];
            }
        }
        return $string;
    }
}