<?php
session_start();
include("../configs.inc.php");
include("global.php");
include_once 'crypt.class.php';
@header("Content-type: text/html; charset=utf-8");
//print_r($_POST);
/**
 *  装载产品语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";


if ($_POST['Action']=='Insert' ) {
	$_SESSION['user_id']     = '';
		$_SESSION['username']    = '';
		$_SESSION['true_name']    = '';
		$_SESSION['user_level']  = '';
		$_SESSION['userlevelname'] ='';
		$_SESSION['Member_Volid'] = '';

	//获得提交后的时间，加上45秒。以保证后退后能保存一段信息！  //着部分要用COOKIE做
	//include "Time.class.php";
	//$TimeClass  = new TimeClass  ;
	//$Push = $TimeClass->getSpetime("Second",45);
	//$Regdate = array();
	//$_SESSION = $Regdate;
	//$Register          = array();
	//$Register          = $_POST;
	//$Register['idate'] = $Push;


	//这个地方是用COOKIE 目的就是不让它和验证码的SESSION有冲突。如果用户服务器不支持这个，那么我也是没有办法了！哈。无所谓了！
	$CountPost = count($_POST);
	$POST_arraykeys = array_keys($_POST);
	for ($i=0;$i<$CountPost;$i++){
		$J = $POST_arraykeys[$i];
		@setcookie ($J,$_POST[$J],time() + 35);
	}



	//校验验证码
	/*
	if (trim($_POST['inputcode'])!=$_SESSION['Code_Reg']){
		$FUNCTIONS->sorry_back("back",$MemberLanguage_Pack[CodeIsBad_say]); //驗證碼錯誤
	}
	*/
	$companyid =  0;
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}user_level` order by level_id desc limit 0,1");
	$Result= $DB->fetch_array($Query);
	$userlevel = $Result['level_id'];
	
	if ($_POST['companypassword'] !=""){
		$Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}saler` where openpwd='" . $_POST['companypassword'] . "' and ifpub=1 and (startdate<='" . date("Y-m-d") . "' or startdate='') and (enddate>='" . date("Y-m-d") . "' or enddate='') limit 0,1");
	}elseif($_SESSION['saler']!=""){
		$Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}saler` where login='" . $_SESSION['saler'] . "' and ifpub=1 and ifpub=1 and (startdate<='" . date("Y-m-d") . "' or startdate='') and (enddate>='" . date("Y-m-d") . "' or enddate='') limit 0,1");
	}
	if ($Query_old !="")
		$Num_old   = $DB->num_rows($Query_old);
	if ($Num_old>0){
		$Result = $DB->fetch_array($Query_old);
		$companyid = intval($Result['id']);
		$userlevel = $Result['userlevel'];
		$givebouns  = intval($Result['givebouns']);
	}
	//print_r($Result);
//echo $companyid;exit;

	$Query_old = $DB->query("select  username from `{$INFO[DBPrefix]}user` where username='".trim($_POST['email'])."' limit 0,1");
	$Num_old   = $DB->num_rows($Query_old);
	if ($Num_old>0){
		$FUNCTIONS->sorry_back('back',$MemberLanguage_Pack[SorryIsHadUserName]); //"對不起，帳號發生重複！請重新選擇輸入帳號！";
	}
	
	

	$Query_country = $DB->query("select membercode from `{$INFO[DBPrefix]}area` where areaname='" . $_POST['county'] . "' and top_id=0");
	$Rs_country = $DB->fetch_array($Query_country);
	$firstcode = $Rs_country['membercode'];
	$memberno = $FUNCTIONS->setMemberCode($firstcode); 
	
	$pic   = $FUNCTIONS->Upload_File($_FILES['pic']['name'],$_FILES['pic']['tmp_name'],'',"../UploadFile/UserPic/");
	if ($_POST['u_recommendno']!="")
		$u_recommendno = $_POST['u_recommendno'];
	else
		$u_recommendno = $_COOKIE['u_recommendno'];
	if($u_recommendno!=""){
		$Query_old = $DB->query("select  memberno from `{$INFO[DBPrefix]}user` where memberno='".trim($u_recommendno)."' limit 0,1");
		$Num_old   = $DB->num_rows($Query_old);
		if ($Num_old<=0){
			$FUNCTIONS->sorry_back('back',"您填寫的推薦人並不存在"); //"對不起，帳號發生重複！請重新選擇輸入帳號！";
		}
	}
	
	$db_string = $DB->compile_db_insert_string( array (
	'username'          => trim($_POST['email']),
	'password'          => md5(trim($_POST['password'])),
	'true_name'         => trim($_POST['realname']),
	'sex'               => intval($_POST['sex']),
	'idcard'            => trim($_POST['idcard']),
	'born_date'         => trim($_POST['byear']."-".$_POST['bmonth']."-".$_POST['bday']),
	'email'             => trim($_POST['email']),
	'addr'              => trim($_POST['address']),

	'city'              => $_POST['city'],
	'canton'            => $_POST['province'],
	'Country'            => $_POST['county'],
	'zip'               => trim($_POST['othercity']),
	'fax'               => trim($_POST['fax']),
	'post'              => trim($_POST['post']),
	'tel'               => MD5Crypt::Encrypt ( trim($_POST['phone']), $INFO['tcrypt']),
	'certcode'               => trim($_POST['cert']),
	'user_level'        => $userlevel,
	'other_tel'         => MD5Crypt::Encrypt ( trim($_POST['mobile']), $INFO['mcrypt']),
	'reg_date'          => date("Y-m-d",time()),
	'reg_ip'            => $FUNCTIONS->getip(),
	'companyid'         => $companyid,
	'schoolname'         => trim($_POST['schoolname']),
	'chenghu'         => trim($_POST['chenghu']),
	'member_point'    => intval($INFO['regpoint']),
	'dianzibao'    => intval($_POST['dianzibao']),
	'memberno'         => trim($memberno),
	'recommendno'         => trim($u_recommendno),
	'nickname'         => trim($_POST['nickname2']),
	'pic'            => $pic,
	'msn'         => trim($_POST['msn']),
	'blog'         => trim($_POST['blog']),
	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}user` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);
	$Insert_id = $DB->insert_id();

	if ($Result_Insert)
	{
		$FUNCTIONS->AddBonuspoint(intval($Insert_id),intval($INFO['regpoint']),6,"會員註冊" . trim($_POST['nickname']),1,0);
		if ($givebouns>0){
			$FUNCTIONS->AddBonuspoint(intval($Insert_id),intval($givebouns),7,"會員註冊經銷商分發積分" . trim($_POST['nickname']),1,0);	
		}
		if (trim($u_recommendno)!=""){
			$u_sql = "select * from `{$INFO[DBPrefix]}user` where memberno='" . trim($u_recommendno) . "'";
			$Query_u=$DB->query($u_sql);
			$Rs_u = $DB->fetch_array($Query_u);
			$ruserid = $Rs_u['user_id'];
			$FUNCTIONS->AddBonuspoint(intval($ruserid),intval($INFO['recommendPoint']),4,"推薦會員" . trim($_POST['nickname']),1,0);
		}
		//注销COOKIE
		@setcookie ("nickname","",time() -1);
		@setcookie ("realname","",time() -1);
		@setcookie ("sex","",time() -1);
		@setcookie ("byear","",time() -1);
		@setcookie ("bmonth","",time() -1);
		@setcookie ("bday","",time() -1);
		@setcookie ("address","",time() -1);
		@setcookie ("post","",time() -1);
		@setcookie ("mobile","",time() -1);
		@setcookie ("phone","",time() -1);
		$Array =  array("username"=>trim($_POST['email']),"truename"=>trim($_POST['realname']),"password"=>trim($_POST['password']));
		include "SMTP.Class.inc.php";
		include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
		$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
		$SMTP->MailForsmartshop(trim($_POST['email']),"",1,$Array);
		
		include "sms2.inc.php";
		include "sendmsg.php";
		
		$sendmsg = new SendMsg;
		$sendmsg->send(trim($_POST['mobile']),$Array,1);
		
		/**
		 * 如果使用集成DZ论坛
		 
		if (intval($INFO['SetupDiscuz'])==1) {
			$gendernew   = intval($_POST['sex']) ==1 ?  1 : 2 ;
			$reg_date    = time();
			$bday        = intval($_POST[byear])."-".intval($_POST[bmonth])."-".intval($_POST[bday]);
			$DZ_Sql      = "INSERT INTO `{$INFO[DiscuzSetupTablePre]}members` (username, password, gender, groupid, regip, regdate, email, bday, pmsound, showemail, newsletter, timeoffset,lastvisit,lastactivity) values('".$FUNCTIONS->smartshophtmlspecialchars(trim($_POST[nickname]))."','".$FUNCTIONS->smartshophtmlspecialchars(trim(md5($_POST[password])))."','".$gendernew."',10,'".$FUNCTIONS->getip()."','".$reg_date."','".$FUNCTIONS->smartshophtmlspecialchars(trim($_POST[email]))."','".$bday."','1','1','1','9999','".time()."','".time()."')";
			$DB->query($DZ_Sql);
			$InsertU_id  = $DB->insert_id();
			$DZnext_Sql  = "INSERT INTO {$INFO[DiscuzSetupTablePre]}memberfields (`uid` , `nickname` , `site` , `alipay` , `icq` , `qq` , `yahoo` , `msn` , `taobao` , `location` , `customstatus` , `medals` , `avatar` , `avatarwidth` , `avatarheight` , `bio` , `signature` , `sightml` , `ignorepm` , `groupterms` , `authstr` ) value ('$InsertU_id', '', '', '', '', '', '', '', '', '', '', '', '', '0', '0', '' , '', '', '', '', ''
)";
			$DB->query($DZnext_Sql);
			//$DZ_Num    = $DB->num_rows($DZ_Query);
		}

		if(file_exists('../api/wordpress.php')) {
			include_once('../api/wordpress.php');
		}
*/
		/**
		 * 这里将直接赋予SESSION值可以同时登陆。
		 */
		$Sql  = "select u.user_id,u.true_name,u.username,u.points,u.toppoints,u.user_level,u.vloid,v.volname,l.level_name from `{$INFO[DBPrefix]}user` u left join `{$INFO[DBPrefix]}forum_vol` v on ( v.vloid=u.vloid ) left join `{$INFO[DBPrefix]}user_level` l on (u.user_level=l.level_id) where u.username='".$FUNCTIONS->smartshophtmlspecialchars(trim($_POST[email]))."' and u.password='".md5(trim($_POST['password']))."'  limit 0,1";
		$Query = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result = $DB->fetch_array($Query);
			$_SESSION['Member_Volid'] = $Result['vloid'];
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
		}

		$FUNCTIONS->header_location('../index.php?hometype=' . $_GET['hometype']);

	}

}


