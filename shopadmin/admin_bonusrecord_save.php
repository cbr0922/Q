<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
$Query  = $DB->query(" select * from `{$INFO[DBPrefix]}user` where username='".$_POST['username']."' limit 0,1");
$Num    = $DB->num_rows($Query);
if ($Num==0){
	if($_POST['mgroup_id']<=0)
		$FUNCTIONS->sorry_back("back","");
}else{
	$Result = $DB->fetch_array($Query);
	$user_id= $Result['user_id'];	
	$email= $Result['email'];	
}
if($_POST['type']==0){
	if(intval($user_id)>0){
		$FUNCTIONS->AddBonuspoint(intval($user_id),intval($_POST['bonus']),9,"調整會員積分 增加" . intval($_POST['bonus']),1);
		$send_array = array($user_id);
	}else{
		$Sql      = "select * from `{$INFO[DBPrefix]}mail_group` where mgroup_id='" . intval($_POST['mgroup_id']) . "' order by mgroup_id asc ";
		$Query    = $DB->query($Sql);
		$Rs=$DB->fetch_array($Query);
		if($Rs['searchlist']=="All"){
			 $sql_user = "select * from `{$INFO[DBPrefix]}user` ";
		}elseif($Rs['searchlist']=="noDing"){
			$sql_user = "select * from `{$INFO[DBPrefix]}user` where dianzibao=0";
		}else{
			$sql_user      = "select m.* from `{$INFO[DBPrefix]}mail_group_list` as m where group_id='" . intval($_POST['mgroup_id']) . "' order by m.user_id asc ";
		}
		$Query_user    = $DB->query($sql_user);
		$i = 0;
		$email_array = array();
		$send_array = array();
		while ($Rs_user=$DB->fetch_array($Query_user)) {
			$FUNCTIONS->AddBonuspoint(intval($Rs_user['user_id']),intval($_POST['bonus']),9,"調整會員積分 增加" . intval($_POST['bonus']),1);
			$email_array[$i]= $Rs_user['email'];
			$send_array[$i] = $Rs_user['user_id'];
			$i++;
		}
		$email = implode(",",$email_array);
	}
	$FUNCTIONS->setLog("調整會員積分");	
	//沒有使用MAIL系統
	if($INFO['nuevo.ifopen']!=true){
		include "SMTP.Class.inc.php";
		include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
		$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
		$Array =  array("bonuspoint"=>intval($_POST['bonus']));
		$SMTP->MailForsmartshop($email,"",28,$Array);
	}else{
		include_once("../modules/apmail/nuevomailer.class.php");
		$nuevo = new NuevoMailer;
		//$send_array = array($user_id);
		$idCampaign = $nuevo->setBonus(intval($_POST['bonus']),$send_array);
		$nuevo->queryMail($idCampaign,"admin_bonusrecord_list.php");
		exit;	
	}
}
if($_POST['type']==1){
	if(intval($user_id)>0){
		$FUNCTIONS->BuyBonuspoint(intval($user_id),intval($_POST['bonus']),"調整會員積分 減少" . intval($_POST['bonus']));	
	}else{
		$Sql      = "select * from `{$INFO[DBPrefix]}mail_group` where mgroup_id='" . intval($_POST['mgroup_id']) . "' order by mgroup_id asc ";
		$Query    = $DB->query($Sql);
		$Rs=$DB->fetch_array($Query);
		if($Rs['searchlist']=="All"){
			 $sql_user = "select * from `{$INFO[DBPrefix]}user` ";
		}elseif($Rs['searchlist']=="noDing"){
			$sql_user = "select * from `{$INFO[DBPrefix]}user` where dianzibao=0";
		}else{
			$sql_user      = "select m.* from `{$INFO[DBPrefix]}mail_group_list` as m where group_id='" . intval($_POST['mgroup_id']) . "' order by m.user_id asc ";
		}
		$Query_user    = $DB->query($sql_user);
		$i = 0;
		$email_array = array();
		$send_array = array();
		while ($Rs_user=$DB->fetch_array($Query_user)) {
			$FUNCTIONS->BuyBonuspoint(intval($Rs_user['user_id']),intval($_POST['bonus']),"調整會員積分 減少" . intval($_POST['bonus']));	
			$email_array[$i]= $Rs_user['email'];
			$send_array[$i] = $Rs_user['user_id'];
			$i++;
		}
		$email = implode(",",$email_array);
	}
}

$FUNCTIONS->header_location('admin_bonusrecord_list.php');
?>