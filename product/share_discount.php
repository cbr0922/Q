<?php 

include("../configs.inc.php");
include("global.php");

$app_id     = $INFO['mod.login.fb.app_id'];
$app_secret = $INFO['mod.login.fb.app_secret'];
$my_url     = $INFO['site_url']."/product/share_discount.php";
$home_url   = $INFO['site_url'];

session_start();
$code = $_REQUEST["code"];

if($_REQUEST["error_code"] == 200){
  echo("<script> top.location.href='" . $INFO['site_url'] . "'</script>");
  exit();
}

if(empty($code)) {
  $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
  $dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
    . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
    . $_SESSION['state'] ."&scope=email,user_likes,read_stream";

  echo("<script> top.location.href='" . $dialog_url . "'</script>");
  exit();
}

if( $code === "test" ){
	$content = file_get_contents("http://www.google.com");
	echo $content;
	exit();
}

if($_REQUEST['state'] == $_SESSION['state']) {
  $token_url = "https://graph.facebook.com/oauth/access_token?"
    . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
    . "&client_secret=" . $app_secret . "&code=" . $code;

  $response = file_get_contents($token_url);
  $params = null;
  parse_str($response, $params);

  $graph_url = "https://graph.facebook.com/me?access_token=" 
    . $params['access_token'];

  $user = json_decode(file_get_contents($graph_url));
  //$user->id
  //$user->name
  //$user->first_name
  //$user->last_name
  //$user->link
  //$user->bio
  //$user->gender
  //$user->email
  //$user->timezone
  //$user->locale
  //$user->verified
  //$user->updated_time
  if($user->id==""){
  	echo "發生錯誤";exit;
  }

  //echo json_encode($user)."<br>";

  $graph_url = "https://graph.facebook.com/me/likes?access_token=". $params['access_token']; //295787873869652
  $id = "295787873869652";
  $likes = false;
  $data = json_decode(file_get_contents($graph_url), true);  
  foreach ($data as $key => $value) { 
    //echo "<h2>$key</h2>";
    foreach ($value as $k => $v) { 
      //echo "$k | $v[id]";
      //print_r($v);
      //echo "<br />";
      if($v[id] == $id){
        $likes = true;
        break;
      }        
    }
  }
  if(!$likes){
    echo("<script>alert('請至官方粉絲按讚!')</script>");
    echo("<script> top.location.href='" . $home_url . "'</script>");
    exit;
  } 

  $graph_url = "https://graph.facebook.com/me/feed?access_token=" . $params['access_token']; //1075232009160748_1090981160919166
  $link = "http://smartshop5.ddcs.com.tw/";
  $feed = false;
  $data = json_decode(file_get_contents($graph_url), true);  
  foreach ($data as $key => $value) { 
    foreach ($value as $k => $v) {
      //echo "$k | $v[link]";
      //print_r($v);
      //echo "<br />";
      if($v[link] == $link){
        $feed = true;
        break;
      } 
    }
  }
  if(!$feed){
    echo("<script>alert('請公開分享網站資訊!')</script>");
    echo("<script> top.location.href='" . $home_url . "'</script>");
    exit;
  }
  
  if($likes && $feed){
    $str;
    $Sql      = "select t.money, c.ticketcode, t.use_starttime, t.use_endtime from `ntssi_ticket` as t inner join `ntssi_ticketcode` as c on t.ticketid=c.ticketid where ticketname='FB分享折價券'";
    $Query    = $DB->query($Sql);
    $Num      = $DB->num_rows($Query);
    if ($Num>0){
      $value = $DB->fetch_array($Query);
      $str = "獲取".$value['money']."元商品折價券!".'\n';
      $str .= "使用方法:送出訂單前輸入折價券號碼".$value['ticketcode'].'\n';
      $str .= "使用期限:".$value['use_starttime']."至".$value['use_endtime'].'\n';
      $str .= "注意:折價券僅可使用乙次";
    }  
    echo("<script>alert('".$str."')</script>");     
  }

  echo("<script> top.location.href='" . $home_url . "'</script>");
}
else {
  echo("The state does not match. You may be a victim of CSRF.");
}

?>
