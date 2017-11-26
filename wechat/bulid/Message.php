<?php

namespace Wechat\bulid;

use function Wechat\common\config;
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
        return $this->message->MsgType;
    }

    public function getMessageFromUserID()
    {
        return $this->message->FromUserName;
    }

    public function getMessageContent()
    {
        $msgType = $this->getMessageType();
        switch ($msgType) {
            case self::MSG_TYPE_TEXT:
                $msgContent = [
                    'type' => $this->getMessageType(),
                    'content' => $this->message->Content];
                break;
            case self::MSG_TYPE_VOICE:
                $msgContent = [
                    'type' => $this->getMessageType(),
                    'content' => $this->message->Recognition];
                break;
            case self::MSG_TYPE_LINK:
                $msgContent = [
                    'type' => $this->getMessageType(),
                    'content' => $this->message->Url];
                break;
            default:
                $msgContent = [
                    'error' => 'error',
                    'type' => $this->getMessageType(),
                    'content' => '暂不支持的消息'];
                break;
        }

        return $msgContent;
    }


    public function setMessageContent($content = null, $option = self::MSG_TYPE_TEXT, $userName = null)
    {
        if (!$content) {
            $this->msgContent = false;
        }
        $xml = '<xml>';
        $xml .= '<ToUserName><![CDATA[' . ($userName == null ? $this->getMessageFromUserID() : $userName) . ']]></ToUserName>';
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
                $xml .= '<ArticleCount>' . $content[0] . '</ArticleCount><Articles>';
                for ($i = 1; $i <= $content[0]; $i++) {
                    $xml .= '<item><Title><![CDATA[' . $content[$i][0] . ']]></Title>';
                    $xml .= '<Description><![CDATA[' . $content[$i][1] . ']]></Description>';
                    $xml .= '<PicUrl><![CDATA[' . $content[$i][2] . ']]></PicUrl>';
                    $xml .= '<Url><![CDATA[' . $content[$i][3] . ']]></Url></item>';
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
//            file_put_contents('1.xml',$this->msgContent);
        }
    }

}