if ($_POST['Action']=='Update' ) {

	$Query_old = $DB->query("select  username from `{$INFO[DBPrefix]}user` where username='".trim($_POST['username'])."' and user_id!=".intval($_POST['user_id'])." limit 0,1");
	$Num_old   = $DB->num_rows($Query_old);

	if ($Num_old>1){
		$FUNCTIONS->sorry_back('back',$MemberLanguage_Pack[SorryIsHadUserName]); //"對不起，帳號發生重複！請重新選擇輸入帳號！";
	}
	$pic   = $FUNCTIONS->Upload_File($_FILES['pic']['name'],$_FILES['pic']['tmp_name'],$_POST['old_pic'],"../UploadFile/UserPic/");

	$db_string = $DB->compile_db_update_string( array (
	'true_name'         => trim($_POST['truename']),
	'sex'               => trim($_POST['sex']),
	'born_date'         => trim($_POST['byear']."-".$_POST['bmonth']."-".$_POST['bday']),
	'addr'              => trim($_POST['address']),
	'city'              => $_POST['city'],
	'canton'            => $_POST['province'],
	'Country'            => $_POST['county'],
	'zip'               => trim($_POST['othercity']),
	'fax'               => trim($_POST['fax']),
	'post'              => trim($_POST['post']),
	'tel'               => MD5Crypt::Encrypt ( trim($_POST['phone']), $INFO['tcrypt']),
	'other_tel'         => MD5Crypt::Encrypt ( trim($_POST['mobile']), $INFO['mcrypt']),
	'schoolname'         => trim($_POST['schoolname']),
	'chenghu'         => trim($_POST['chenghu']),
	'dianzibao'         => intval($_POST['dianzibao']),
	'nickname'         => trim($_POST['nickname']),
	'pic'            => $pic,
	'msn'         => trim($_POST['msn']),
	'blog'         => trim($_POST['blog']),
	
	));

	if(file_exists('../api/wordpress.php')) {
		include_once('../api/wordpress.php');
	}

	$Sql = "UPDATE `{$INFO[DBPrefix]}user` SET $db_string WHERE user_id=".intval($_POST['user_id']);

	$Result_Update = $DB->query($Sql);

	if ($Result_Update)
	{
		$FUNCTIONS->header_location('index.php?hometype=' . $_GET['hometype']);
	}else{
		$FUNCTIONS->sorry_back('back',$MemberLanguage_Pack[YanZhengIsBad_say]);
	}

}

