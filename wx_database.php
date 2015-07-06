<?php
require_once('wx_tpl.php');
date_default_timezone_set('prc'); // 北京时间

$user_id = 0;

function CheckNewUserInDataBase($fromUsername)
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

    $sql = "SELECT * FROM  {$dbname}.`CRM` WHERE  `USER` =  '{$fromUsername}' LIMIT 0 , 30";
    $query = mysql_query($sql);
    $rs = mysql_fetch_array($query);
    $name = $rs['USER'];

    if ($name == $fromUsername)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function InsertUserIntoDataBase($fromUsername)
{
    // dataBase_添加用户信息
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
    $userNum = $rs['COUNT( * )'];

    $latestTime = date('y-m-d H:i:s',time());
    $sql = "INSERT INTO {$dbname}.`CRM` (`ID`, `RequestStatus`, `USER`, `LatestTime`) ".
        "VALUES ($userNum + 1, '0', '{$fromUsername}','{$latestTime}')";
    mysql_query($sql);
}

function UpdateUserIntoDataBase($fromUsername)
{
    // dataBase_添加用户信息
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
    $userNum = $rs['COUNT( * )'];

    $latestTime = date('y-m-d H:i:s',time());
    $sql = "UPDATE {$dbname}.`CRM` SET `RequestStatus` = '0', `LatestTime` = '{$latestTime}'".
        "WHERE `USER`='{$fromUsername}'";

    mysql_query($sql);
}

function GetRequestStatusInDataBase($fromUsername)
{
    global $user_id;

    // dataBase_添加用户信息
    $hostname = SAE_MYSQL_HOST_M . ':' . SAE_MYSQL_PORT;
    $dbuser = SAE_MYSQL_USER;
    $dbpass = SAE_MYSQL_PASS;
    $dbname = SAE_MYSQL_DB;
    $link = mysql_connect($hostname, $dbuser, $dbpass);
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }
    mysql_select_db($dbname, $link) or die ('Can\'t use dbname : ' . mysql_error());

    $sql = "SELECT * FROM  {$dbname}.`CRM` WHERE  `USER` =  '{$fromUsername}' LIMIT 0 , 30";
    $query = mysql_query($sql);
    $rs = mysql_fetch_array($query);


    $user_id  = $rs['ID'];
    $getRequestStatus = $rs['RequestStatus'];
    return $getRequestStatus;
}

function SetRequestStatusInDataBase($setRequestStatus)
{
    global $user_id;
    $dbname = SAE_MYSQL_DB;
    $sql = "UPDATE  {$dbname}.`CRM` SET  `RequestStatus` = $setRequestStatus  WHERE  `CRM`.`ID` = $user_id;";
    mysql_query($sql);
}

?>