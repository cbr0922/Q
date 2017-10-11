<?php
include_once('../Classes/cart.class.php' );
session_start();
include("../configs.inc.php");
include("global.php");
include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";

@header("Content-type: text/html; charset=utf-8");
/**
 cart_LOGO的尺寸
*/
$tpl->assign("cart_logo_width",  $INFO["cart_logo_width"]);
$tpl->assign("cart_logo_height", $INFO["cart_logo_height"]);

if(!isset($_SESSION['cart']) || $_GET['key'] == "") {
	header("Location:shopping.php");
}

$cart =&$_SESSION['cart'];


if ($cart->get_key != $_GET['key']){
	/*
	$cart->get_key = intval($_GET['key']);
	$cart->tickets = array();
	$cart->bonus = array();
	$cart->transmoney = 0;
	*/
	$cart->resetCart();
}
$cart->get_key = $_GET['key'];

//$cart->setAllSaleOff($_GET['key']);

//購物車中商品

$items_array = $cart->getCart($_GET['key']);
//print_r($items_array);
if (!is_array($items_array) || count($items_array)<=0){
	header("Location:shopping.php");
}
$Cart_item = array();


foreach($items_array as $k => $v){
	if ($v['packgid']==0){
		$Cart_item[$i] = $v;
		$Cart_item[$i]['total'] = $v['count']*$v['price'];
		$i++;
	}
}

//print_r($Cart_item);
$cart->setTotal($_GET['key']);
$Cart_totalPrices = $cart->totalPrices;//商品網絡總計
$Cart_discount_totalPrices = $cart->discount_totalPrices;
$totalbonuspoint = $cart->setGroupbonuspoint($_GET['key']);
$Cart_bonus = $cart->bonus;//紅利



//折價券
$Sql = "select *,sum(ut.count) as count from `{$INFO[DBPrefix]}userticket` as ut inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid where ut.count>0 and ut.userid=".intval($_SESSION['user_id'])." and ( t.use_starttime<='" . date('Y-m-d',time()) . "' and t.use_endtime>='" . date('Y-m-d',time()) . "') and t.ordertotal<='" . $Cart_discount_totalPrices . "'  group by ut.ticketid";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
$i = 0;
if ($Num>0){
	while ($Rs = $DB->fetch_array($Query)) {
		$TicketList[$i]['tickeid'] = $Rs['ticketid'];
		$TicketList[$i]['id'] = $Rs['id'];
		$TicketList[$i]['count']   = intval($Rs['count']);
		$TicketList[$i]['money']   = intval($Rs['money']);
		$TicketList[$i]['ticketname']     = $Rs['ticketname'];
		$i++;
	}
}

//紅利折抵
$Query = $DB->query("select * from `{$INFO[DBPrefix]}bonus`");
$Result= $DB->fetch_array($Query);
$rebate  =  $Result['rebate'];//最多折抵金額百分比

//紅利

$point =$FUNCTIONS->Userpoint(intval($_SESSION['user_id']),1);
//红利判断
	if (intval($_SESSION['user_id'])>0 && intval($Cart_bonus['point']+$totalbonuspoint+$Cart_combipoint)>0){
		if ($point<intval($Cart_bonus['point']+$totalbonuspoint)){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您最多可以使用" . $point . "點積分');location.href='shopping.php?key=" . $_POST['key'] . "';</script>";exit;	
		}
	}else if (intval($_SESSION['user_id'])==0 && intval($Cart_bonus['point'])>0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您最多可以使用0點積分');location.href='shopping.php?key=" . $_POST['key'] . "';</script>";exit;	
	}


//最多可以使用的折抵紅利點數
$MaxbonusPoint = round($Cart_totalPrices * $rebate/100,0)>($point-$Cart_bonus['point']-$totalbonuspoint)?($point-$Cart_bonus['point']-$totalbonuspoint):round($Cart_totalPrices * $rebate/100,0);


