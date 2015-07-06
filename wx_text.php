<?php
require_once('wx_tpl.php');
require_once('wx_database.php');
require_once('wx_text_notebook.php');//wq 2015-07-06


function GetHolidayInfo($keyword)
{
    global $schoolInfoList, $busRouteList, $schoolGateList,
           $diningHallList, $libraryList, $swimmingList;

    switch ($keyword)
    {
        case "11":  //校车时刻查询
            $contentStr = $busRouteList;
            break;
        case "12":  //游泳时刻查询
            $contentStr = $swimmingList;
            break;
        case "13":  //食堂开放情况查询
            $contentStr = $diningHallList;
            break;
        case "14":  //图书馆开放情况查询
            $contentStr = $libraryList;
            break;
        case "15": //大门开放时间查询
            $contentStr = $schoolGateList;
            break;
        default:
            $contentStr = $schoolInfoList;
            break;
    }
    return $contentStr;
}

function GetRobotChatInfo($keyword)
{
    $apiKey = "a6bcb49199af7b805cd976d7c2b5db69";
    $apiURL = "http://www.tuling123.com/openapi/api?key=KEY&info=INFO";

    $reqInfo = $keyword;
    $url = str_replace("INFO", $reqInfo, str_replace("KEY", $apiKey, $apiURL));

    $res =file_get_contents($url);
    $tranSon = json_decode($res);
    $code = $tranSon->code;

    switch ($code)
    {
        case "100000":
            $text = $tranSon->text;
            $contentStr = $text;
            break;
        case "200000":
            $text = $tranSon->text;
            $url = $tranSon->url;
            $contentStr = $text."\n".$url;
            break;
        case "302000":
            $text = $tranSon->text;
            $contentStr = $text."\n";
            $list = $tranSon->list;

            for ($i = 0; $i < count($list); $i++)
            {
                $article = $list[$i]->article;
                $source = $list[$i]->source;
                $icon = $list[$i]->icon;
                $detailurl = $list[$i]->detailurl;

                $contentStr = $contentStr."\n".$article."\n".$detailurl."\n";
            }
            break;
        case "305000":
            $text = $tranSon->text;
            $contentStr = $text."\n";
            $list = $tranSon->list;

            for ($i = 0; $i < (count($list) > 3 ? 3 : count($list)); $i++)
            {
                $trainnum = $list[$i]->trainnum;
                $start = $list[$i]->start;
                $terminal = $list[$i]->terminal;
                $starttime = $list[$i]->starttime;
                $endtime = $list[$i]->endtime;
                $icon = $list[$i]->icon;
                $detailurl = $list[$i]->detailurl;

                $trainnum = "车次: ".$trainnum;
                $start = "出发站: ".$start;
                $terminal = "到达站: ".$terminal;
                $starttime = "出发时间: ".$starttime;
                $endtime = "到达时间: ".$endtime;
                $detailurl = "相关链接: ".$detailurl;

                $contentStr = $contentStr."\n".$trainnum."\n".$start."\n".$terminal."\n".$starttime."\n".$endtime."\n".$detailurl;
            }
            break;
        case "306000":
            $text = $tranSon->text;
            $url = $tranSon->url;
            $contentStr = $text."\n".$url;
            break;
        case "308000":
            $text = $tranSon->text;
            $contentStr = $text."\n";
            $list = $tranSon->list;

            for ($i = 0; $i < (count($list) > 3 ? 3 : count($list)); $i++)
            {
                $name = $list[$i]->name;
                $icon = $list[$i]->icon;
                $info = $list[$i]->info;
                $detailurl = $list[$i]->detailurl;

                $contentStr = $contentStr."\n".$name."\n".$info."\n".$detailurl;
            }
            break;
        default:
            $contentStr = "对不起，小南不会了。。。";
            break;
    }
    return $contentStr;
}

function GetWeatherInfo($keyword)
{
    $weatherUrl = "http://api.map.baidu.com/telematics/v2/weather?" .
        "location={$keyword}&ak=CBUV6HFsaPoRmE6hAO0NIBoU";
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
        $contentStr = "对不起，没有找到您所输入的城市名的天气情况，请确认您所输入的城市名 \"{$keyword}\" 是否有误。";
    }
    return $contentStr;
}

function GetJokeInfo()
{
    $url = "http://apix.sinaapp.com/joke/?appkey=trialuser";
    $output = file_get_contents($url);
    $contentStr = json_decode($output, true);
    return $contentStr;
}


function GetTextMessage($postObj)
{
    global $textTpl, $promptList, $schoolInfoList, $robotInfoList;
    global $weatherInfoList, $noteInfoList, $jokeInfoList;  // wx_tpl.php中定义的变量

    $fromUsername = $postObj->FromUserName;
    // $fromUsernameTest = "he";
    $toUsername = $postObj->ToUserName;
    $keyword = trim($postObj->Content);
    $time = time();
    $msgType = "text";

    $getRequestStatus = GetRequestStatusInDataBase($fromUsername);
    //$contentStr = "linaijun";
    //$contentStr = $getRequestStatus;
    //$getRequestStatus = "0";

    if ($getRequestStatus == "0")
    {
        switch ($keyword)
        {
            case "1": //暑期咨询
                $contentStr = $schoolInfoList;
                $setRequestStatus = '1';
                break;
            case "2"://机器人聊天
                $contentStr = $robotInfoList;
                $setRequestStatus = '2';
                break;
            case "3": //天气查询
                $contentStr = $weatherInfoList;
                $setRequestStatus = '3';
                break;
            case "4": //记事本
                $contentStr = $noteInfoList;
                $setRequestStatus = '4';
                break;
            case "5": //笑话大全
                $contentStr = $jokeInfoList;
                $setRequestStatus = '5';
                break;
            default: // 其他情况
                $setRequestStatus ='0';
                $contentStr = $promptList;
        }
        SetRequestStatusInDataBase($setRequestStatus); // 设置当前用户的状态标识
    }
    else
    {
        if ($keyword == "#")
        {
            $contentStr = $promptList;
            $setRequestStatus = '0';
            SetRequestStatusInDataBase($setRequestStatus); // 设置当前用户的状态标识
        }
        else {
            switch ($getRequestStatus) {
                case "1":
                    $contentStr = GetHolidayInfo($keyword);
                    break;
                case "2"://机器人聊天
                    $contentStr = GetRobotChatInfo($keyword);
                    break;
                case "3": // 天气查询
                    $contentStr = GetWeatherInfo($keyword);
                    break;
                case "4": //记事本
                    $contentStr = JudgeTextMessage($keyword,$fromUsername);
                    break;
                case "5": //笑话大全
                    $contentStr = GetJokeInfo();
                    break;
                default:
                    $contentStr = $promptList;
                    break;
            }
        }
    }

    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
    return $resultStr;
}
?>
