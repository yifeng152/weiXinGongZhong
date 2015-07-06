<?php
/**
 * wechat php test
 */
require_once('wx_event.php');
require_once('wx_text.php');
require_once('wx_location.php');
require_once('wm_record_content.php');

//define your token
define("TOKEN", "jgxq");
//$wechatObj->valid();
$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        global $textTpl;
        if (!empty($postStr))
        {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $type = $postObj->MsgType;

            switch ($type)
            {
            case "event":
                $resultStr = GetEventMessage($postObj);
                break;
            case "location":
                $resultStr = GetLocationMessage($postObj);
                break;
            case "text":
                $resultStr = GetTextMessage($postObj);
                break;
            default:
                $resultStr = GetTextMessage($postObj);
                //$resultStr = GetDefaultMessage($postObj);
                break;
            }

            RecordInputContent($postObj);   //保存用户输入的所有内容
            RefleshUserLoginTime($postObj); // 更新用户登录的最新时间

            echo $resultStr;
        }
        else
        {
            echo "";
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}
?>

