<?php 

include("../configs.inc.php");
include("global.php");

//weibo API
include_once( 'saetv2.ex.class.php' );

$home_url = $INFO['site_url'];
define( "WB_AKEY"         , $INFO['mod.login.weibo.app_key'] );
define( "WB_SKEY"         , $INFO['mod.login.weibo.app_secret'] );
define( "WB_CALLBACK_URL" , $INFO['site_url'].'/member/login_weibo.php' );

session_start();

$code = $_REQUEST['code'];
if(empty($code)){

    $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
    $code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
    echo("<script> top.location.href='" . $code_url . "'</script>");
}




if (isset($_REQUEST['code'])) {

    $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
//    $keys['scope'] = 'email';
	try {
        $token = $o->getAccessToken( 'code', $keys ) ;

	} catch (OAuthException $e) {

	}
}
if ($token) {

	$_SESSION['token'] = $token;
	setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
    
    $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
    
    $uid_get = $c->get_uid();
    $uid = $uid_get['uid'];
    
    $user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息

    if(isset($user_message->error_code)){
        echo "<h3>error:</h3>" . $user_message->error_code;
        echo "<h3>msg  :</h3>" . $user_message->error;
        exit;
    
    }
    
    
    $email = $uid . "@weibo";
    $truename = $user_message['screen_name'];
    $username = $user_message['name'];
  
//  print_r($user_message);
  print_r($truename);
//  exit();
// register database
 if($uid==""){
	echo "發生錯誤";exit;
}   
    $Query_old = $DB->query("select  weibo_id from `{$INFO[DBPrefix]}user` where weibo_id='".$uid."' limit 0,1");
  $Num_old   = $DB->num_rows($Query_old);
  if ($Num_old>0){
          //echo "已經有用 Weibo 登入過了";
          //$FUNCTIONS->sorry_back('back',$MemberLanguage_Pack[SorryIsHadUserName]); //"對不起，帳號發生重複！請重新選擇輸入帳號！";
          echo ""; // do nothing
  }
  else{

	  $county = "中國大陸";
	  $Query_country = $DB->query("select membercode from `{$INFO[DBPrefix]}area` where areaname='" . $county . "' and top_id=0");
	  $Rs_country = $DB->fetch_array($Query_country);
	  $firstcode = $Rs_country['membercode'];
	  $memberno = $FUNCTIONS->setMemberCode($firstcode); 

	  {
		  if ($_POST['u_recommendno']!=""){
			  $u_recommendno = $_POST['u_recommendno'];
		  }
		  else{
			  $u_recommendno = $_COOKIE['u_recommendno'];
		  }

		  $companyid = 0;
		  $userlevel = 2;

		  if ($_POST['companypassword'] !=""){
			  $Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}saler` where openpwd='" . $_POST['companypassword'] . "' limit 0,1");
		  }elseif($_SESSION['saler']!=""){
			  $Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}saler` where login='" . $_SESSION['saler'] . "' limit 0,1");
		  }

		  if ($Query_old !=""){
			  $Num_old   = $DB->num_rows($Query_old);
		  }

		  if ($Num_old>0){
			  $Result = $DB->fetch_array($Query_old);
			  $companyid = intval($Result['id']);
			  $userlevel = $Result['userlevel'];
			  $givebouns  = intval($Result['givebouns']);
		  }

		  if($userlevel==0){
			  $userlevel = 2;
		  }
	  }

          $db_string = $DB->compile_db_insert_string( array (
      	    'username'          => $username,
      	    'true_name'         => $truename,
      	    'email'             => $email,
      	    'user_level'        => $userlevel,
	    'companyid'         => $companyid,
      	    'vloid'             => 1,
      	    'user_state'        => 0,
      	    'reg_date'          => date("Y-m-d",time()),
      	    'reg_ip'            => $FUNCTIONS->getip(),
	    'memberno'          => trim($memberno),
	    'recommendno'       => trim($u_recommendno),
      	    'weibo_id'       => $uid,
      	    )      
          );

          $Sql="INSERT INTO `{$INFO[DBPrefix]}user` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
          $Result_Insert=$DB->query($Sql);
  }

      $Sql  = "select u.user_id,u.true_name,u.username,u.points,u.toppoints,u.user_level,u.vloid,v.volname,l.level_name,u.isold,u.islogin from `{$INFO[DBPrefix]}user` u left join `{$INFO[DBPrefix]}forum_vol` v on ( v.vloid=u.vloid ) left join `{$INFO[DBPrefix]}user_level` l on (u.user_level=l.level_id) where u.weibo_id='".$uid."' and user_state!=1 limit 0,1";

      $Query = $DB->query($Sql);
      $Num   = $DB->num_rows($Query);
      if ($Num>0){
      	$Result = $DB->fetch_array($Query);
      	$_SESSION['Member_Volid'] = $Rs['vloid'];
      	$_SESSION['user_id']      = $Result['user_id'];
      	$_SESSION['username']      = $Result['username'];
      	$_SESSION['true_name']    = $Result['true_name'];
      	$_SESSION['user_level']   = $Result['user_level'];
      	if (intval($Result['user_level'])!=0){
      		$_SESSION['userlevelname']  = $Result['level_name'];
      	}else{
      		$_SESSION['userlevelname']  = $MemberLanguage_Pack[Member_say];
      	}
      	$_SESSION['YesPass'] = "YesPass";
      	//每日登陸送紅利
      	$d = date('d',time());
      	$y = intval(date('Y',time()));
      	$m = intval(date('m',time()));
      	$overtime = mktime(0,0,0,$m,$d,$y);
      	$B_Sql = "select * from `{$INFO[DBPrefix]}bonuspoint` where `type`=8 and user_id='" . intval($Result['user_id']) . "' and addtime>='" . $overtime . "' and addtime<='" . ($overtime+60*60*24) . "'";
      	$B_Query = $DB->query($B_Sql);
      	$B_Num   = $DB->num_rows($B_Query);
      	if ($B_Num<=0)
      		$FUNCTIONS->AddBonuspoint(intval($Result['user_id']),intval($INFO['loginpoint']),8,"會員登陸" . trim($Result['username']),1,0);
      		
      	$updatesql = "update `{$INFO[DBPrefix]}user` set islogin=1 where user_id='" . $Result['user_id'] . "'";
      	$DB->query($updatesql);
      	if($Result['isold']==1 && $Result['islogin']==0){
      		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script>alert('請檢查您的資料');</script>";	
      	}
      }

//    print_r($_SESSION);
//    exit();
      echo("<script> top.location.href='" . $home_url . "'</script>");    
    
} else {
    echo("The state does not match. You may be a victim of CSRF.");
}

?>
