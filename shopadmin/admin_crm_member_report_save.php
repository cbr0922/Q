<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

$json='{"act":"save","mgroup_name":"","user_level":"0","Year":"1954","Month":"1","Sex":"0","checkArea":"1","county":"u53f0u7063","province":"u81fau5317u5e02","city":"","company":"3","dianzibao":"0","begtime":"","endtime":"","order_begtime":"","order_endtime":"","minmoney":"","maxmoney":""}';
$arr_json = json_decode($json,TRUE);
$filename="註冊會員統計_".date("Y-m-d",time());
$arr_json['mgroup_name']=$filename;

$searchlist = json_encode($arr_json);
$db_string = $DB->compile_db_insert_string( array (
	'mgroup_name'                 => trim($filename),
	'auto'                => 0,
	'searchlist'           => trim($searchlist),
)      );
$Sql="INSERT INTO `{$INFO[DBPrefix]}mail_group` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
$Result_Insert=$DB->query($Sql);
$group_id = mysql_insert_id();
	
if ( ($_POST['post_goods_starttime'] != "") && ($_POST['post_goods_endtime'] != "")  ){
	//$Date_string = " companyid ='".intval($_POST['post_companyid'])."' ";
	$Date_string = " reg_date BETWEEN '".$_POST['post_goods_starttime']."' AND '".$_POST['post_goods_endtime']."'";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Date_string);
}
if ( ($_POST['post_age_start'] != "") && ($_POST['post_age_end'] != "")  ){
	//$Date_string = " companyid ='".intval($_POST['post_companyid'])."' ";
	$Age_string = " year(born_date) BETWEEN '".$_POST['post_age_end']."' AND '".$_POST['post_age_start']."'";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Age_string);
	//		" year(born_date) BETWEEN '".($time[year] - $age_end)."' AND '".($time[year] - $age_start)."'";
}
if (intval($_POST['post_companyid']) > 0){
	$Date_string = " companyid ='".intval($_POST['post_companyid'])."' ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Date_string);
}
if ($_POST['post_month'] != "0" && isset($_POST['post_month'])){
	$Month_string = " MONTH(born_date) ='".intval($_POST['post_month'])."' ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Month_string);
}
if ($_POST['post_gender']!="" && $_POST['post_gender']!="none"){
	if( $_POST['post_gender'] == "male"){
		$Sex_string = " sex='".intval("0")."' ";
	}
	else if( $_POST['post_gender'] == "female"){
		$Sex_string = " sex='".intval("1")."' ";
	}

	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Sex_string);
}
if ($_POST['post_ifallarea']!="1"){
	if($_POST['post_county']!="" && $_POST['post_county']!="請選擇"){
		$Area_string = "   Country='".trim($_POST['post_county'])."'  ";
		$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Area_string);
	}
	if($_POST['post_province']!="" && $_POST['post_province']!="請選擇"){
		$Area_string = "   canton='".trim($_POST['post_province'])."'  ";
		$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Area_string);
	}
	if($_POST['post_city']!="" && $_POST['post_city']!="請選擇"){
		$Area_string = "   city='".trim($_POST['post_city'])."'  ";
		$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Area_string);
	}
}
if ($_POST['post_dianzibao']!="" && $_POST['post_dianzibao']!="all" && $_POST['post_dianzibao']!="none"){
	$Dianzibao_string = " dianzibao='".intval($_POST['post_dianzibao'])."' ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Dianzibao_string);
}

if ($_POST['post_type_level']!= 0 ){
	$Level_string = " user_level='".intval($_POST['post_type_level'])."' ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Level_string);
}

$Sql      = "select u.* ,l.level_name from `{$INFO[DBPrefix]}user` u  left join `{$INFO[DBPrefix]}user_level` l on (u.user_level = l.level_id) ".$Create_Sql." order by u.user_id desc";
$Query    = $DB->query($Sql);
while ($Rs=$DB->fetch_array($Query)) {
	$Sql="INSERT INTO `{$INFO[DBPrefix]}mail_group_list` (group_id,user_id,email) VALUES ('" . $group_id . "','" . $Rs['user_id'] . "','" . $Rs['email'] . "')";
	$DB->query($Sql);
}
$FUNCTIONS->setLog("新建郵件組");	
echo "<script language=javascript>alert('郵件組已保存!');window.close();</script>";
exit;
?>