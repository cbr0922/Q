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
		include("securimage.php");
	 $img=new Securimage();
	  $valid=$img->check($_POST['inputcode']);
	  	if($valid==false) {
	 $FUNCTIONS->sorry_back("back","驗證碼錯誤");
	}

	$companyid =  0;
	$userlevel = intval($INFO['reg_userlevel']);

	if ($_POST['companypassword'] !=""){
		$Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}saler` where openpwd='" . $_POST['companypassword'] . "' and ifpub=1 and (startdate<='" . date("Y-m-d") . "' or startdate='') and (enddate>='" . date("Y-m-d") . "' or enddate='') limit 0,1");
	}elseif($_SESSION['saler']!=""){
		$Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}saler` where login='" . $_SESSION['saler'] . "' and ifpub=1 and (startdate<='" . date("Y-m-d") . "' or startdate='') and (enddate>='" . date("Y-m-d") . "' or enddate='') limit 0,1");
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
	$authnum=randstr(5);
	$db_string = $DB->compile_db_insert_string( array (
	'username'          => trim($_POST['email']),
	'password'          => password_hash(trim($_POST['password']), PASSWORD_BCRYPT),
	'true_name'         => trim($_POST['realname']),
	'sex'               => intval($_POST['sex']),
	'idcard'            => trim($_POST['idcard']),
	'born_date'         => trim($_POST['byear']."-".$_POST['bmonth']."-".$_POST['bday']),
	'email'             => trim($_POST['email']),
	'addr'              => trim($_POST['address']),

	'city'              => $_POST['city'],
	'canton'            => $_POST['province'],
	'Country'            => $_POST['county'],
	'bornCountry'            => $_POST['county'],
	'zip'               => trim($_POST['othercity']),
	'fax'               => trim($_POST['fax']),
	'post'              => trim($_POST['post']),
	'tel'               => MD5Crypt::Encrypt ( trim($_POST['phone']), $INFO['tcrypt']),
	'certcode'               => trim($_POST['certcode']),
	'cn_secondname'         => trim($_POST['cn_secondname']),
			'en_firstname'         => trim($_POST['en_firstname']),
			'en_secondname'         => trim($_POST['en_secondname']),
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
	'user_state'=>1,
	'authnum' =>$authnum,
	'ifupdate'	=>1,
	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}user` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);
	$Insert_id = $DB->insert_id();

	if ($Result_Insert)
	{
		$FUNCTIONS->AddBonuspoint(intval($Insert_id),intval($INFO['regpoint']),6,"會員註冊" . trim($_POST['nickname']),1,0);
		$FUNCTIONS->AddTicket(intval($Insert_id),$INFO['ticket_id'],trim($_POST['email']),intval($INFO['ticketcount']));
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
		/**
		nuevoMailer系統串接
		**/
		if($INFO['nuevo.ifopen']==true){
			$_POST['user_id'] = $Insert_id;
			include_once("../modules/apmail/nuevomailer.class.php");
			$nuevo = new NuevoMailer;
			$nuevo->setSubscribers("insert",$_POST);
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
		$Array =  array("authnum"=>trim($authnum),"username"=>trim($_POST['email']),"truename"=>trim($_POST['realname']),"password"=>trim($_POST['password']));
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
 */
		$FUNCTIONS->sorry_back('reg_cellphone.php?mobile='.trim($_POST['mobile'])."&username=".trim($_POST['email']),"註冊手機驗證碼的簡訊已寄送，請查看並於此頁面進行驗證以開通會員帳號。");

	}

}


if ($_POST['Action']=='Update' ) {



	$Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}user` where user_id=".intval($_POST['user_id'])." limit 0,1");
	$Num_old   = $DB->num_rows($Query_old);

	if ($Num_old>0){

		$Result= $DB->fetch_array($Query_old);
		$Email   =  trim($Result['email']);
		$username   =  trim($Result['username']);
		if($Email!=$_POST['email']){
			$authnum=randstr(15);
			$subSql = ",authnum='".$authnum."',user_state=1";
		}
	}
	$pic   = $FUNCTIONS->Upload_File($_FILES['pic']['name'],$_FILES['pic']['tmp_name'],$_POST['old_pic'],"../UploadFile/UserPic/");
	if($_POST['byear']!="" && $_POST['bmonth']!="" && $_POST['bday']!="" ){
		$subSql .= ",born_date='" . trim($_POST['byear']."-".$_POST['bmonth']."-".$_POST['bday']) . "'";
	}

	$db_string = $DB->compile_db_update_string( array (
	'true_name'         => trim($_POST['truename']),
		'cn_secondname'         => trim($_POST['cn_secondname']),
			'en_firstname'         => trim($_POST['en_firstname']),
			'en_secondname'         => trim($_POST['en_secondname']),
		'certcode'               => trim($_POST['certcode']),
	'sex'               => trim($_POST['sex']),
	'email'             => trim($_POST['email']),
	//'born_date'         => trim($_POST['byear']."-".$_POST['bmonth']."-".$_POST['bday']),
	'addr'              => trim($_POST['address']),
	'city'              => $_POST['city'],
	'canton'            => $_POST['province'],
	'Country'            => $_POST['county'],
	'bornCountry'            => $_POST['bornCountry'],
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

	/*}
	else{

	$db_string = $DB->compile_db_update_string( array (
	'true_name'         => trim($_POST['truename']),
	'sex'               => trim($_POST['sex']),
	'email'             => trim($_POST['email']),
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

	}*/


	if(file_exists('../api/wordpress.php')) {
		include_once('../api/wordpress.php');
	}

	$Sql = "UPDATE `{$INFO[DBPrefix]}user` SET $db_string".$subSql." WHERE user_id=".intval($_POST['user_id']);

	$Result_Update = $DB->query($Sql);

	if ($Result_Update)
	{
		if($_POST['ifupdate'] == 0){
			$DB->query("UPDATE `{$INFO[DBPrefix]}user` SET ifupdate='1' WHERE user_id=".intval($_POST['user_id']));
			$FUNCTIONS->AddTicket(intval($_POST['user_id']),$INFO['ticket_id'],trim($_POST['email']),intval($INFO['ticketcount']));
			//echo "<script>alert('已發放註冊折價券');</script>";
		}
		if($subSql!=""){
			$Array =  array("authnum"=>trim($authnum),"username"=>trim($username),"truename"=>trim($_POST['realname']),"password"=>trim($_POST['password']));
			include "SMTP.Class.inc.php";
			include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
			$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
			$SMTP->MailForsmartshop(trim($_POST['email']),"",1,$Array);
		}
		/**
		nuevoMailer系統串接
		**/
		if($INFO['nuevo.ifopen']==true){
			include_once("../modules/apmail/nuevomailer.class.php");
			$nuevo = new NuevoMailer;
			$nuevo->setSubscribers("Change",$_POST);
		}
		$FUNCTIONS->header_location('index.php?hometype=' . $_GET['hometype']);
	}else{
		$FUNCTIONS->sorry_back('back',$MemberLanguage_Pack[YanZhengIsBad_say]);
	}

}




if ($_POST['Action']=='ChangePwd') {

	$Old_pw =  trim($_POST['old_pwd']);
	$New_pw =  password_hash(trim($_POST['f_pwd']), PASSWORD_BCRYPT);
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

	if (!password_verify($Old_pw , $Pw)){
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
if ($_GET['Action']=='ver') {

	$vcode =  (trim($_GET['vcode']));
	$username =  (trim($_GET['username']));
	 $Sql = "select u.*,l.level_name from `{$INFO[DBPrefix]}user` as u left join `{$INFO[DBPrefix]}user_level` l on (u.user_level=l.level_id) where u.authnum='".$vcode."' and u.username='" .$username. "' limit 0,1";
	// $Sql  = "select u.user_id,u.true_name,u.username,u.points,u.toppoints,u.user_level,u.vloid,v.volname,l.level_name from `{$INFO[DBPrefix]}user` u left join `{$INFO[DBPrefix]}forum_vol` v on ( v.vloid=u.vloid ) left join `{$INFO[DBPrefix]}user_level` l on (u.user_level=l.level_id) where u.username='".$FUNCTIONS->smartshophtmlspecialchars(trim($_POST[email]))."' and u.password='".md5(trim($_POST['password']))."'  limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$user_id      =  trim($Result['user_id']);
		$Pw      =  trim($Result['password']);
		$Email   =  trim($Result['email']);
		$other_tel   =  trim($Result['other_tel']);
		$true_name   =  trim($Result['true_name']);
		$username   =  trim($Result['username']);
		$recommendno   =  trim($Result['recommendno']);
		$companyid   =  trim($Result['companyid']);
		$Sql = "update `{$INFO[DBPrefix]}user` set user_state=0,authnum='' where user_id=".intval($user_id);
		$Modi_queryOk = $DB->query($Sql);

		$_SESSION['Member_Volid'] = $Result['vloid'];
			$_SESSION['user_id']      = $Result['user_id'];
			$_SESSION['username']     = $Result['username'];
			$_SESSION['true_name']    = $Result['true_name'];
			$_SESSION['user_level']   = $Result['user_level'];
			if (intval($Result['user_level'])!=0){
				$_SESSION['userlevelname']  = $Result['level_name'];
			}else{
				$_SESSION['userlevelname']  = "會員";
			}
			$_SESSION['YesPass'] = "YesPass";



		$FUNCTIONS->sorry_back("../index.php","驗證成功"); //'密码修改成功！'
		exit;
	}else{
		$FUNCTIONS->sorry_back("../index.php",'已驗證成功');
		exit;
	}

}
if ($_POST['Action']=='rever') {
	/*if (trim($_POST['inputcode'])!=$_SESSION['Code_Reg']){
		$FUNCTIONS->sorry_back("back",$MemberLanguage_Pack[CodeIsBad_say]); //驗證碼錯誤
	}*/
	include("securimage.php");
	 $img=new Securimage();
	  $valid=$img->check($_POST['inputcode']);
	  	if($valid==false) {
	 $FUNCTIONS->sorry_back("back","驗證碼錯誤");
	}
	$authnum=randstr(15);

	$Sql = "select * from `{$INFO[DBPrefix]}user` where username='" .$_POST['email']. "' and user_state=1 and authnum>'' limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Sql = "update `{$INFO[DBPrefix]}user` set user_state=1,authnum='" . $authnum . "' where username='".$_POST['email'] . "'";
	$Modi_queryOk = $DB->query($Sql);
		$Result = $DB->fetch_array($Query);
			$truename    = $Result['true_name'];

		$Array =  array("authnum"=>trim($authnum),"username"=>trim($_POST['email']),"truename"=>trim($truename),"password"=>trim($_POST['password']));
		include "SMTP.Class.inc.php";
		include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
		$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
		$SMTP->MailForsmartshop(trim($_POST['email']),"",1,$Array);
		//$FUNCTIONS->header_location('../index.php?hometype=' . $_GET['hometype']);
		$FUNCTIONS->sorry_back('../index.php?hometype=' . $_GET['hometype'],"註冊驗證信已發送，請至您的Email位址進行驗證以開通會員帳號。");
	}
	$FUNCTIONS->sorry_back('back',"對不起您填寫的資料不對");
}
if($_POST['Action']=="checkNum"){
	$vcode =  (trim($_POST['vcode']));
	$mobile =  (trim($_POST['mobile']));
	$username = (trim($_POST['username']));
	if ($vcode!=""){
		$Sql = "select u.*,l.* from `{$INFO[DBPrefix]}user` as u left join `{$INFO[DBPrefix]}user_level` l on (u.user_level=l.level_id) where u.authnum='".$vcode."'  limit 0,1";
		$Query = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			while($Result= $DB->fetch_array($Query)){
				if($mobile==MD5Crypt::Decrypt (trim($Result['other_tel']), $INFO['mcrypt'])){


					$user_id      =  trim($Result['user_id']);
					$Pw      =  trim($Result['password']);
					$Email   =  trim($Result['email']);
					$other_tel   =  trim($Result['other_tel']);
					$true_name   =  trim($Result['true_name']);
					$username   =  trim($Result['username']);
					$recommendno   =  trim($Result['recommendno']);
					$companyid   =  trim($Result['companyid']);
					$_SESSION['Member_Volid'] = $Result['vloid'];
				$_SESSION['user_id']      = $Result['user_id'];
				$_SESSION['username']     = $Result['username'];
				$_SESSION['true_name']    = $Result['true_name'];
				$_SESSION['user_level']   = $Result['user_level'];
				if (intval($Result['user_level'])!=0){
					$_SESSION['userlevelname']  = $Result['level_name'];
				}else{
					$_SESSION['userlevelname']  = "會員";
				}
				$_SESSION['YesPass'] = "YesPass";
					$Sql = "update `{$INFO[DBPrefix]}user` set user_state=0,authnum='' where user_id=".intval($user_id);
					$Modi_queryOk = $DB->query($Sql);
					$FUNCTIONS->sorry_back("reg_ok.php","驗證成功"); //'密码修改成功！'
					exit;
				}
			}
			$FUNCTIONS->sorry_back("reg_cellphone.php?mobile=" . $mobile."&username=".$username,'驗證失敗');
			exit;

		}else{
			$FUNCTIONS->sorry_back("reg_cellphone.php?mobile=" . $mobile."&username=".$username,'驗證失敗');
			exit;
		}
	}else{
		$FUNCTIONS->sorry_back("reg_cellphone.php?mobile=" . $mobile."&username=".$username,'驗證失敗');
		exit;
	}
}
if ($_POST['Action']=='mrever') {
	$authnum=randstr(5);

	$Sql = "select * from `{$INFO[DBPrefix]}user` where username='" .$_POST['username']. "' limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		if($_POST['mobile']!=''){
			if(($Result['other_tel']=='' && ($Result['facebook_id']!='' || $Result['yahoo_gid']!='')) || $_POST['mobile']!=MD5Crypt::Decrypt (trim($Result['other_tel']), $INFO['mcrypt'])){
				$Sql = "select other_tel from `{$INFO[DBPrefix]}user`";
				$Query  = $DB->query($Sql);
				$Num    = $DB->num_rows($Query);
				if ($Num>0){
					while ($Rs = $DB->fetch_array($Query)) {
						if($_POST['mobile']==MD5Crypt::Decrypt (trim($Rs['other_tel']), $INFO['mcrypt'])){
							echo 2;
							exit;
						}
					}
				}
			}
			$Sql = "update `{$INFO[DBPrefix]}user` set user_state=1,authnum='" . $authnum . "',other_tel='" . MD5Crypt::Encrypt ( trim($_POST['mobile']), $INFO['mcrypt']) . "' where username='".$_POST['username'] . "'";
			$Modi_queryOk = $DB->query($Sql);
			$Result = $DB->fetch_array($Query);
			$truename    = $Result['true_name'];

			$Array =  array("authnum"=>trim($authnum),"username"=>trim($_POST['username']),"truename"=>trim($truename),"password"=>trim($_POST['password']));
			include "sms2.inc.php";
			include "sendmsg.php";

			$sendmsg = new SendMsg;
			$sendmsg->send(trim($_POST['mobile']),$Array,1);
			echo 1;
			exit;
		}
	echo 0;
	}
}
function randstr($len=6) {
	$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	// characters to build the password from
	mt_srand((double)microtime()*1000000*getmypid());
	// seed the random number generater (must be done)
	$password='';
	while(strlen($password)<$len)
	$password.=substr($chars,(mt_rand()%strlen($chars)),1);
	return $password;
}
?>
