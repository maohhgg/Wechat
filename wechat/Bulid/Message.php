<?php

namespace Wechat\bulid;

use function Wechat\Common\config;
use Wechat\Wechat;

/**
 * Class Message
 * 负责消息处理 和 发送
 * @package wechat\bulid
 */
class Message extends Wechat
{
    protected $msgContent = false;

    #--------------事件消息类型--------
    //关注
    const EVENT_TYPE_SUBSCRIBE = 'subscribe';
    //取消关注
    const EVENT_TYPE_UNSUBSCRIBE = 'unsubscribe';

    #--------------用户发送的消息类型--------
    //消息类型为文本消息
    const MSG_TYPE_TEXT = 'text';
    //图片消息
    const MSG_TYPE_IMAGE = 'image';
    //语音
    const MSG_TYPE_VOICE = 'voice';
    //视频
    const MSG_TYPE_VIDEO = 'video';
    //短视频
    const MSG_TYPE_SHORT_VIDEO = 'shortvideo';
    //地理位置
    const MSG_TYPE_LOCATION = 'location';
    //链接
    const MSG_TYPE_LINK = 'link';
    //发送的图文
    const MSG_TYPE_NEWS = 'news';

    //------------------事件消息判断------------
    //关注
    public function isSubscribeEvent()
    {
        return $this->message->Event == self::EVENT_TYPE_SUBSCRIBE && $this->message->MsgType == 'event';
    }

    //取消关注
    public function isUnSubscribeEvent()
    {
        return $this->message->Event == self::EVENT_TYPE_UNSUBSCRIBE && $this->message->MsgType == 'event';
    }

    //------------------普通消息判断------------
    //文本消息
    public function isTextMsg()
    {
        return $this->message->MsgType == self::MSG_TYPE_TEXT;
    }

    //图片消息
    public function isImageMsg()
    {
        return $this->message->MsgType == self::MSG_TYPE_IMAGE;
    }

    //语音消息
    public function isVoiceMsg()
    {
        return $this->message->MsgType == self::MSG_TYPE_VOICE;
    }

    //视频消息
    public function isVideoMsg()
    {
        return $this->message->MsgType == self::MSG_TYPE_VIDEO;
    }

    //短视频消息
    public function isShortVideoMsg()
    {
        return $this->message->MsgType == self::MSG_TYPE_SHORT_VIDEO;
    }

    //地理位置消息
    public function isLocationMsg()
    {
        return $this->message->MsgType == self::MSG_TYPE_LOCATION;
    }

    //链接消息
    public function isLinkMsg()
    {
        return $this->message->MsgType == self::MSG_TYPE_LINK;
    }

    public function getMessageType()
    {
        return (string)$this->message->MsgType;
    }

    public function getMessageFromUserID()
    {
        return (string)$this->message->FromUserName;
    }

    public function getMessageID()
    {
        if (isset($this->message->MsgId)){
            return (string)$this->message->MsgId;
        }
        return '0';
    }

    public function getMessageCreateTime()
    {
        return (int)$this->message->CreateTime;
    }

    public function getMessageContent()
    {
        $msgType = $this->getMessageType();

        $msgContent = [
            'type' => $msgType,
            'uid' => $this->getMessageFromUserID(),
            'time' => date('Y-m-d H:i:s', $this->getMessageCreateTime()),
            'msg_id' => $this->getMessageID(),
            'origin' => json_encode($this->message)
        ];

        switch ($msgType) {

            case self::MSG_TYPE_TEXT:
                $msgContent['content'] = (string)$this->message->Content;
                break;

            case self::MSG_TYPE_VOICE:
                $msgContent['content'] = (string)$this->message->Recognition;
                break;

            case self::MSG_TYPE_LINK:
                $msgContent['content'] = (string)$this->message->Url;
                break;

            default:
                $msgContent['error'] = 'error';
                $msgContent['content'] = '';
                break;
        }

        return $msgContent;
    }


    public function setMessageContent($needle = null, $user = null)
    {
        $content = null;
        $option = self::MSG_TYPE_TEXT;
        $token = $user ? $user : $this->getMessageFromUserID();

        if (is_string($needle)) {
            $content = $needle;
        }

        if (is_array($needle)) {
            $content = isset($needle[0]) ? $needle[0] : null;
            $option = isset($needle[1]) ? $needle[1] : $option;
            $token = isset($needle[2]) ? $needle[2] : $token;
        }

        if (!$content) {
            $this->msgContent = false;
            return $this;
        }

        $xml = '<xml>';
        $xml .= '<ToUserName><![CDATA[' . $token . ']]></ToUserName>';
        $xml .= '<FromUserName><![CDATA[' . config('originID') . ']]></FromUserName>';
        $xml .= '<CreateTime>' . time() . '</CreateTime>';
        $xml .= '<MsgType><![CDATA[' . $option . ']]></MsgType>';

        switch ($option) {
            case self::MSG_TYPE_TEXT:
                $xml .= '<Content><![CDATA[' . $content . ']]></Content>';
                break;

            case self::MSG_TYPE_VOICE:
            case self::MSG_TYPE_IMAGE:
                $xml .= '<Image><MediaId><![CDATA[' . $content['media_id'] . ']]></MediaId></Image>';
                break;

            case self::MSG_TYPE_VIDEO:
                $xml .= '<Video><MediaId><![CDATA[' . $content['media_id'] . ']]></MediaId>';
                $xml .= '<Title><![CDATA[' . $content['title'] . ']]></Title>';
                $xml .= '<Description><![CDATA[' . $content['description'] . ']]></Description></Video>';
                break;

            case self::MSG_TYPE_NEWS:
                $xml .= '<ArticleCount>' . $content['count'] . '</ArticleCount><Articles>';
                foreach ($content['content'] as $item) {
                    $xml .= '<item><Title><![CDATA[' . $item['title'] . ']]></Title>';
                    $xml .= '<Description><![CDATA[' . $item['description'] . ']]></Description>';
                    $xml .= '<PicUrl><![CDATA[' . $item['picUrl'] . ']]></PicUrl>';
                    $xml .= '<Url><![CDATA[' . $item['url'] . ']]></Url></item>';
                }
                $xml .= '</Articles>';
                break;

            default:
                break;
        }

        $xml .= '</xml>';
        $this->msgContent = $xml;
        return $this;
    }

    public function send()
    {
        if ($this->msgContent) {
            header('Content-type:application/xml');
            echo $this->msgContent;
        }
    }

}