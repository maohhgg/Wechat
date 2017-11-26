<?php

namespace Wechat;

use function Wechat\common\config;


class Wechat extends Error
{
    protected $message;
    protected $accessToken = null;

    public function __construct()
    {
        $this->message = $this->parsePostRequestData();
    }


    // 微信服务验证使用
    public function valid()
    {
        if (isset($_GET["echostr"]) && $_GET["signature"] && $_GET["timestamp"] && $_GET["nonce"]) {
            $echoStr = $_GET["echostr"];
            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];

            $token = config('token');
            $tmpArr = array($token, $timestamp, $nonce);
            sort($tmpArr);
            $tmpStr = implode($tmpArr);
            $tmpStr = sha1($tmpStr);
            if ($tmpStr == $signature) {
                echo $echoStr;
            }
        }

    }

    /**
     * @return \SimpleXMLElement
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        $cacheName = md5(config('AppID') . config('AppSecret'));
        $file = APP_PATH .'Cache/'. $cacheName . '.php';

        if (is_file($file) && filemtime($file) + 7100 > time()) {
            $cacheArrayData = include $file;
        } else {
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . config('AppID') . '&secret=' . config('AppSecret');
            $data = $this->curl($url);
            $cacheArrayData = json_decode($data, true);
            //获取失败
            if (isset($cacheArrayData['errcode'])) {
                return false;
            }
            //缓存access_token
            file_put_contents($file, '<?php return ' . var_export($cacheArrayData, true) . ';?>');
        }
        return $this->accessToken = $cacheArrayData['access_token'];
    }


    /**
     * 服务器发送请求
     * $fields 存在为POST 反之为GET
     * @param $url
     * @param array $fields 不是必须
     * @param int $timeout
     * @return string
     */
    public function curl($url, $fields = [], $timeout = 10)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if ($fields) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        }
        $response = curl_exec($ch);

        if ($error = curl_error($ch)) {
            die($error);
        }
        curl_close($ch);

        return $response;
    }


    /**
     * 获取用户发来的请求
     * @return \SimpleXMLElement
     */
    public function parsePostRequestData()
    {
        $postData = file_get_contents('php://input');
        if (!empty($postData)) {
            return simplexml_load_string($postData, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
    }
}