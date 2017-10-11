<?php
include_once "Check_Admin.php";
$kefu_tem = intval($_GET['kefu_tem']);
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";
if ($kefu_tem==='none')
{
	$message =  $KeFu_Pack['Back_Please_SelectHF'];//"請您選擇一個模版!";

}else{

	$Sql_linshi = " select k_tem_con from `{$INFO[DBPrefix]}kefu_tem` where k_tem_id = ".$kefu_tem;
	$Query_linshi = $DB->query($Sql_linshi);
	while ($Rs_linshi = $DB->fetch_array($Query_linshi)){
		$kefu_tem_con = $Rs_linshi;
	}

	$message =  $kefu_tem_con['k_tem_con'];

}

echo $message;
?>