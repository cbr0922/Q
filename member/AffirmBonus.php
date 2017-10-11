<?php
error_reporting(7);
session_start();
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include("global.php");
/**
 *  装载产品语言包
 */
include "../language/".$INFO['IS']."/Good.php";

$Abc_id       = $FUNCTIONS->Value_Manage($_GET['abc_id'],$_POST['abc_id'],'back','');  //判断是否有正常的ID进入

$Sql = " select isay,ssay,goodsname,idate,sidate,askfor from `{$INFO[DBPrefix]}bonuscoll_goods` where abc_id=".intval($Abc_id)." limit 0,1";
$Query  = $DB->query($Sql);

$BonusHistory = array();

$Rs = $DB->fetch_array($Query);

$tpl->assign("Isay",       nl2br($Rs['isay']));
$tpl->assign("Ssay",       nl2br($Rs['ssay']));
$tpl->assign("Goodsname",  $Rs['goodsname']);
$tpl->assign("Idate",      date("Y-m-d H:i a",$Rs['idate']));
if (trim($Rs['sidate'])!=""){
	$tpl->assign("Sdate",      date("Y-m-d H:i a",$Rs['sidate']));
}

switch ($Rs['askfor'])
{
	case 1:
		$BonusHistory[$i][askfor]  = $Good[HaveGetBonusShenQing_PleaseWaiting];//已成功提交需求红利商品申请！等待系统审核中.....!
		break;
	case 2:
		$BonusHistory[$i][askfor]  = $Good[SystemCancelBonusShengQing];//系统取消了本条需求红利商品申请！
		break;
	case 3:
		$BonusHistory[$i][askfor]  = $Good[SystemPassBonusShengQing];//系统已接受本条需求红利商品申请！
		break;
	default:
		$BonusHistory[$i][askfor]  = $Good[HaveGetBonusShenQing_PleaseWaiting];//已成功提交需求红利商品申请！等待系统审核中.....!
		break;
}


$tpl->assign("Askfor",       $Askfor);
$tpl->assign($Good);

$tpl->display("AffirmBonus.html");
?>

