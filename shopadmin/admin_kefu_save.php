<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";
include_once "SMTP.Class.inc.php";
include_once RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);

if ($_POST['Action']=='Post' ) {

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}kefu` where kid=".intval(trim($_POST['kid']))." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$serialnum            =  $Result['serialnum'];
		$type_chuli           =  $Result['type_chuli'];
		$type_chuli_name      =  $Result['type_chuli_name'];
		$username             =  $Result['username'];
		$realname             =  $Result['realname'];
		$email                =  $Result['email'];
		$postnum              =  $Result['postnum'];
		$title                =  $Result['title'];
		$lastdate             =  $Result['lastdate'];
		$status               =  $Result['status'];
		$k_kefu_con           =  $Result['k_kefu_con'];
		$order_serial           =  $Result['order_serial'];
		$marketno           =  $Result['marketno'];

	}
	$timeforserialnum = time();
	$db_string = $DB->compile_db_insert_string( array (
	'kid'                       => trim($_POST['kid']),
	'username'                  => $KeFu_Pack['Back_System_report'],
	'postdate'                  => $timeforserialnum,
	'k_post_title'              => $KeFu_Pack['Back_System_report'],
	'k_post_con'                => trim($_POST['k_tem_con']),
	'ifcheck'=>1,

	)      );

	if ($_POST['kefu_tem']!='none') {
		$Sql="INSERT INTO `{$INFO[DBPrefix]}kefu_posts` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
		$Result_Insert=$DB->query($Sql);
	}
	//修改kefu表數據
	if (isset($_POST['type'])) {
		 $linshi = $_POST['select_type'] . "-" . $_POST['chuli'];
		//$linshi = $_POST['type_chuli'];

		$number = explode('-',$linshi);

		$Sql_linshi = "select k_type_name from `{$INFO[DBPrefix]}kefu_type` where k_type_id = '" . $number[0] . "'";
		$Query_linshi = $DB->query($Sql_linshi);
		$type_name_linshi = $DB->fetch_array($Query_linshi);
		$Sql_linshi = "select k_chuli_name , ifclose from `{$INFO[DBPrefix]}kefu_chuli` where k_chuli_id ='" .  $number[1] . "'";
		$Query_linshi = $DB->query($Sql_linshi);
		$chuli_name_linshi = $DB->fetch_array($Query_linshi);
		$type_chuli_name = $type_name_linshi['k_type_name'].'-'.$chuli_name_linshi['k_chuli_name'];

		$status = 1;
		if ($chuli_name_linshi['ifclose']==1) {
			$status = 2;
		}

		$Sql="UPDATE `{$INFO[DBPrefix]}kefu` set type_chuli='".$_POST['type_chuli']."' , type_chuli_name='".$type_chuli_name."' , postnum = 1 , status = '".$status."' , iflogin = ".$_POST['iflogin']." where kid = ".$_POST['kid'];
	}else {
		$Sql="UPDATE `{$INFO[DBPrefix]}kefu` set postnum = 1 where kid = '".$_POST['kid']."'";
	}

	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$Array =  array("truename"=>trim($realname),"k_kefu_con"=>trim($k_kefu_con),"k_post_con"=>trim($_POST['k_tem_con']));
		$SMTP->MailForsmartshop(trim($email),"",17,$Array);

		$FUNCTIONS->setLog("回覆在線客服");
		$FUNCTIONS->setKefuLog($_POST['kid'],"管理員回覆",1);
		$url_locate = 'admin_kefu_list.php';
		$FUNCTIONS->header_location($url_locate);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}


if ($_POST['act']=='Del' ) {

	$Array_cid =  $_POST['cid'];
	$Num_cid  = count($Array_cid);

	for ($i=0;$i<$Num_cid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}kefu` where kid=".intval($Array_cid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除在線客服");
		 $url_locate = 'admin_kefu_list.php';
		$FUNCTIONS->header_location($url_locate);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

if ($_GET['Action']=='DelPost' ) {

	$k_post_id =  $_GET['k_post_id'];

	$Result =  $DB->query("delete from `{$INFO[DBPrefix]}kefu_posts` where k_post_id=".intval($k_post_id));

	if ($Result)
	{
		$FUNCTIONS->setLog("刪除在線客服回覆");
		$FUNCTIONS->setKefuLog($_POST['kid'],"管理員刪除回覆，編號" . $k_post_id,1);
		$url_locate = 'admin_kefu.php?Action=Modi&kid='.$_GET['kid'];
		$FUNCTIONS->header_location($url_locate);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

if ($_GET['Action']=='check' ) {

	$k_post_id =  $_GET['k_post_id'];

	$Result =  $DB->query("update `{$INFO[DBPrefix]}kefu_posts` set ifcheck='" . (1-intval($_GET['state'])) . "' where k_post_id=".intval($k_post_id));
	if (intval($_GET['state'])==1){
		$status = 3;
	}else{
		$status = 1;
	}
	if (intval($_GET['state'])==1){
		$stitle = "撤銷審核";
	}elseif (intval($_GET['state'])==0){
		$stitle = "通過審核";
	}
	$Result =  $DB->query("update `{$INFO[DBPrefix]}kefu` set status=" . $status . " where kid=".intval($_GET['kid']));

	if ($Result)
	{
		if ($_GET['state'] == 0){
			$Query = $DB->query("select * from `{$INFO[DBPrefix]}kefu` where kid=".intval(trim($_GET['kid']))." limit 0,1");
			$Num   = $DB->num_rows($Query);

			if ($Num>0){
				$Result= $DB->fetch_array($Query);
				$serialnum            =  $Result['serialnum'];
				$type_chuli           =  $Result['type_chuli'];
				$type_chuli_name      =  $Result['type_chuli_name'];
				$username             =  $Result['username'];
				$realname             =  $Result['realname'];
				$email                =  $Result['email'];
				$postnum              =  $Result['postnum'];
				$title                =  $Result['title'];
				$lastdate             =  $Result['lastdate'];
				$status               =  $Result['status'];
				$k_kefu_con           =  $Result['k_kefu_con'];
				$order_serial           =  $Result['order_serial'];
				$marketno           =  $Result['marketno'];

				$p_Sql = "select * from `{$INFO[DBPrefix]}kefu_posts` where  k_post_id=".intval($k_post_id);
				$p_Query = $DB->query($p_Sql);
				$p_Result= $DB->fetch_array($p_Query);

			}
			$Array =  array("truename"=>trim($realname),"k_kefu_con"=>trim($k_kefu_con),"k_post_con"=>($p_Result['k_post_con']));
			$SMTP->MailForsmartshop(trim($email),"",17,$Array);
		}
		$FUNCTIONS->setLog("審核在線客服回覆");
		$FUNCTIONS->setKefuLog($_POST['kid'],"管理員" . $stitle,1);
		$url_locate = 'admin_kefu_list.php';
		$FUNCTIONS->header_location($url_locate);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

?>
