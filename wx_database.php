<?php
require_once('wx_tpl.php');

$user_id = 0;

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
    $sql = "INSERT INTO {$dbname}.`CRM` (`ID`, `USER`) VALUES (NULL, '{$fromUsername}')";
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