$key_value = explode("_",$_GET['key']);
//額滿禮
$goods_sql = "select * from `{$INFO[DBPrefix]}goods` as g where g.ifpub='1' and g.ifjs!=1  and g.ifbonus!='1' and g.ifpresent=1 and g.storage>0 having g.present_endmoney>='" . $Cart_discount_totalPrices . "' and g.present_money<='" . $Cart_discount_totalPrices . "' and g.present_money<(" . $Cart_discount_totalPrices . "+1000)";
$goods_sql .= " and g.ttype='" . $key_value[2] . "' ";
	
if (intval($key_value[1])>0){
	$goods_sql .= " and g.provider_id=".intval($key_value[1]) . " and g.iftogether=0";	
}else{
	$goods_sql .= " and (g.provider_id=0 or g.iftogether=1)";		
}
 $goods_sql .= " order by present_money desc,g.idate desc";
$goods_Query    = $DB->query($goods_sql);
$j = 0;
while ($goods_Rs=$DB->fetch_array($goods_Query)) {
	$em_goods_array[$j]['goodsname'] = $goods_Rs['goodsname'];
	$em_goods_array[$j]['gid'] = $goods_Rs['gid'];
	$em_goods_array[$j]['present_money'] = $goods_Rs['present_money'];
	$em_goods_array[$j]['present_endmoney'] = $goods_Rs['present_endmoney'];
	if ($goods_Rs['present_money'] - $Cart_discount_totalPrices > 0)
		$em_goods_array[$j]['have_present_money'] = $goods_Rs['present_money'] - $Cart_discount_totalPrices;
	else 
		$em_goods_array[$j]['have_present_money'] = 0;
	$em_goods_array[$j]['smallimg'] = $goods_Rs['smallimg'];
	$j++;
}

if (intval($key_value[1])>0){
	$Sql_p      = "select * from `{$INFO[DBPrefix]}provider` where  provider_id=".intval($key_value[1]) . " order by provider_idate  ";
	$Query_p    = $DB->query($Sql_p);
	$Rs_p=$DB->fetch_array($Query_p);
	$provider_name = "廠商配送(" . $Rs_p['providerno'] .")". "[運費：" . $Rs_p['yunfei'] . "元，滿" . $Rs_p['mianyunfei'] . "元免運費]";
	$cart->transname = "廠商配送";
	if($Rs_p['mianyunfei']<=$Cart_discount_totalPrices)
		$cart->transmoney= 0;
	else
		$cart->transmoney= $Rs_p['yunfei'];
}
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign("provider_id",     $key_value[1]);
$tpl->assign("provider_name",     $provider_name);
$tpl->assign("Cart_combipoint",   $Cart_combipoint);
$tpl->assign("sumpoint",    intval($point)); 
$tpl->assign("em_goods_array",     $em_goods_array);
$tpl->assign("MaxbonusPoint",      $MaxbonusPoint);
$tpl->assign("TicketList",         $TicketList);
$tpl->assign("Cart_item",          $Cart_item);
$tpl->assign("Cart_totalPrices",   $Cart_totalPrices);
$tpl->assign("Cart_tickets",       $cart->tickets);
$tpl->assign("Cart_bonus",         $cart->bonus['point']);
$tpl->assign("sid",         $sid);
$tpl->assign("Cart_discount_totalPrices", $Cart_discount_totalPrices);
$tpl->assign("Gpicpath",         $INFO['good_pic_path']);
$tpl->assign("MinBuyMoney",         $INFO['MinBuyMoney']);
$tpl->assign("cart_usebonus",         $INFO['cart_usebonus']);
$tpl->assign("cart_useticket",         $INFO['cart_useticket']);
$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Basic_Command);
if (substr($_GET['key'],0,2)=="FB")
$tpl->display("shopping2_fb.html");
else
$tpl->display("shopping2.html");
?>