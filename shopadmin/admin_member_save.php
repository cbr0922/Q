<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

include_once 'crypt.class.php';
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
//print_r($_POST);
if ($_POST['Action']=='Insert' ) {

	$Query_old = $DB->query("select  username from `{$INFO[DBPrefix]}user` where username='".trim($_POST['username'])."' limit 0,1");
	$Num_old   = $DB->num_rows($Query_old);
	if ($Num_old>0){
		$FUNCTIONS->sorry_back('back',$Admin_Member[Ajax_Userhave]);
	}

	$pic   = $FUNCTIONS->Upload_File($_FILES['pic']['name'],$_FILES['pic']['tmp_name'],'',"../UploadFile/UserPic/");

	$Query_country = $DB->query("select membercode from `{$INFO[DBPrefix]}area` where areaname='" . $_POST['county'] . "' and top_id=0");
	$Rs_country = $DB->fetch_array($Query_country);
	$firstcode = $Rs_country['membercode'];
	$memberno = $FUNCTIONS->setMemberCode($firstcode);

	if($_POST['company']!=""){
		$Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}saler` where id='" . $_POST['company'] . "' limit 0,1");
		$Num_old   = $DB->num_rows($Query_old);
		if ($Num_old>0){
			$Result = $DB->fetch_array($Query_old);
			$companyid = intval($Result['id']);
			$userlevel = $Result['userlevel'];
			$givebouns  = intval($Result['givebouns']);
		}else{
			$userlevel = intval($_POST['user_level']);
		}
	}else{
			$userlevel = intval($_POST['user_level']);
		}

	$db_string = $DB->compile_db_insert_string( array (
	'username'          => $FUNCTIONS->smartshophtmlspecialchars($_POST['username']),
	'password'          => password_hash($FUNCTIONS->smartshophtmlspecialchars($_POST['password']), PASSWORD_BCRYPT),
	'true_name'         => $FUNCTIONS->smartshophtmlspecialchars($_POST['true_name']),
		'cn_secondname'         => trim($_POST['cn_secondname']),
			'en_firstname'         => trim($_POST['en_firstname']),
			'en_secondname'         => trim($_POST['en_secondname']),
		'bornCountry'            => $_POST['bornCountry'],
	'sex'               => $FUNCTIONS->smartshophtmlspecialchars($_POST['sex']),
	'born_date'         => $FUNCTIONS->smartshophtmlspecialchars($_POST['born_date']),
	'email'             => $FUNCTIONS->smartshophtmlspecialchars($_POST['email']),
	'addr'              => $FUNCTIONS->smartshophtmlspecialchars($_POST['addr']),
	'city'              => $FUNCTIONS->smartshophtmlspecialchars($_POST['city']),
	'canton'            => $FUNCTIONS->smartshophtmlspecialchars($_POST['province']),
	'Country'            => $FUNCTIONS->smartshophtmlspecialchars($_POST['county']),
	'fax'               => $FUNCTIONS->smartshophtmlspecialchars($_POST['fax']),
	'post'              => $FUNCTIONS->smartshophtmlspecialchars($_POST['othercity']),
	'tel'               => MD5Crypt::Encrypt ( trim($_POST['tel']), $INFO['tcrypt']),
	'other_tel'         => MD5Crypt::Encrypt ( trim($_POST['other_tel']), $INFO['mcrypt']),
	'reg_date'          => date("Y/m/d",time()),
	'reg_ip'            => $FUNCTIONS->getip(),
	'user_level'        => intval($userlevel),
	'user_state'        => intval($_POST['user_state']),
	'vloid'             => intval($_POST['vloid']),
	'certcode'             => trim($_POST['cert']),
	'companyid'             => intval($_POST['company']),
	'member_point'      => intval($FUNCTIONS->smartshophtmlspecialchars($_POST['member_point'])),
	'advance'           => intval($FUNCTIONS->smartshophtmlspecialchars($_POST['advance'])),
	'schoolname'             => trim($_POST['schoolname']),
	'chenghu'             => trim($_POST['chenghu']),
	'dianzibao'             => intval($_POST['dianzibao']),
	'nickname'             => trim($_POST['nickname']),
	'pic'            => $pic,
	'msn'             => trim($_POST['msn']),
	'blog'             => trim($_POST['blog']),
	'memberno'         => trim($memberno),
	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}user` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);
	$Insert_id = $DB->insert_id();
	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增會員");
		/**
		nuevoMailer系統串接
		**/

		if($INFO['nuevo.ifopen']==true){
			$_POST['realname'] = $_POST['true_name'];
			$_POST['phone'] = $_POST['tel'];
			$_POST['mobile'] = $_POST['other_tel'];
			$_POST['user_id'] = $Insert_id;
			include_once("../modules/apmail/nuevomailer.class.php");
			$nuevo = new NuevoMailer;
			$nuevo->setSubscribers("insert",$_POST);
		}

		/**
		 * 如果使用集成DZ论坛
		 */
		if (intval($INFO['SetupDiscuz'])==1) {
			$gendernew   = intval($_POST['sex']) ==1 ?  1 : 2 ;
			$reg_date    = time();
			$bday        = intval($_POST[byear])."-".intval($_POST[bmonth])."-".intval($_POST[bday]);
			$DZ_Sql      = "INSERT INTO `{$INFO[DiscuzSetupTablePre]}members` (username, password, gender, groupid, regip, regdate, email, bday, pmsound, showemail, newsletter, timeoffset,lastvisit,lastactivity) values('".$FUNCTIONS->smartshophtmlspecialchars(trim($_POST[nickname]))."','".$FUNCTIONS->smartshophtmlspecialchars(trim(password_hash($_POST[password], PASSWORD_BCRYPT)))."','".$gendernew."',10,'".$FUNCTIONS->getip()."','".$reg_date."','".$FUNCTIONS->smartshophtmlspecialchars(trim($_POST[email]))."','".$bday."','1','1','1','9999','".time()."','".time()."')";
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

		$FUNCTIONS->header_location('admin_member_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {
	/*
	$Query_old = $DB->query("select  username from user where username='".trim($_POST['username'])."' and user_id!=".intval($_POST['user_id'])." limit 0,1");
	$Num_old   = $DB->num_rows($Query_old);

	if ($Num_old>0){
	$FUNCTIONS->sorry_back('back',$PROG_TAGS[ptag_1876]);
	}
	*/
	//echo intval($_POST['company']);exit;
	$Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}user` where user_id='".intval($_POST['user_id'])."'  limit 0,1");
	$Num_old   = $DB->num_rows($Query_old);

	if ($Num_old>0){
		$Result= $DB->fetch_array($Query_old);
		$user_level = $Result['user_level'];
	}
	$pic   = $FUNCTIONS->Upload_File($_FILES['pic']['name'],$_FILES['pic']['tmp_name'],$_POST['old_pic'],"../UploadFile/UserPic/");
	if($_POST['company']!=""){
		$Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}saler` where id='" . $_POST['company'] . "' limit 0,1");
		$Num_old   = $DB->num_rows($Query_old);
		if ($Num_old>0){
			$Result = $DB->fetch_array($Query_old);
			$companyid = intval($Result['id']);
			$userlevel = $Result['userlevel'];
			$givebouns  = intval($Result['givebouns']);
		}else{
			$userlevel = intval($_POST['user_level']);
		}
	}else{
			$userlevel = intval($_POST['user_level']);
		}

	if($user_level!=$_POST['user_level'] || $user_level!=$userlevel)
		$ifhandlevel = 1;
	else
		$ifhandlevel = 0;

	$db_string = $DB->compile_db_update_string( array (
	'true_name'         => $FUNCTIONS->smartshophtmlspecialchars($_POST['true_name']),
		'cn_secondname'         => trim($_POST['cn_secondname']),
			'en_firstname'         => trim($_POST['en_firstname']),
			'en_secondname'         => trim($_POST['en_secondname']),
		'bornCountry'            => $_POST['bornCountry'],
	'sex'               => $FUNCTIONS->smartshophtmlspecialchars($_POST['sex']),
	'born_date'         => $FUNCTIONS->smartshophtmlspecialchars($_POST['born_date']),
	'email'             => $FUNCTIONS->smartshophtmlspecialchars($_POST['email']),
	'addr'              => $FUNCTIONS->smartshophtmlspecialchars($_POST['addr']),
	'fax'               => $FUNCTIONS->smartshophtmlspecialchars($_POST['fax']),
	'city'              => $FUNCTIONS->smartshophtmlspecialchars($_POST['city']),
	'canton'            => $FUNCTIONS->smartshophtmlspecialchars($_POST['province']),
	'Country'            => $FUNCTIONS->smartshophtmlspecialchars($_POST['county']),
	'fax'               => $FUNCTIONS->smartshophtmlspecialchars($_POST['fax']),
	'post'              => $FUNCTIONS->smartshophtmlspecialchars($_POST['othercity']),
	'zip'               => $FUNCTIONS->smartshophtmlspecialchars($_POST['othercity']),
	'tel'               => MD5Crypt::Encrypt ( trim($_POST['tel']), $INFO['tcrypt']),
	'other_tel'         => MD5Crypt::Encrypt ( trim($_POST['other_tel']), $INFO['mcrypt']),
	'member_point'      => $FUNCTIONS->smartshophtmlspecialchars($_POST['member_point']),
	'user_level'        => intval($userlevel),
	'vloid'             => intval($_POST['vloid']),
	'user_state'        => intval($_POST['user_state']),
	'certcode'             => trim($_POST['cert']),
	'companyid'             => intval($_POST['company']),
	'advance'           => intval($FUNCTIONS->smartshophtmlspecialchars($_POST['advance'])),
	'schoolname'             => trim($_POST['schoolname']),
	'chenghu'             => trim($_POST['chenghu']),
	'dianzibao'             => intval($_POST['dianzibao']),
	'nickname'             => trim($_POST['nickname']),
	'pic'            => $pic,
	'msn'             => trim($_POST['msn']),
	'blog'             => trim($_POST['blog']),
	'ifhandlevel'        => intval($ifhandlevel),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}user` SET $db_string WHERE user_id=".intval($_POST['user_id']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯會員");
		/**
		nuevoMailer系統串接
		**/

		if($INFO['nuevo.ifopen']==true){
			$_POST['realname'] = $_POST['true_name'];
			$_POST['phone'] = $_POST['tel'];
			$_POST['mobile'] = $_POST['other_tel'];
			include_once("../modules/apmail/nuevomailer.class.php");
			$nuevo = new NuevoMailer;
			$nuevo->setSubscribers("update",$_POST);
		}
		if(file_exists('../api/wordpress.php')) {
			include_once('../api/wordpress.php');
		}

		$FUNCTIONS->header_location('admin_member_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid  =  $_POST['cid'];
	$Num_bid    =  count($Array_bid);

	if(file_exists('../api/wordpress.php')) {
		include_once('../api/wordpress.php');
	}

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}user`  where user_id=".intval($Array_bid[$i]));
		//还没有删除相关数据。。。。。。。。。。。。。。

	}
	/**
	nuevoMailer系統串接
	**/

	if($INFO['nuevo.ifopen']==true){
		include_once("../modules/apmail/nuevomailer.class.php");
		$nuevo = new NuevoMailer;
		$nuevo->deleSubscribers($Array_bid);
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除會員");
		$FUNCTIONS->header_location('admin_member_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}



}
if ($_GET['Action']=='delPic' && intval($_GET['id'])>0  && $_GET['pic']!='' ) {
	$Sql = "select " . $_GET['type'] . " from  `{$INFO[DBPrefix]}user`  where user_id=".intval($_GET['id'])." limit 0,1";
	$Query =  $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Rs  = $DB->fetch_array($Query);
		@unlink("../UploadFile/UserPic/".$_GET['pic']);
	}

	$DB->query("update `{$INFO[DBPrefix]}user` set  " . $_GET['type'] . "='' where user_id=".intval($_GET['id'])." limit 1");
	$FUNCTIONS->setLog("刪除商品分類圖片");
	$FUNCTIONS->header_location('admin_member.php?user_id=' . $_GET['id'] . '&Action=Modi' );
}
if ($_GET['Action']=='resetpwd'){
	$DB->query("update `{$INFO[DBPrefix]}user` set  password='" . password_hash("111111", PASSWORD_BCRYPT) . "' where user_id=".intval($_GET['user_id'])." limit 1");
	$FUNCTIONS->setLog("重置密碼");
	$FUNCTIONS->header_location('admin_member_list.php' );
}
if ($_POST['act']=='badDel' ) {

	$Array_bid  =  $_POST['cid'];
	$Num_bid    =  count($Array_bid);


	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}baduser`  where user_id=".intval($Array_bid[$i]));
		//还没有删除相关数据。。。。。。。。。。。。。。
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除黑名单會員");
		$FUNCTIONS->header_location('admin_badmember_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}



}
?>
