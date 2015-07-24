<?php

$promptList = "请输入对应的序号:\n".
    "1. 暑期校园信息\n".
    "2. 机器人聊天\n".
    "3. 天气查询\n".
    "4. 记事本\n".
    "5. 笑话大全\n".
    "6. 建议意见\n".
    "发送您的‘位置’可以直接获取附近美食\n\n".
    "小助手：1-6是直接回复数字哦~\n".
    "发送‘位置’是类似发送图片，发送红包的那个发送位置，在对话框的“+”号里面哦";

$schoolInfoList = "请输入对应的选项:\n".
                "11. 校车时刻查询\n".
                "12. 游泳时刻查询\n".
                "13. 食堂开放情况查询\n".
                "14. 图书馆开放情况查询\n".
                "15. 大门开放时间查询\n".
                "  0. 返回主界面";

$robotInfoList = "现在您可以和小南聊天了（输入“0”返回主界面）";

$weatherInfoList = "请输入您要查询的城市或者您当前所在的\"位置\"（输入“0”返回主界面）";

$tips = "\n小贴士:\n1、仅输入“0”返回主界面\n2、仅输入“查看”浏览记事本\n3、输入“删除+序号”如“删除1”删除序号1，“删除1 3”同时删除序号1，3的信息";
$noteInfoList = "现在已进入记事本模式，您输入的内容将会被记录到记事本中".$tips;//wq 2015-07-06

$jokeInfoList = "现在已进入笑话模式，发送任意信息即可收到一个笑话（输入“0”返回主界面）";

$suggestionInfoList = "欢迎您提出宝贵意见，分享您找到的bug（输入“0”返回主界面）";

$textTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                <FuncFlag>0</FuncFlag>
            </xml>";

$imageTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Image>
                <MediaId><![CDATA[%s]]></MediaId>
                </Image>
            </xml>";

$musicTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Music>
                <Title><![CDATA[梁祝]]></Title>
                <Description><![CDATA[交响乐]]></Description>
                <MusicUrl><![CDATA[www.baidu.com]]></MusicUrl>
                <HQMusicUrl><![CDATA[www.baidu.com]]></HQMusicUrl>
                </Music>
                <FuncFlag>0</FuncFlag>
            </xml>";

$newsTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[news]]></MsgType>
                <ArticleCount>1</ArticleCount>
                <Articles>
                <item>
                <Title><![CDATA[导航]]></Title>
                <Description><![CDATA[点击后导航到我的实验室]]></Description>
                <PicUrl><![CDATA[]]></PicUrl>
                <Url><![CDATA[%s]]></Url>
                </item>
                </Articles>
                <FuncFlag>0</FuncFlag>
            </xml>";

$foodTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[news]]></MsgType>
                <ArticleCount>6</ArticleCount>
                <Articles>
                <item>
                <Title><![CDATA[您周边的一些美食店如下]]></Title>
                <Description><![CDATA[]]></Description>
                <PicUrl><![CDATA[http://yifeng152-deutsch.stor.sinaapp.com/food.jpg]]></PicUrl>
                <Url><![CDATA[]]></Url>
                </item>
                <item>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[]]></Description>
                <PicUrl><![CDATA[http://yifeng152-deutsch.stor.sinaapp.com/food.jpg]]></PicUrl>
                <Url><![CDATA[]]></Url>
                </item>
                <item>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[]]></Description>
                <PicUrl><![CDATA[http://yifeng152-deutsch.stor.sinaapp.com/food.jpg]]></PicUrl>
                <Url><![CDATA[]]></Url>
                </item>
                <item>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[]]></Description>
                <PicUrl><![CDATA[http://yifeng152-deutsch.stor.sinaapp.com/food.jpg]]></PicUrl>
                <Url><![CDATA[]]></Url>
                </item>
                <item>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[]]></Description>
                <PicUrl><![CDATA[http://yifeng152-deutsch.stor.sinaapp.com/food.jpg]]></PicUrl>
                <Url><![CDATA[]]></Url>
                </item>
                <item>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[]]></Description>
                <PicUrl><![CDATA[http://yifeng152-deutsch.stor.sinaapp.com/food.jpg]]></PicUrl>
                <Url><![CDATA[]]></Url>
                </item>
                </Articles>
                <FuncFlag>0</FuncFlag>
            </xml>";

$busRouteList =
    "将军路校区 开往 明故宫校区\n".
    "           [行车路线]\n".
    "将军路东区 -> 将军路西区 -> 明故宫校区\n".
    "7月18日 ~ 7月31日\n".
    "07:25(1辆)  08:05(1辆)\n".
    "12:00(1辆)  17:10(1辆)\n".
    "18:15(1辆)\n\n".

    "8月1日 ~ 8月21日\n".
    "07:25(1辆)  17:10(1辆)\n".
    "18:15(1辆)\n\n".

    "8月22日 ~ 8月29日\n".
    "07:25(2辆)  08:05(1辆)\n".
    "09:10(1辆)  12:00(1辆)\n".
    "17:10(2辆)  18:15(1辆)\n\n".

    "明故宫校区 开往 将军路校区\n".
    "           [行车路线]\n".
    "明故宫校区 -> 将军路西区 -> 将军路东区\n".
    "7月18日 ~ 7月31日\n".
    "06:55(1辆)  07:10(1辆)\n".
    "08:20(1辆)  09:10(1辆)\n".
    "17:10(1辆)\n\n".

    "8月1日 ~ 8月21日\n".
    "06:55(1辆)  08:20(1辆)\n".
    "17:10(1辆)\n\n".

    "8月22日 ~ 8月29日\n".
    "06:55(1辆)  07:10(1辆)\n".
    "08:20(2辆)  09:10(1辆)\n".
    "13:20(1辆)  17:10(2辆)\n\n".

    "注：8月30日起恢复正常班车\n";

$schoolGateList = "将军路校区暑假期间大门开放时间\n".
    "1.大西门开放时间： 每天07:00—19:00；\n".
    "2.小西门关闭时间： 7月9日—8月25日；\n".
    "其余时间开放，开放时段：06:30—22:30；\n".
    "3.西区北门开放时间：每天，00:00—24:00；\n".
    "4.东区北门关闭时间：7月9日—8月25日；\n".
    "其余时间开放，开放时段：06:30—22:30；\n".
    "5.东区东门开放时间：每天，07:00—21:00";

$diningHallList = "将军路校区各食堂暑期安排\n".
    "学生三食堂：属于西区正常上班食堂\n".
    "学生四食堂：属于东区正常上班食堂。\n".
    "学生一食堂放假时间：7月5日-8月27日（7月4日晚餐后停，29日开早餐）。\n".
    "学子缘餐厅放假时间：7月5日-8月28日（7月4日中餐后停，30号开中餐）。\n".
    "东清食堂放假时间：7月5日-8月28日（7月4日中餐后停，30号开中餐）。\n".
    "学生五食堂放假时间：7月5日-8月28日（7月4日晚餐后停，30号开早餐）。\n".
    "翠屏苑放假时间：7月5日-8月28日（7月4日晚餐后停，30号开中餐）。\n".
    "暑期开餐时间：\n".
    "早餐：7:00-9:00\n".
    "中餐：11:00-12:30\n".
    "晚餐：5:00-7:30\n";


$libraryList = "将军路图书馆暑期开放时间表\n".
                "开放部门：借还书处、各阅览室\n".
                "开放时间：7月21～8月28日（每周二、周五）上午：9:00 - 12:00";

$swimmingList ="将军路游泳馆暑期开放时间表\n".
    "1. 暑假期间游泳馆只对校内师生员工开放；\n".
    "2. 开放时间: 7月4日~8月30日 每天下午4点~晚8点；\n".
    "3. 游泳卡办卡及充值时间:每天下午4-6点，周六周日休息；\n".
    "4. 办卡充值地点: 游泳馆辅助2号口。";

?>