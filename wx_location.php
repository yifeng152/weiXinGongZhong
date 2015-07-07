<?php

require_once('wx_tpl.php');
require_once('wx_database.php');

function GetLocationMessage($postObj)
{
    global $textTpl, $foodTpl;  // wx_tpl.php中定义的变量

    $fromUsername = $postObj->FromUserName;
    $toUsername = $postObj->ToUserName;
    $time = time();
    $latitude = $postObj->Location_X;
    $longitude = $postObj->Location_Y;
    $msgType = "text";

    $getRequestStatus = GetRequestStatusInDataBase($fromUsername);

    if ($getRequestStatus == "0")   //附近美食查询
    {
        $foodUrl = "http://api.map.baidu.com/telematics/v2/local?".
            "location={$longitude},{$latitude}&keyWord=美食&ak=CBUV6HFsaPoRmE6hAO0NIBoU&radius=500";

        $apiStr = file_get_contents($foodUrl);
        $apiObj = simplexml_load_string($apiStr); //xml解析

        for ($i = 1; $i <= 5; $i++) {
            ${"name" . $i . "_obj"} = $apiObj->poiList->point[$i-1]->name;      //店名
            ${"add" . $i . "_obj"} = $apiObj->poiList->point[$i-1]->address;    //地址
            ${"tell" . $i . "_obj"} = $apiObj->poiList->point[$i-1]->telephone;    //电话
            ${"price" . $i . "_obj"} = $apiObj->poiList->point[$i-1]->additionalInfo->price; //价格

            if (${"price" . $i . "_obj"} == "")
            {
                ${"price" . $i . "_obj"} = "不详";
            }
            else
            {
                ${"price" . $i . "_obj"} =  ${"price" . $i . "_obj"}."元";
            }
            ${"title" . $i . "_str"} = "{${"name".$i."_obj"}}\n地址: {${"add".$i."_obj"}}" .
                "\n电话: {${"tell".$i."_obj"}}\n人均消费: {${"price".$i."_obj"}}";
        }
        $resultStr = sprintf($foodTpl, $fromUsername, $toUsername, $time, ${"title1_str"},
           ${"title2_str"}, ${"title3_str"}, ${"title4_str"}, ${"title5_str"});
    }
    else if ($getRequestStatus == "3")   //天气查询
    {
        $weatherUrl = "http://api.map.baidu.com/telematics/v2/weather?".
            "location={$longitude},{$latitude}&ak=CBUV6HFsaPoRmE6hAO0NIBoU";
        $apiStr = file_get_contents($weatherUrl);
        $apiObj = simplexml_load_string($apiStr); //xml解析
        $placeObj = $apiObj->currentCity; // 读取城市

        $contentStr = $placeObj;
        for ($i= 0; $i < 4; $i++) {
            $todayObj = $apiObj->results->result[$i]->date;
            $weatherObj = $apiObj->results->result[$i]->weather;
            $windObj = $apiObj->results->result[$i]->wind;
            $tmpObj = $apiObj->results->result[$i]->temperature;
            $contentStr =  $contentStr."\n{$todayObj}\n天气：{$weatherObj}\n温度：{$tmpObj}\n风力：{$windObj}\n";
        }

        if (strlen($contentStr) <= 6 * strlen("天气，温度，风力")) {
            $contentStr = "对不起，没有找到您所在的地方的天气情况。";
        }
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
    }
    return $resultStr;
}
?>
