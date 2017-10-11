<?php
error_reporting(7);
session_start();
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";

if(file_exists('../api/wordpress.php')) {
include_once('../api/wordpress.php');
}

if (($_POST['Action']=="Login" || $_POST['Action']=="ajaxLogin") && trim($_POST['username'])!="" && trim($_POST['passwd'])!=""){
	
	//校验验证码
	if (trim($_POST['inputcode'])!=$_SESSION['Code_Reg'] && $_POST['Action']=="Login"){
		$FUNCTIONS->sorry_back("back",$MemberLanguage_Pack[CodeIsBad_say]); //驗證碼錯誤
	}
	//升級
	/*
	$levelpoin = 0;
	$levelSql = "select * from `{$INFO[DBPrefix]}user_level` where level_num>0 order by level_num desc";
	$levelQuery    = $DB->query($levelSql);
	$levelNum      = $DB->num_rows($levelQuery);
	$i = 0;
	while ($levelRs=$DB->fetch_array($levelQuery)) {
		if ($i>0)
			$subSql = " and member_point<'" . $levelpoin . "'";
		else
			$subSql = " ";
		$uSql = "update `{$INFO[DBPrefix]}user` set user_level='" . intval($levelRs['level_id']) . "' where member_point>='" . intval($levelRs['level_num']) . "'" . $subSql;
		$DB->query($uSql);
		$levelpoin = intval($levelRs['level_num']);
		$i++;
	}
	*/
	
	//print_r($_POST);exit;
	if($_GET['hometype']=="groupon")
		$de_url_From = base64_decode($_POST[en_url_From])!="" ? base64_decode($_POST[en_url_From]) : "../index_groupon.php" ;
	else
    	$de_url_From = base64_decode($_POST[en_url_From])!="" ? base64_decode($_POST[en_url_From]) : "../index.php" ;
		if (trim($_POST['from'])=="shop"){
			if ($_POST['type']=="group")
				$de_url_From = "../shopping/shopping2_g.php?key=" . $_POST['key'];
		   else
				$de_url_From = "../shopping/shopping2.php?key=" . $_POST['key'];
		}

	//$Sql   = "select * from `{$INFO[DBPrefix]}user` u left join `{$INFO[DBPrefix]}user_level` l on (u.user_level=l.level_id) where u.username='".trim($_POST['username'])."' and u.password='".md5(trim($_POST['passwd']))."' limit 0,1";
	$Sql  = "select u.user_id,u.true_name,u.username,u.points,u.toppoints,u.user_level,u.vloid,v.volname,l.level_name,u.isold,u.islogin from `{$INFO[DBPrefix]}user` u left join `{$INFO[DBPrefix]}forum_vol` v on ( v.vloid=u.vloid ) left join `{$INFO[DBPrefix]}user_level` l on (u.user_level=l.level_id) where u.username='".trim($_POST['username'])."' and u.password='".md5(trim($_POST['passwd']))."' and user_state!=1 limit 0,1";

	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result = $DB->fetch_array($Query);
		$_SESSION['Member_Volid'] = $Rs['vloid'];
		$_SESSION['user_id']      = $Result['user_id'];
		$_SESSION['username']     = $Result['username'];
		$_SESSION['true_name']    = $Result['true_name'];
		$_SESSION['user_level']   = $Result['user_level'];
		if (intval($Result['user_level'])!=0){
			$_SESSION['userlevelname']  = $Result['level_name'];
		}else{
			$_SESSION['userlevelname']  = $MemberLanguage_Pack[Member_say];
		}
		$_SESSION['YesPass'] = "YesPass";
		//每日登陸送紅利
		$d = intval(date('d',time()));
		$y = intval(date('Y',time()));
		$m = intval(date('m',time()));
		$overtime = gmmktime(0,0,0,$m,$d,$y);
		$B_Sql = "select * from `{$INFO[DBPrefix]}bonuspoint` where `type`=8 and user_id='" . intval($Result['user_id']) . "' and addtime>='" . $overtime . "' and addtime<='" . ($overtime+60*60*24) . "'";
		$B_Query = $DB->query($B_Sql);
		$B_Num   = $DB->num_rows($B_Query);
		if ($B_Num<=0)
			$FUNCTIONS->AddBonuspoint(intval($Result['user_id']),intval($INFO['loginpoint']),8,"會員登入" . trim($Result['username']),1,0);
			
		$updatesql = "update `{$INFO[DBPrefix]}user` set islogin=1 where user_id='" . $Result['user_id'] . "'";
		$DB->query($updatesql);
		if($Result['isold']==1 && $Result['islogin']==0){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script>alert('請檢查您的資料');</script>";	
		}
		//升級
		$memberlevel_point = $FUNCTIONS->Userpoint(intval($_SESSION['user_id']),2);
		$levelSql = "select * from `{$INFO[DBPrefix]}user_level` where level_num<='" . $memberlevel_point . "' order by level_num desc";
		$levelQuery    = $DB->query($levelSql);
		$levelNum      = $DB->num_rows($levelQuery);
		if($levelNum>0){
			$levelRs=$DB->fetch_array($levelQuery);
			$uSql = "update `{$INFO[DBPrefix]}user` set user_level='" . intval($levelRs['level_id']) . "' where user_id='" . $Result['user_id'] . "'";
			$DB->query($uSql);
			$_SESSION['user_level']   = $levelRs['level_id'];
			$_SESSION['userlevelname']  = $levelRs['level_name'];
		}

		if($_POST['Action']=="ajaxLogin"){
			echo 1;
			exit;
		}
		if (trim($_POST[Url])!=""){
			$FUNCTIONS->header_location(base64_decode($_POST[Url]));
		}else{
			$FUNCTIONS->header_location($de_url_From);
		}

	}else{
		if($_POST['Action']=="ajaxLogin"){
			echo 0;
			exit;
		}
		//$FUNCTIONS->header_location("login_windows.php?wrong=bad&Url=".trim($_POST[Url]));
		$FUNCTIONS->sorry_back("login_windows.php?wrong=bad&Url=".trim($_POST[Url])."&hometype=" . $_GET['hometype'],"帳號或密碼錯誤"); //驗證碼錯誤
	}
}

if ($_GET['Action']=="Logout") {

	$_SESSION['user_id']     = '';
	$_SESSION['username']    = '';
	$_SESSION['true_name']    = '';
	$_SESSION['user_level']  = '';
	$_SESSION['userlevelname'] ='';
	$_SESSION['Member_Volid'] = '';

	/*
	if (!empty($_SESSION['cart'])){
		$cart =& $_SESSION['cart'];
		$cart->Empty_array();
	}
	*/

	session_destroy();
    $de_url_From = base64_decode($_GET[en_url_From])!="" ? base64_decode($_GET[en_url_From]) : "login_windows.php"."?hometype=" . $_GET['hometype'] ;
	//$FUNCTIONS->header_location("login_windows.php");
	$FUNCTIONS->header_location($de_url_From);
}
$FUNCTIONS->header_location("login_windows.php?wrong=bad&Url=".trim($_POST[Url])."&hometype=" . $_GET['hometype']);

?>

