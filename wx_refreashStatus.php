<?php
//由Cron控制，每4个小时清理一下





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

for($iCounter=1;$iCounter<=$userNum;$iCounter+=1) {
    $sql = "SELECT * FROM  {$dbname}.`CRM` WHERE  `ID` =  '{$iCounter}' LIMIT 0 , 30";
    $query = mysql_query($sql);
    $rs = mysql_fetch_array($query);
    $latestTime = $rs['LatestTime'];




    if( abs(time() - strtotime($latestTime))/60/60 > 4){
        $sql = "UPDATE {$dbname}.`CRM` SET `RequestStatus` = '0' WHERE  `ID` =  '{$iCounter}'";
        mysql_query($sql);

    }
    else
    {
        //echo "not need";
    }


   // echo date('y-m-d H:i:s',time());
}

?>
