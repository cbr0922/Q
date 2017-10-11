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

//$Sql      = "select * from `{$INFO[DBPrefix]}combipoint` where user_id=".intval($_SESSION['user_id'])." order by id desc";
$Sql      = "select bp.* from `{$INFO[DBPrefix]}grouppoint` as bp where bp.user_id=".intval($_SESSION['user_id'])." order by bp.id desc";

$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$Nav->total_result=$Num;
	$Nav->execute($Sql,10);
	$Query = $Nav->sql_result;
	$i=0;
	while ($Rs = $DB->fetch_array($Query)) {
		$OrderList[$i]['point'] = $Rs['point'];
		$OrderList[$i]['id'] = $Rs['id'];
		$OrderList[$i]['addtime']   = date("Y-m-d",$Rs['addtime']);
		$OrderList[$i]['endtime']   = date("Y-m-d",$Rs['endtime']);
		$OrderList[$i]['type']   = intval($Rs['type']);
		$OrderList[$i]['usestate']   = intval($Rs['usestate']);
		$OrderList[$i]['orderid']   = intval($Rs['orderid']);
		
		$OrderList[$i]['typename'] = $Rs['typename'];
		$OrderList[$i]['content'] = $Rs['content'];
				
		if ($Rs['endtime']<time())
				$OrderList[$i]['state'] = "[已過期]";
		switch (intval($Rs['usestate'])){
			case 0:
				$OrderList[$i]['usestatename'] = "未使用" . $OrderList[$i]['state'];
				break;
			case 1:
				$u_sql = "select * from `{$INFO[DBPrefix]}grouppointbuydetail` where grouppoint_id=" . $Rs['id'];
				$u_Query =  $DB->query($u_sql);
				$u_Rs = $DB->fetch_array($u_Query);
				$usepoint = intval($u_Rs['usepoint']);
				
				$OrderList[$i]['usestatename'] = "部份使用(" . $usepoint . "團購金)" . $OrderList[$i]['state'];
				break;
			case 2:
				$OrderList[$i]['usestatename'] = "已使用";
				break;
		}
		$OrderList[$i]['endtime']     = date("Y-m-d",$Rs['endtime']);
		$i++;
	}
	$Nav_banner = $Nav->pagenav();
}else{
	$Nav_banner = $Basic_Command['NullDate'] ; // "無相關資料！" ;
}

$point =$FUNCTIONS->Grouppoint(intval($_SESSION['user_id']),1);
$tpl->assign("sumpoint",    intval($point)); 

$tpl->assign("OrderList",        $OrderList);   // 订单内容列表 （数组）
$tpl->assign("Nav_banner",       $Nav_banner);  // 数据分页导航条
$tpl->assign("OrderTotalNum",    $Num);         // 数据总数

$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Order_Pack);
$tpl->assign($Good);
$tpl->display("grouppointrecord.html");
?>