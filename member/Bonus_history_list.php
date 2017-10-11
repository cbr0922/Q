<?php
error_reporting(7);
session_start();
@header("Content-type: text/html; charset=utf-8");
echo "<meta http-equiv=\"refresh\" content=\"60\">";

include("../configs.inc.php");
include("global.php");
/**
 *  装载产品语言包
 */
include "../language/".$INFO['IS']."/Good.php";

$Sql = " select abc_id,goodsname,idate,askfor,goods_id from `{$INFO[DBPrefix]}bonuscoll_goods`  where user_id='".$_SESSION['user_id']."' order by idate desc ";
$Query  = $DB->query($Sql);
$BonusHistory = array();
$i=0;
$j=1;
while ($Rs = $DB->fetch_array($Query)){
	$BonusHistory[$i][j]            = $j;
	$BonusHistory[$i][abc_id]       = $Rs['abc_id'];
	$BonusHistory[$i][goodsname]    = $Rs['goodsname'];
	$BonusHistory[$i][idate]        = date("y-m-d",$Rs['idate']);

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

	$BonusHistory[$i][goods_id]     = $Rs['goods_id'];

	$i++;
	$j++;
}

$tpl->assign("BonusHistory",  $BonusHistory);   //数据组
$tpl->assign($Good);
$tpl->assign($Basic_Command);
$tpl->display("Bonus_history_list.html");
?>

