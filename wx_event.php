<?php
require_once('wx_tpl.php');
require_once('wx_database.php');

function GetEventMessage($postObj)
{
    global $textTpl, $promptList;  // wx_tpl.php中定义的变量

    $fromUsername = $postObj->FromUserName;
    $toUsername = $postObj->ToUserName;
    $customevent = $postObj->Event;
    $time = time();
    $msgType = "text";

    if ($customevent == "subscribe")
    {
        $isNewUserInDataBase = CheckNewUserInDataBase($fromUsername);

        if ($isNewUserInDataBase)   //老用户
        {
            UpdateUserIntoDataBase($fromUsername); //更新老用户在数据库中的信息
            $contentStr = "欢迎您回来!\n".$promptList;
        }
        else // 新用户
        {
            InsertUserIntoDataBase($fromUsername); //将用户信息保存到数据库
            $contentStr = $promptList;
        }
    }
    else
    {
        $contentStr = "";
    }
    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
    return $resultStr;
}
?>