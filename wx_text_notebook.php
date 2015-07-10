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

    $counter =0;
    for ( $counter = 0; $counter < count($xmlObj->data); $counter += 1) {
////添加一个新的元素
        $newData = $doc->createElement("data");

        $index = $doc->createElement("index");
        $index->appendChild($doc->createTextNode($counter+1));
        $newData->appendChild($index);

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

    $index = $doc->createElement("index");
    $index->appendChild($doc->createTextNode($counter+1));
    $newData->appendChild($index);

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



    UpdateNoteBookInDatabase($str4XML,$user_id);

    return $counter+1;

}

function PreviewLatestMessage($keyword,$inputTimeValue,$latestIndex)
{
//date_default_timezone_set('Asia/Shanghai');
//echo "<Br>time is ";
//echo date("Y-m-d H:i:s", $timeValue) ;
    global $tips;
    $noteBookMessageTpl = "序号".$latestIndex." "."时间:".date("Y-m-d H:i:s", $inputTimeValue)."\n"."记事本:".$keyword.$tips;
    return $noteBookMessageTpl;
}

function ReviewLatestMessage($fromUsername)//回顾以前的信息
{
    global $tips;
    $rs =  GetQureyResultFromDatabase($fromUsername);
    $str4NoteBook  = $rs['NoteBook'];
    $user_id  = $rs['ID'];

    $xmlObj = simplexml_load_string($str4NoteBook);

    $messageReview = "";
    for ( $counter = 0; $counter < count($xmlObj->data); $counter += 1) {
////添加一个新的元素

        $messageReview = "序号".$xmlObj->data[$counter]->index." "."时间:".date("Y-m-d H:i:s",intval($xmlObj->data[$counter]->time))."\n"."记事本:".$xmlObj->data[$counter]->note."\n".$messageReview;
    }

    $messageReview=$messageReview."\n***********\n现在已进入记事本模式，您输入的内容将会被记录到记事本中".$tips;
    return $messageReview;
}
function DeleteMessage($fromUsername,$arr)//回顾以前的信息
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

    $index4NoteBook=1;
    $counter =0;
    $messageReview = "";
    for ( ; $counter < count($xmlObj->data); $counter += 1) {
////添加一个新的元素

        $skipEnabled = 0;
        for ( $i = 0; $i < count($arr);$i += 1) {
            if($arr[$i] == ($counter+1) ){
                $skipEnabled = 1;
                break;
            }
        }
        if($skipEnabled == 1){
            $messageReview = "\n序号".$xmlObj->data[$counter]->index." "."时间:".date("Y-m-d H:i:s",intval($xmlObj->data[$counter]->time))."\n"."记事本:".$xmlObj->data[$counter]->note.$messageReview;

        }
        else{
            $newData = $doc->createElement("data");

            $index = $doc->createElement("index");
            $index->appendChild($doc->createTextNode($index4NoteBook));
            $index4NoteBook = $index4NoteBook+1;
            $newData->appendChild($index);

            $time = $doc->createElement("time");
            $time->appendChild($doc->createTextNode($xmlObj->data[$counter]->time));
            $newData->appendChild($time);

            $note = $doc->createElement("note");
            $note->appendChild($doc->createTextNode($xmlObj->data[$counter]->note));
            $newData->appendChild($note);

            $record->appendChild($newData);
        }



    }

    $str4XML = $doc->saveXML();
    UpdateNoteBookInDatabase($str4XML,$user_id);

    return $messageReview;

}

#截取utf8编码的多字节字符串
function utf8Substr($str, $from, $len)
{
    return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
        '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
        '$1',$str);
}

function JudgeTextMessage($keyword,$fromUsername)
{
    if($keyword == "查看"){
        $contentStr = "开启查看模式\n".ReviewLatestMessage($fromUsername);

    }
    else if(utf8Substr($keyword,0,2) == "删除"){
        $leftKeyWord = substr($keyword , 6 , strlen($keyword));#去除前面“删除”二字的KeyWord

        $arr = explode(' ',$leftKeyWord);

        $contentStr = "开启删除模式\n"."下列信息已经被删除:".DeleteMessage($fromUsername,$arr);
    }
    else{
        $inputTimeValue = time();
        $latestIndex = RecordTextMessage($keyword,$fromUsername,$inputTimeValue);
        $contentStr = "您的消息已经记录\n".PreviewLatestMessage($keyword,$inputTimeValue,$latestIndex);
//        $contentStr = "您的消息已经记录";



    }


    return $contentStr;

}



?>