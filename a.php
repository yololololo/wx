<?php
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
/*$sql1="insert into wx_media(type,mediaID,ctime) values(?,?,?)";
       $sql2="update wx_media set type=?,mediaID=?,ctime=? where id='{$row['id']}'";
       $token= getToken();
    $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$token}&type={$type}";
     $data=array('myfile'=>'@/var/www/html/wx/upload/0.jpg');*/
//获取临时media_id
  //获取临时media_id
  function  getMediaID($type,$file){  
      $token=getToken();
      $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$token}&type={$type}";
      $postData=array('myfile'=>"@$file");
      $data=curlRequest($url,$postData);
      $data=array_values($data);
      return $data[1];
     
  }

 show(getMediaID('thumb','/var/www/html/wx/upload/0.jpg'));

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

