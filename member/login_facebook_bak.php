<?php 

include("../configs.inc.php");
require_once( RootDocument."/Config/conf.global.php" );
include("global.php");

$app_id = "428062713907515";
$app_secret = "d55a87ab61670e8bc7e8ab29cb48320f";
$my_url = $INFO['site_url']."/member/login_facebook.php";
$home_url = $INFO['site_url'];


session_start();
$code = $_REQUEST["code"];

if ( $_GET['optype']=="FB" ){
	$_SESSION['optype']="FB";
	//$_SESSION['opkey']=substr( $_GET['opKey'], 3, strlen($_GET['opKey'])-3 );
	$_SESSION['opkey']=$_GET['opKey'];
}

if ( $_SESSION['optype']=="FB" ){
	$home_url = "http://www.qbeauty.com.tw/shopping/shoppingop.php?key=" . $_SESSION['opkey'];
	setcookie("optype", "FB",time(),"/");
	setcookie("opKey", "FB_" . $MallgicOrderId,time(),"/");
}

if(empty($code)) {
  $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
  $dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
    . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
    . $_SESSION['state'] ."&scope=email";

  echo("<script> top.location.href='" . $dialog_url . "'</script>");
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

  $Query_old = $DB->query("select  facebook_id from `{$INFO[DBPrefix]}user` where facebook_id='".$user->id."' limit 0,1");
  $Num_old   = $DB->num_rows($Query_old);
  if ($Num_old>0){
          //echo "已經有用 facebook 登入過了";
          //$FUNCTIONS->sorry_back('back',$MemberLanguage_Pack[SorryIsHadUserName]); //"對不起，帳號發生重複！請重新選擇輸入帳號！";
          echo ""; // do nothing
  }
  else{

          $db_string = $DB->compile_db_insert_string( array (
      	    'true_name'         => $user->name,
      	    'email'             => $user->email,
      	    'user_level'        => 3,
      	    'vloid'             => 1,
      	    'user_state'        => 0,
      	    'reg_date'          => date("Y-m-d",time()),
      	    'reg_ip'            => $FUNCTIONS->getip(),
      	    'facebook_id'       => $user->id,
      	    )      
          );

          $Sql="INSERT INTO `{$INFO[DBPrefix]}user` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

          $Result_Insert=$DB->query($Sql);
  }

      $Sql  = "select u.user_id,u.true_name,u.username,u.points,u.toppoints,u.user_level,u.vloid,v.volname,l.level_name,u.isold,u.islogin from `{$INFO[DBPrefix]}user` u left join `{$INFO[DBPrefix]}forum_vol` v on ( v.vloid=u.vloid ) left join `{$INFO[DBPrefix]}user_level` l on (u.user_level=l.level_id) where u.facebook_id='".$user->id."' and user_state!=1 limit 0,1";

      $Query = $DB->query($Sql);
      $Num   = $DB->num_rows($Query);
      if ($Num>0){
      	$Result = $DB->fetch_array($Query);
      	$_SESSION['Member_Volid'] = $Rs['vloid'];
      	$_SESSION['user_id']      = $Result['user_id'];
      	$_SESSION['true_name']    = $Result['true_name'];
      	$_SESSION['user_level']   = $Result['user_level'];
      	if (intval($Result['user_level'])!=0){
      		$_SESSION['userlevelname']  = $Result['level_name'];
      	}else{
      		$_SESSION['userlevelname']  = $MemberLanguage_Pack[Member_say];
      	}
      	$_SESSION['YesPass'] = "YesPass";
      	//每日登入送紅利
	/*
      	$d = date('d',time());
      	$y = intval(date('Y',time()));
      	$m = intval(date('m',time()));
      	$overtime = gmmktime(0,0,0,$m,$d,$y);
      	$B_Sql = "select * from `{$INFO[DBPrefix]}bonuspoint` where `type`=8 and user_id='" . intval($Result['user_id']) . "' and addtime>='" . $overtime . "' and addtime<='" . ($overtime+60*60*24) . "'";
      	$B_Query = $DB->query($B_Sql);
      	$B_Num   = $DB->num_rows($B_Query);
      	if ($B_Num<=0)
      		$FUNCTIONS->AddBonuspoint(intval($Result['user_id']),intval($INFO['loginpoint']),8,"會員登入" . trim($Result['username']),1,0);
	*/
      		
      	$updatesql = "update `{$INFO[DBPrefix]}user` set islogin=1 where user_id='" . $Result['user_id'] . "'";
      	$DB->query($updatesql);
      	if($Result['isold']==1 && $Result['islogin']==0){
      		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script>alert('請檢查您的資料');</script>";	
      	}
      }

  $_SESSION['optype']="";
  $_SESSION['opkey']="";
  echo("<script> top.location.href='" . $home_url . "'</script>");
  //print_r($user);
  //print_r($_SESSION);
}
else {
  echo("The state does not match. You may be a victim of CSRF.");
}

?>
