<?php

header('Content-type:text');
define("TOKEN", "bean");

$wechatObj = new wechatCallbackapiTest();
if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
}else{
    $wechatObj->valid();
}

class wechatCallbackapiTest{

     function __construct(){
        include('tpl.php');
    }

    public function valid(){
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    //这个函数就是专门处理业务逻辑
    public function responseMsg(){
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        //extract post data
        //就是说如果我们的请求数据不是为空
        if (!empty($postStr)){
            $object = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($object->MsgType);
            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($object);
                    break;
                case "text":
                    $result = $this->receiveText($object);
                    break;
            }
            echo $result;
        }else {
            echo "";
            exit;
        }
    }

       private function receiveEvent($object)
    {
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
                $content = "hey,you here";
                break;
            case "unsubscribe":
                $content = "取消关注";
                break;
        }
        $result=textTpl($object,$content);
        return $result;
    }

   //接收文本消息
    private function receiveText($object)
    {
        $keyword = trim($object->Content);
        switch ($keyword) {
            case 2://回复文本消息
                // $result=$this->test($object);
                $content="我是文本回复";
                $result=textTpl($object,$content);
                break;

               case 3://回复图文消息
                $result=newsTpl($object);
                break;

                case 4://回复图片
                $result=imageTpl($object);
                break;

            default:
                $result=$this->test($object);
                break;
        }
       

        return $result;
    }


   
    

    function test($object){
        $testTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        </xml>"; 
        //填充模板
       echo  $resultStr = sprintf($testTpl, $object->FromUserName, 
            $object->toUsername, time(),'hey');
    }

          
    private function checkSignature() {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
                
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}
?>