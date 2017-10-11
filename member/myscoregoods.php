<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
include "../language/".$INFO['IS']."/Order_Pack.php";

include_once Classes . "/orderClass.php";
$orderClass = new orderClass;

if ($_POST['act']=="save"){
	$order_id = intval($_POST['order_id']);

	$Sql   =  " select * from `{$INFO[DBPrefix]}order_table` where user_id=".$_SESSION['user_id']." and order_id='" . $order_id . "' and order_state=4 order by createtime desc ";
	$Query =  $DB->query($Sql);
	$Num   =  $DB->num_rows($Query);
	if ($Num>0){
		$Rs = $DB->fetch_array($Query);
		$Query_detail = $DB->query(" select g.* from `{$INFO[DBPrefix]}order_detail` as g where g.order_id=".intval($order_id)." and g.gid=".intval($_POST['goods_id'])." order by g.order_detail_id desc ");
		$i = 0 ;
		while ($Rs_detail = $DB->fetch_array($Query_detail)){
			$s_Sql   =  " select * from `{$INFO[DBPrefix]}score` where user_id=".$_SESSION['user_id']." and order_id='" . $order_id . "' and gid='" . $Rs_detail['gid'] . "' ";
			$s_Query =  $DB->query($s_Sql);
			$s_Num   =  $DB->num_rows($s_Query);
			if ($s_Num<=0){
				$db_string = $DB->compile_db_insert_string( array (
				'score1'          => intval($_POST['score']),
				'order_id'          => $order_id,
				'user_id'          => $_SESSION['user_id'],
				'scoretime'          => time(),
				'order_id'          => $Rs['order_id'],
				'gid'          => $Rs_detail['gid'],
				'content'          => $_POST['content'],
				)      );
				$Sql="INSERT INTO `{$INFO[DBPrefix]}score` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
				$Result_Insert=$DB->query($Sql);
				if (intval($INFO['commnetPoint'])>0){
					$FUNCTIONS->AddBonuspoint($_SESSION['user_id'],intval($INFO['commnetPoint']),10,"評論商品贈點",1,0);
				}
			}
		}
	}
	$FUNCTIONS->sorry_back("myscore.php","");
}


$Sql   =  " select od.*,s.score1,s.content,s.answer,s.scoretime,ot.*,g.smallimg,s.score_id,g.intro from `{$INFO[DBPrefix]}order_detail`  as od inner join `{$INFO[DBPrefix]}order_table` as ot on od.order_id=ot.order_id inner join `{$INFO[DBPrefix]}goods` as g on g.gid=od.gid left join `{$INFO[DBPrefix]}score` as s on (s.gid=od.gid and s.order_id=od.order_id) where ot.user_id=".$_SESSION['user_id']." and od.gid='" . $_GET['goods_id'] . "' and ot.order_id='" . $_GET['order_id'] . "' order by ot.createtime desc ";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
if ($Num>0){
	$Rs = $DB->fetch_array($Query);
		$order_serial = $Rs['order_serial'];
		$goodsname = $Rs['goodsname'];
		$answer = $Rs['answer'];
		$price = intval($Rs['price']);
		$intro = $Rs['intro'];
		$pay_state = $orderClass->getOrderState($Rs['pay_state'],2);
		$transport_state = $orderClass->getOrderState($Rs['transport_state'],3);
		$createtime   = date("Y-m-d",$Rs['createtime']);
		$scoretime   = date("Y-m-d",$Rs['scoretime']);
		$order_state  = $orderClass->getOrderState($Rs['order_state'],1);
		$order_id     = $Rs['order_id'];
		$gid     = $Rs['gid'];
		//$score1     = $Rs['score1'];
		if ($Rs['score1']>0)
			$score1     = str_repeat("<i class='fa fa-star'></i>",$Rs['score1']);
		if ($Rs['score1']<5)
			$score2     = str_repeat("<i class='fa fa-star-o'></i>",5-$Rs['score1']);
		$content     = $Rs['content'];
		$score_id = intval($Rs['score_id']);
		$ifcanscore = $Rs['order_state']==4?1:0;
		$bn = $Rs['bn'];
		$smallimg = $Rs['smallimg'];
}



$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign("order_serial",        $order_serial);   // 订单内容列表 （数组）
$tpl->assign("goodsname",        $goodsname);
$tpl->assign("scoretime",        $scoretime);
$tpl->assign("username",        $_SESSION['username']);
$tpl->assign("intro",        $intro);
$tpl->assign("answer",        $answer);
$tpl->assign("price",        $price);
$tpl->assign("pay_state",        $pay_state);
$tpl->assign("transport_state",        $transport_state);
$tpl->assign("createtime",        $createtime);
$tpl->assign("order_state",        $order_state);
$tpl->assign("order_id",        $order_id);
$tpl->assign("gid",        $gid);
$tpl->assign("score1",        $score1);
$tpl->assign("score2",        $score2);
$tpl->assign("content",        $content);
$tpl->assign("score_id",        $score_id);
$tpl->assign("ifcanscore",        $ifcanscore);
$tpl->assign("smallimg",        $smallimg);
$tpl->assign("bn",        $bn);
$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Order_Pack);
$tpl->display("myscoregoods.html");

?>