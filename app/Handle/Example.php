<?php

namespace App\Handle;


class Example
{
    const MOVIE = 'movie';
    const USER = 'user';

    const FUNC = 'function';
    const OPTION = 'option';

    /**
     * TODO 1. 这个保存到配置文件
     * TODO 2. 以后这个改为Model 数据存到数据中
     * @var array
     */
    protected static $example = [
        'help' => [['/帮助', '/help', '帮助'], self::USER, self::FUNC],
        'getHistory' => [['/历史记录', '/history', '历史记录'], self::USER, self::FUNC],
        'messageHistory' => [['/对话记录', '/message', '对话记录'], self::USER, self::FUNC],
        'clearGetHistory' => ['/清空获取记录', self::USER, self::FUNC],
        'clearMessageHistory' => ['/清空对话记录', self::USER, self::FUNC],


        'getMagnet' => [['获取', '下载'], self::MOVIE, self::FUNC],

        'chinese_name' => ['电影', self::MOVIE, self::OPTION],
        'tv' => ['电视剧', self::MOVIE, self::OPTION],
        'douban' => [['豆瓣', '豆瓣号'], self::MOVIE, self::OPTION],
        'actor' => ['主演', self::MOVIE, self::OPTION],
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
            if ($item[0] == $string || (is_array($item[0]) && (array_search($string,$item[0]) > -1) ) ) {
                return [
                    'model' => $item[1],
                    'index' => $key,
                    'type' => $item[2],
                    'other' => $string];
            }
        }
        return [
            'model' => self::USER,
            'index' => 'error',
            'type' => self::FUNC,
            'other' => $string];
    }
}