<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Good.php";

//装载翻页函数
include ("pagenav_stard.php");
$objClass  = "p9v";
$Nav       = new buildNav($DB,$objClass);
include ("pagenav_ex.php");
$Build_nav = new NavFunction();
$point =$FUNCTIONS->Buypoint(intval($_SESSION['user_id']),1);

if($_POST['act']=="insert"){
	if(intval($_POST['point'])<=intval($point)){
		$iSql = "insert into `{$INFO[DBPrefix]}buypointrefund`(bank,bankcode,account,acountname,u_id,state,refundtime,point,remark,refundtype)values('" . $_POST['bank'] . "','" . $_POST['bankcode'] . "','" . $_POST['account'] . "','" . $_POST['accountname'] . "','" . intval($_SESSION['user_id']) . "',0,'" . time() . "','" . intval($_POST['point']) . "','',1)";	
		$DB->query($iSql);
		$FUNCTIONS->sorry_back("buypointrefund.php","您已經申請退款");
	}
}
$Sql      = "select * from `{$INFO[DBPrefix]}buypointrefund` where u_id='" . intval($_SESSION['user_id']) . "' order by br_id desc";


$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$Nav->total_result=$Num;
	$Nav->execute($Sql,10);
	$Query = $Nav->sql_result;
	$i=0;
	while ($Rs = $DB->fetch_array($Query)) {
		$OrderList[$i]['point'] = $Rs['point'];
		$OrderList[$i]['bank'] = $Rs['bank'];
		$OrderList[$i]['refundtime']   = date("Y-m-d",$Rs['refundtime']);
		$OrderList[$i]['bankcode']   = ($Rs['bankcode']);
		$OrderList[$i]['account'] = $Rs['account'];
		$OrderList[$i]['acountname'] = $Rs['acountname'];
		$OrderList[$i]['u_id'] = $Rs['u_id'];
		$OrderList[$i]['state'] = $Rs['state'];
		$OrderList[$i]['remark'] = $Rs['remark'];
		$i++;
	}
	$Nav_banner = $Nav->pagenav();
}else{
	$Nav_banner = $Basic_Command['NullDate'] ; // "無相關資料！" ;
}


$tpl->assign("sumpoint",    intval($point)); 

$tpl->assign("OrderList",        $OrderList);   // 订单内容列表 （数组）
$tpl->assign("Nav_banner",       $Nav_banner);  // 数据分页导航条
$tpl->assign("OrderTotalNum",    $Num);         // 数据总数

$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Order_Pack);
$tpl->assign($Good);
$tpl->display("buypointrefund.html");
?>
