<?php
	//文本消息模板
	function textTpl($object,$content){
			 $textTpl="<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[text]]></MsgType>
				<Content><![CDATA[%s]]></Content>
			  </xml>";
			 return $textStr = sprintf($textTpl, $object->FromUserName, 
	                            $object->toUsername, time(),$content);
	
		}

		//图文消息模板
    function newsTpl($object){
        include('db.php');
        include('func.php');
        $itemTpl="<item>
                    <Title><![CDATA[%s]]></Title> 
                    <Description><![CDATA[%s]]></Description>
                    <PicUrl><![CDATA[%s]]></PicUrl>
                    <Url><![CDATA[%s]]></Url>
                  </item>";
        $res=$pdo->query("select * from wx_news order by id desc limit 3");
        if($res==null){
            $errorInfo=$pdo->errorInfo();
            _log($errorInfo[2]);
        }else{
            _log('newsTpl方法成功');
        }
        $rows=$res->fetchAll();
        $itemStr='';
        foreach($rows as $v){
            $itemStr.=sprintf($itemTpl,$v['title'],$v['_desc'],$v['picUrl'],$v['url']);
        }
        $newsTpl="<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>%s</ArticleCount>
                    <Articles>
                    $itemStr
                    </Articles>
                    </xml>";
         return $newsStr = sprintf($newsTpl, $object->FromUserName, 
                                $object->ToUserName, time(),count($rows));

    }


    //图片消息模板
    function imageTpl($object){

             $imageTpl="<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[image]]></MsgType>
                            <Image>
                            <MediaId><![CDATA[%s]]></MediaId>
                            </Image>
                        </xml>";
             include('func.php');
             $file="/var/www/html/wx/upload/0.jpg";
             $mediaId=getMediaID('image',$file);
             return $imageStr = sprintf($imageTpl, $object->FromUserName, 
                                $object->toUsername, time(),$mediaId);
        }
         /*

     //音乐消息模板
    function imageTpl($object){
        include('db.php');
        include('func.php');
          $item=" <Music>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![%s]]></Description>
                        <MusicUrl><![CDATA[%s]]></MusicUrl>
                        <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                        <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
                    </Music>";
        $res=$pdo->query("select * from wx_news order by id desc limit 3");
        if($res==null){
            $errorInfo=$pdo->errorInfo();
            _log($errorInfo[2]);
        }else{
            _log('newsTpl方法成功');
        }
        $rows=$res->fetchAll();
        $itemStr='';
        foreach($rows as $v){
            $itemStr.=sprintf($itemTpl,$v['title'],$v['_desc'],$v['picUrl'],$v['url']);
        }
        $musicTpl="<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[music]]></MsgType>
                            {$itemStr}
                            </Image>
                        </xml>";
             $file="/var/www/html/wx/upload/0.jpg";
             $mediaId=getMediaID('thumb',$file);
             return $res = sprintf($musicTpl, $object->FromUserName, 
                                $object->toUsername, time(),$mediaId);
            
        }
*/