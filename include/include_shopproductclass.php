<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

$shopid = intval($_GET['sid']);

if ($shopid==0 && intval($_GET['goods_id'])>0){
	$Sql =   "select g.shopid  from `{$INFO[DBPrefix]}goods` g where g.gid=".intval($_GET['goods_id'])." ";
	$Query   = $DB->query($Sql);
	$Result_goods = $DB->fetch_array($Query);
	$shopid = $Result_goods['shopid'];
	$_GET['sid'] = $shopid;
}

$class_array = array();
$i = 0;
$class_Sql = "select * from `{$INFO[DBPrefix]}shopgoodsclass` where top_id=0 and shopid='" . $shopid . "'";
$class_Query  = $DB->query($class_Sql);
while($class_Rs =  $DB->fetch_array($class_Query)){
	$class_array[$i]['classname']	= $class_Rs['classname'];
	$class_array[$i]['sgcid']	= $class_Rs['sgcid'];
	$class_sub_Sql = "select * from `{$INFO[DBPrefix]}shopgoodsclass` where top_id='" . $class_Rs['sgcid'] . "' and shopid='" . $shopid . "'";
	$class_sub_Query  = $DB->query($class_sub_Sql);
	$j = 0;
	while($class_sub_Rs =  $DB->fetch_array($class_sub_Query)){
		$class_array[$i]['sub'][$j]['classname']	= $class_sub_Rs['classname'];
		$class_array[$i]['sub'][$j]['sgcid']	= $class_sub_Rs['sgcid'];
		$j++;
	}
	$i++;
}

$Query = $DB->query("select shoppic,view_num from `{$INFO[DBPrefix]}shopinfo` where sid=".intval($shopid)." and state=1 limit 0,1");
$Num   = $DB->num_rows($Query);
$Result= $DB->fetch_array($Query);
$shoppic     =  $Result['shoppic'];
$view_num     =  $Result['view_num'];


$NewsArray = array();
$Sql = "select g.* from `{$INFO[DBPrefix]}shopnews` g where shopid='" . $shopid . "' order by pubtime desc limit 0,10";
$Query = $DB->query($Sql);
$i = 0;
while ($Rs = $DB->fetch_array($Query)){
	$NewsArray[$i]['title'] = $Rs['title'];
	$NewsArray[$i]['pubtime'] = date("Y-m-d H:i:s",$Rs['pubtime']);
	$NewsArray[$i]['snid'] = $Rs['snid'];
	$i++;
}

$Sql = "select AVG((score1+score3+score2)/3) as total,AVG(score1) as score1,AVG(score2) as score2,AVG(score3) as score3,sum(score1+score3+score2) as allscore from `{$INFO[DBPrefix]}score` s where s.shopid='" . $shopid . "' ";
$Query    = $DB->query($Sql);
$Rs = $DB->fetch_array($Query);
$total = round($Rs['total'],2);
$score1 = round($Rs['score1'],2);
$score2 = round($Rs['score2'],2);
$score3 = round($Rs['score3'],2);
$allscore = $Rs['allscore'];

$d = date('d',time())-1;
$y = intval(date('Y',time()));
$m = intval(date('m',time()));
$overtime = gmmktime(0,0,0,$m,$d+1,$y);
$starttime = gmmktime(0,0,0,$m,$d,$y);
$Sql = "select count(*) as counts from `{$INFO[DBPrefix]}goods` where  shopid='" . $shopid . "'";
$Query    = $DB->query($Sql);
$Rs = $DB->fetch_array($Query);
$goodscount = $Rs['counts'];

$C_Sql = "select count(*) as counts from `{$INFO[DBPrefix]}shopcomment` as sc inner join `{$INFO[DBPrefix]}user` as u on sc.uid=u.user_id where sc.shopid='" . $sid . "' and sc.topid=0 limit 0,10";
$C_Query = $DB->query($C_Sql);
$Rs =  $DB->fetch_array($C_Query);
$commentcount = $Rs['counts'];

$TSql = "select count(*) as counts from `{$INFO[DBPrefix]}order_table` as ot where ot.shopid='" . $shopid . "'";
$Query    = $DB->query($TSql);
$Rs = $DB->fetch_array($Query);
$ordercount = $Rs['counts'];

$TSql = "select AVG(oa.actiontime-ot.createtime) as transday from `{$INFO[DBPrefix]}order_table` as ot inner join `{$INFO[DBPrefix]}order_action` as oa on ot.order_id=oa.order_id and oa.state_value=1 and oa.state_type=3  where ot.shopid='" . $shopid . "'";
$Query    = $DB->query($TSql);
$Rs = $DB->fetch_array($Query);
$transday = ceil($Rs['transday']/60/60/24);

$tpl->assign("view_num",       $view_num);
$tpl->assign("transday",       $transday);
$tpl->assign("ordercount",       $ordercount);
$tpl->assign("commentcount",       $commentcount);
$tpl->assign("goodscount",       $goodscount);
$tpl->assign("allscore",       $allscore);
$tpl->assign("SG_Array",       $SG_Array);
$tpl->assign("total",       $total);
$tpl->assign("score1",       $score1);
$tpl->assign("score2",       $score2);
$tpl->assign("score3",       $score3);

//print_r($class_array);
$tpl->assign("NewsArray", $NewsArray);
$tpl->assign("shopid",$shopid);
$tpl->assign("class_array",$class_array);
$tpl->assign("shoppic", $shoppic);
$tpl->display("include_shopproductclass.html");
?>
