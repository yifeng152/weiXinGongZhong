<?php
/**
 * wechat php test
 */
require_once('wx_event.php');
require_once('wx_text.php');
require_once('wx_location.php');

//define your token
define("TOKEN", "jgxq");
//$wechatObj->valid();
$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();

function RecordInputContent($postObj)
{
    $fromUsername = $postObj->FromUserName;
    $type = $postObj->MsgType;

    $hostname = SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT;
    $dbuser = SAE_MYSQL_USER;
    $dbpass = SAE_MYSQL_PASS;
    $dbname = SAE_MYSQL_DB;
    $link = mysql_connect($hostname, $dbuser, $dbpass);
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }
    mysql_select_db($dbname, $link) or die ('Can\'t use dbname : ' . mysql_error());

    $sql = "SELECT COUNT( * ) FROM  {$dbname}.`UserContent`";
    $query = mysql_query($sql);
    $rs = mysql_fetch_array($query);
    $userNum = $rs['COUNT( * )'];
    $latestTime = date('y-m-d H:i:s',time());
    $content = trim($postObj->Content);

    switch ($type)
    {
        case "location":
            $infoType = "location";
            $userContent = "no content";
            break;
        case "text":
            $infoType = "text";
            $userContent = $content;
            break;
        case "voice":
            $infoType = "voice";
            $userContent = "no content";
            break;
        case "video":
            $infoType = "video";
            $userContent = "no content";
            break;
        case "shortvideo":
            $infoType = "shortvideo";
            $userContent = "no content";
            break;
        case "image":
            $infoType = "image";
            $userContent = "no content";
            break;
        case "link":
            $infoType = "link";
            $userContent = "no content";
            break;
        default:
            $infoType = "others";
            $userContent = "no content";
            break;
    }
    $sql = "INSERT INTO {$dbname}.`UserContent` (`Index`, `Time`, `User`, `Content`, `InfoType`) ".
        "VALUES ($userNum + 1, '{$latestTime}', '{$fromUsername}','{$userContent}', '{$infoType}')";
    mysql_query($sql);
}

function RefleshUserLoginTime($postObj)
{
    $hostname = SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT;
    $dbuser = SAE_MYSQL_USER;
    $dbpass = SAE_MYSQL_PASS;
    $dbname = SAE_MYSQL_DB;
    $link = mysql_connect($hostname, $dbuser, $dbpass);
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }
    mysql_select_db($dbname, $link) or die ('Can\'t use dbname : ' . mysql_error());

    $sql = "SELECT COUNT( * ) FROM  {$dbname}.`CRM`";
    $query = mysql_query($sql);
    $rs = mysql_fetch_array($query);
    $fromUsername = $postObj->FromUserName;

    $latestTime = date('y-m-d H:i:s',time());
    $sql = "UPDATE {$dbname}.`CRM` SET `LatestTime` = '{$latestTime}' WHERE `USER`='{$fromUsername}'";
    mysql_query($sql);
}

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

            RecordInputContent($postObj);   //保存用户输入的所有内容
            RefleshUserLoginTime($postObj); // 更新用户登录的最新时间

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

