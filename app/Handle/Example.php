<?php

namespace App\Handle;

use \App\Handle\Bean\Parameter;

class Example
{
    const MOVIE = 'movie';
    const USER = 'user';

    const FUNC = 'function';
    const OPTION = 'option';


    protected static $example = [
        'help' => [['/帮助', '/help', '帮助'], self::USER, self::FUNC],
        'getHistory' => [['/历史记录', '/history', '历史记录'], self::USER, self::FUNC],
        'messageHistory' => [['/对话记录', '/message', '对话记录'], self::USER, self::FUNC],
        'clearGetHistory' => ['/清空获取记录', self::USER, self::FUNC],
        'clearMessageHistory' => ['/清空对话记录', self::USER, self::FUNC],


        'get' => [['获取', '下载'], self::MOVIE, self::FUNC],

        'movie' => ['电影', self::MOVIE, self::OPTION],
        'tv' => ['电视剧', self::MOVIE, self::FUNC],
        'douban' => [['豆瓣', '豆瓣号'], self::MOVIE, self::OPTION],
        'director' => ['导演', self::MOVIE, self::OPTION],
        'screenwriter' => ['主演', self::MOVIE, self::OPTION],
        'actor' => ['编剧', self::MOVIE, self::OPTION],

        'local' => [['大陆', '中国大陆', '中国'], self::MOVIE, self::OPTION],
        'type' => [['喜剧', '动作', '剧情'], self::MOVIE, self::OPTION]
    ];

    /**
     * @return Parameter
     */
    public static function getExample($string = '')
    {
        if(!$string){
            return null;
        }
        foreach (self::$example as $key => $item) {
            if ($item[0] == $string || (is_array($item[0]) && array_search($string, $item[0]))) {
                return new Parameter($item[1], $key, $item[2],$string);
            }
        }
        return new Parameter(self::USER, 'error', self::FUNC,'');
    }
}