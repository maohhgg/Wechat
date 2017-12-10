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
    const MODEL_PAGINATION = 'pagination';
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
        'help' => [['/帮助', '/help'], self::MODEL_USER],
        'getHistory' => [['/历史记录', '/history', '历史记录'], self::MODEL_USER],
        'messageHistory' => [['/对话记录', '/message', '对话记录'], self::MODEL_USER],
        'clearGetHistory' => ['/清空获取记录', self::MODEL_USER],
        'clearMessageHistory' => ['/清空对话记录', self::MODEL_USER],
        'bug' => ['bug', self::MODEL_USER],

        'nextPage' => ['下一页', self::MODEL_PAGINATION],
        'prevPage' => ['上一页', self::MODEL_PAGINATION],

        'movie_id' => [['获取', '下载', "磁力", "磁力链接"], self::MODEL_MAGNET],

        'movie' => ['电影', self::MODEL_MOVIE, self::TYPE_OPTION,],
        'tv' => ['电视剧', self::MODEL_MOVIE, self::TYPE_OPTION],
        'douban' => [['豆瓣', '豆瓣号'], self::MODEL_MOVIE, self::TYPE_INDEX],
        'actor' => ['主演', self::MODEL_MOVIE, self::TYPE_INDEX],
        'director' => ['导演', self::MODEL_MOVIE, self::TYPE_INDEX],
        'screenwriter' => ['编剧', self::MODEL_MOVIE, self::TYPE_INDEX],

        'local' => [
            ['美国','英国','法国','日本','中国大陆','香港','德国','印度','加拿大','意大利','韩国','澳大利亚','西班牙','台湾','瑞典','比利时','荷兰','丹麦','俄罗斯','泰国'],
            self::MODEL_MOVIE,
            self::TYPE_VALUE],
        'type' => [
            ['剧情','喜剧','真人秀','惊悚','动作','爱情','犯罪','恐怖','冒险','悬疑','科幻','家庭','奇幻','动画','战争','历史','传记','音乐','歌舞','运动','西部','纪录片'],
            self::MODEL_MOVIE,
            self::TYPE_VALUE ],
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
                    'type' => isset($item[2]) ? $item[2] : null,
                    'param' => $string,
                ];
            }
        }
        return $string;
    }
}