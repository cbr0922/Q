<?php

include("../configs.inc.php");
include("global.php");

$app_id     = $INFO['mod.login.fb.app_id'];
$app_secret = $INFO['mod.login.fb.app_secret'];
$my_url     = $INFO['site_url']."/member/login_facebook.php";
$home_url   = $INFO['site_url']."/member/reg_ok.php";

session_start();
$code = $_REQUEST["code"];

/*if($_REQUEST["error_code"] == 200){
  echo("<script> top.location.href='" . $INFO['site_url']."/member/login_windows.php" . "'</script>");
  exit();
}*/

if(!empty($_GET[url])) {
  $home_url = $_GET[url];
  $my_url   = $INFO['site_url']."/member/login_facebook.php?url=" . urlencode($home_url);
}

if(empty($code)) {
  $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
  $dialog_url = "http://www.facebook.com/dialog/oauth?client_id="
    . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
    . $_SESSION['state'] ."&scope=public_profile,email";

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
    . $params['access_token']."&fields=id,name,email,gender";

  $user = json_decode(file_get_contents($graph_url));
  //print_r($user);exit;
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
  $Query_old = $DB->query("select  facebook_id from `{$INFO[DBPrefix]}user` where facebook_id='".$user->id."' limit 0,1");
  $Num_old   = $DB->num_rows($Query_old);
  if ($Num_old>0){

	    $Sql  = "select u.user_id,u.true_name,u.username,u.points,u.toppoints,u.user_level,u.vloid,v.volname,l.level_name,u.isold,u.islogin from `{$INFO[DBPrefix]}user` u left join `{$INFO[DBPrefix]}forum_vol` v on ( v.vloid=u.vloid ) left join `{$INFO[DBPrefix]}user_level` l on (u.user_level=l.level_id) where u.facebook_id='".$user->id."' and user_state!=1 limit 0,1";
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
		//登陸日誌
		$IP = $FUNCTIONS->getip();
		$DB->query( "insert into  `{$INFO[DBPrefix]}user_log` (user_id,ip,logintime) values('".$_SESSION['user_id']."','".$IP."','".time()."')");
		//購物車處理

		 $Sql = "UPDATE `{$INFO[DBPrefix]}shopping` SET user_id='" . $_SESSION['user_id'] . "' where user_id='' and session_id='" . $session_id . "'";
		$Result_Update = $DB->query($Sql);
      	if($Result['isold']==1 && $Result['islogin']==0){
      		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script>alert('請檢查您的資料');</script>";
      	}
      }


  echo("<script> top.location.href='" . $home_url . "'</script>");
  exit;
          //echo "已經有用 facebook 登入過了";
          //$FUNCTIONS->sorry_back('back',$MemberLanguage_Pack[SorryIsHadUserName]); //"對不起，帳號發生重複！請重新選擇輸入帳號！";
          //echo ""; // do nothing
  }
  else{
    $Query = $DB->query("select username from `{$INFO[DBPrefix]}user` where username='".$user->email."' limit 0,1");
    $Num   = $DB->num_rows($Query);
    if ($Num>0 && $user->email!=''){
      $FUNCTIONS->sorry_back("back","Email帳號已存在，請使用會員帳號登入");
      //echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('Email帳號已存在，請使用會員帳號登入');location.href='" . $INFO['site_url'] . "/member/login_windows.php';</script>";
      exit;
    }

	  $county = "台灣";
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
		  $userlevel = intval($INFO['reg_userlevel']);

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
			  $userlevel = intval($INFO['reg_userlevel']);
		  }
	  }

    if($user->email == ''){
      $username = $user->id."@facebook";
    }else {
      $username = $user->email;
    }
    $sex = $user->gender == "male" ? 0: 1;

          $db_string = $DB->compile_db_insert_string( array (
      	    'username'          => $username,
      	    'true_name'         => $user->name,
      	    'email'             => $user->email,
      	    'user_level'        => $userlevel,
	          'companyid'         => $companyid,
      	    'vloid'             => 1,
      	    'user_state'        => 0,
      	    'reg_date'          => date("Y-m-d",time()),
      	    'reg_ip'            => $FUNCTIONS->getip(),
	          'memberno'          => trim($memberno),
	          'recommendno'       => trim($u_recommendno),
      	    'facebook_id'       => $user->id,
            'sex'               => $sex,
      	    )
          );

          $Sql="INSERT INTO `{$INFO[DBPrefix]}user` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

          $Result_Insert=$DB->query($Sql);
          $Insert_id = $DB->insert_id();

          if ($Result_Insert){
            $FUNCTIONS->AddBonuspoint(intval($Insert_id),intval($INFO['regpoint']),6,"會員註冊",1,0);
          }
  }

      $Sql  = "select u.user_id,u.true_name,u.username,u.points,u.toppoints,u.user_level,u.vloid,v.volname,l.level_name,u.isold,u.islogin from `{$INFO[DBPrefix]}user` u left join `{$INFO[DBPrefix]}forum_vol` v on ( v.vloid=u.vloid ) left join `{$INFO[DBPrefix]}user_level` l on (u.user_level=l.level_id) where u.facebook_id='".$user->id."' and user_state!=1 limit 0,1";

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
		//登陸日誌
		$IP = $FUNCTIONS->getip();
		$DB->query( "insert into  `{$INFO[DBPrefix]}user_log` (user_id,ip,logintime) values('".$_SESSION['user_id']."','".$IP."','".time()."')");
		//購物車處理

		 $Sql = "UPDATE `{$INFO[DBPrefix]}shopping` SET user_id='" . $_SESSION['user_id'] . "' where user_id='' and session_id='" . $session_id . "'";
		$Result_Update = $DB->query($Sql);
      	if($Result['isold']==1 && $Result['islogin']==0){
      		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script>alert('請檢查您的資料');</script>";
      	}
      }


  echo("<script> top.location.href='" . $INFO['site_url'] . "/member/reg_ok.php'</script>");
  //print_r($user);
  //print_r($_SESSION);
}
else {
  echo("The state does not match. You may be a victim of CSRF.");
}

?>
