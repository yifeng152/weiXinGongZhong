<?php
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
?>
