<?php
//获取access_token
function token(){
  include('getToken.php');
  $getToken=new getToken();
  return $getToken->token();
}
//curl get或post请求
function curlRequest($url,$data=''){
  $ch=curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//不验证证书
  if(!empty($data)){
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  }
  $output=curl_exec($ch);
  curl_close($ch);
  return json_decode($output,true);
}

//测试
/*$url="http://www.luoqiusa.top/wx/b.php";
// $data=array('name'=>'yolo');
$data=array("myfile"=>"@/var/www/html/wx/upload/0.jpg");
$res=curlRequest($url,$data);
print_r($res);*/


/**
 * 查询数据库中是否有token值，没有则添加，有则检查是否过期,过期则更新
 * @return [type] [description]
 */
   function getToken(){
    include('db.php');
    $time=time();
    $res=$pdo->query("select * from wx_token");
    $row=$res->fetch();
    $access_token=$row['access_token'];
    if($row==null || (time()-$row['time'])>7000){
       $sql1="insert into wx_token values(?,?)";
       $sql2="update wx_token set access_token=?,time=? where access_token='{$row['access_token']}'";
       $sql=($row==null)?$sql1:$sql2;
       $res=$pdo->prepare($sql);
       $conf=include('conf.php');
       $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$conf['appid']}&secret={$conf['appsecret']}";
       $data=curlRequest($url);
       $arr=[$data['access_token'],$time];
       $res->execute($arr);
    }
    return $access_token;
   }

   // echo getToken();
   

  //获取临时media_id  
 function  getMediaID($type,$file){  
      $token=getToken();
      $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$token}&type={$type}";
      $postData=array('myfile'=>"@$file");
      $data=curlRequest($url,$postData);
      $data=array_values($data);
      return $data[1];
  }
  
//测试
 // show(getMediaID('thumb','/var/www/html/wx/upload/0.jpg'));
  

//输出变量
function show($var){
  if($var==null){
    echo 'null';
  }elseif(is_array($var) || is_object($var)){
    echo "<pre>";
    print_r($var);
    echo "</pre>";
  }else{
    echo $var;
  }
}
//日志记录
 function _log($logMsg){
        $logMsg='时间：'.date("Y-m-d H:i:s",time())."\n".$logMsg."\n";
        file_put_contents('log.php',$logMsg,FILE_APPEND);
    }
?>