if ($_POST['act']=='Del' ) {

	$Array_bid  =  $_POST['cid'];
	$Num_bid    =  count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}user` where user_id=".intval($Array_bid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->header_location('admin_member_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$MemberLanguage_Pack[YanZhengIsBad_say] );
	}
}


if ($_POST['Action']=='ChangePwd') {

	$Old_pw =  md5(trim($_POST['old_pwd']));
	$New_pw =  md5(trim($_POST['f_pwd']));
	$Sql = "select * from `{$INFO[DBPrefix]}user` where user_id=".intval($_SESSION['user_id'])." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);

		$Pw      =  trim($Result['password']);
		$Email   =  trim($Result['email']);
		$other_tel   =  trim($Result['other_tel']);
		$true_name   =  trim($Result['true_name']);
		$username   =  trim($Result['username']);
	}else{
		$FUNCTIONS->sorry_back("index.php?hometype=" . $_GET['hometype'],'NoMember');
		exit;
	}
	//echo $Pw;
	//echo "<br>";
	//echo $Old_pw;

	if ($Pw!=$Old_pw){
		$FUNCTIONS->sorry_back("index.php?hometype=" . $_GET['hometype'],$MemberLanguage_Pack[Ydm_bad] ); //原密码输入不正确！
		exit;
	}else{

		$Sql = "update `{$INFO[DBPrefix]}user` set password='".$New_pw."' where user_id=".intval($_SESSION['user_id']);
		$Modi_queryOk = $DB->query($Sql);


		if ($Modi_queryOk) {
			$Array =  array("f_pwd"=>trim($_POST['f_pwd']),"truename"=>trim($true_name),"username"=>trim($username));
			include "SMTP.Class.inc.php";
			include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
			$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
			$SMTP->MailForsmartshop(trim($Email),"",2,$Array);
			
			include "sms2.inc.php";
			include "sendmsg.php";
			
			$sendmsg = new SendMsg;
			$sendmsg->send(trim($other_tel),$Array,2);
		}

		if(file_exists('../api/wordpress.php')) {
			include_once('../api/wordpress.php');
		}

		$FUNCTIONS->sorry_back("index.php?hometype=" . $_GET['hometype'],$MemberLanguage_Pack[PassWordModiIsPass_say]); //'密码修改成功！'
		exit;
	}
}
?>
