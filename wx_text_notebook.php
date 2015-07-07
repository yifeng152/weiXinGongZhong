<?php

//$fromUsername = "he";//test
//$keyword="NewNode";//test
//$inputTimeValue = time();
////test
//RecordTextMessage($keyword,$fromUsername,$inputTimeValue);



function GetQureyResultFromDatabase($fromUsername)
{

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

    return $rs;
}

function UpdateNoteBookInDatabase($str4XML,$user_id)
{
    $dbname = SAE_MYSQL_DB;
    $sql = "UPDATE  {$dbname}.`CRM` SET  `NoteBook` =  '{$str4XML}' WHERE  `CRM`.`ID` =$user_id";
    mysql_query($sql);
}

function RecordTextMessage($keyword,$fromUsername,$inputTimeValue)
{
    $rs =  GetQureyResultFromDatabase($fromUsername);
    $str4NoteBook  = $rs['NoteBook'];
    $user_id  = $rs['ID'];

    $xmlObj = simplexml_load_string($str4NoteBook);

//    echo "<Br></Br>再重新构建标准XML";
    $doc = new DOMDocument('1.0', 'utf-8');  // 声明版本和编码
    $doc->formatOutput = true;

    $record = $doc->createElement("record");
    $doc->appendChild($record);


    for ( $counter = 0; $counter < count($xmlObj->data); $counter += 1) {
////添加一个新的元素
        $newData = $doc->createElement("data");

        $time = $doc->createElement("time");
        $time->appendChild($doc->createTextNode($xmlObj->data[$counter]->time));
        $newData->appendChild($time);

        $note = $doc->createElement("note");
        $note->appendChild($doc->createTextNode($xmlObj->data[$counter]->note));
        $newData->appendChild($note);

        $record->appendChild($newData);
    }

    ////添加一个新的元素
    $newData = $doc->createElement("data");

    $time = $doc->createElement("time");
    $timeValue = $inputTimeValue;
//date_default_timezone_set('Asia/Shanghai');
//echo "<Br>time is ";
//echo date("Y-m-d H:i:s", $timeValue) ;
    $time->appendChild($doc->createTextNode($timeValue));
    $newData->appendChild($time);

    $note = $doc->createElement("note");
    $noteVar = $keyword;
    $note->appendChild($doc->createTextNode($noteVar));
    $newData->appendChild($note);

    $record->appendChild($newData);
////////////////////
    $str4XML = $doc->saveXML();
    echo $str4XML;


    UpdateNoteBookInDatabase($str4XML,$user_id);

}

function PreviewLatestMessage($keyword,$inputTimeValue)
{
//date_default_timezone_set('Asia/Shanghai');
//echo "<Br>time is ";
//echo date("Y-m-d H:i:s", $timeValue) ;
    $noteBookMessageTpl = "时间:".date("Y-m-d H:i:s", $inputTimeValue)."\n"."记事本:".$keyword;
    return $noteBookMessageTpl;
}

function ReviewLatestMessage($fromUsername)//回顾以前的信息
{
    $rs =  GetQureyResultFromDatabase($fromUsername);
    $str4NoteBook  = $rs['NoteBook'];
    $user_id  = $rs['ID'];

    $xmlObj = simplexml_load_string($str4NoteBook);

    $messageReview = "";
    for ( $counter = 0; $counter < count($xmlObj->data); $counter += 1) {
////添加一个新的元素

        $messageReview = "时间:".date("Y-m-d H:i:s",intval($xmlObj->data[$counter]->time))."\n"."记事本:".$xmlObj->data[$counter]->note."\n".$messageReview;
    }

    $messageReview=$messageReview."\n***********\n现在已进入记事本模式，您输入的内容将会被记录到记事本中\n（仅输入“0”返回主界面;\n仅输入“查看”查看记事本）";
    return $messageReview;
}

function JudgeTextMessage($keyword,$fromUsername)
{
    if($keyword == "查看"){
        $contentStr = "开启查看模式\n".ReviewLatestMessage($fromUsername);


    }
    else{
        $inputTimeValue = time();
        $contentStr = "您的消息已经记录\n".PreviewLatestMessage($keyword,$inputTimeValue);
//        $contentStr = "您的消息已经记录";
        RecordTextMessage($keyword,$fromUsername,$inputTimeValue);


    }


    return $contentStr;

}



?>