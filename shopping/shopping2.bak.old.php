<?php
require_once('../Classes/cart.class.php' );
session_start();
include("../configs.inc.php");
include("global.php");
include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";

require "check_member.php";
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
$sid = session_id(); 
if ($_GET['Action']=="Add"){
	$goods_id  = $FUNCTIONS->Value_Manage(intval($_GET['goods_id']),'','shopping.php','');
	$Query = $DB->query("select g.gid,g.goodsname,g.unit,g.provider_id,g.good_color,g.good_size,g.nocarriage,g.smallimg,g.price,g.pricedesc,g.point ,g.ifjs,g.js_begtime,g.js_endtime,g.storage,g.if_monthprice,g.ifpresent,g.trans_special_money,g.trans_special,g.iftransabroad,g.trans_type,g.present_money,g.iftogether,g.ifmood  from `{$INFO[DBPrefix]}goods` g where gid=".intval($goods_id)." limit 0,1 ");
	$Num   = $DB->num_rows($Query);
	if ($Num>0) {
		$goods_array = array();
		$Rs=$DB->fetch_array($Query);
			
			$goods_array['gid'] = $Rs['gid'];
			$goods_array['goodsname'] = $Rs['goodsname'];
			 if ($Rs['ifpresent']==1){
				 $goods_array['detail_name'] = "額滿禮";
				 $goods_array['detail_desc'] = "購買滿" . $goods_array['present_money'] . "元之贈品";
			 }
			$goods_array['storage'] = $Rs['storage'];
			$goods_array['org_price']  = $Rs['price'];
			$goods_array['smallimg'] = $Rs['smallimg'];
			$goods_array['count'] = 1;
			$goods_array['memberorprice'] = 1;
			$goods_array['ifpresent'] = $Rs['ifpresent'];
			$goods_array['present_money'] = $Rs['present_money'];
			$goods_array['iftogether'] = $Rs['iftogether'];
			$goods_array['ifmood'] = $Rs['ifmood'];
			//print_r($goods_array);exit;
			$cart->addItems($goods_array,$_GET['key']);
		
	}
	header("Location:shopping2.php?key=" . $_GET['key']);
}



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
$cart->setTotalbonuspoint();
$totalbonuspoint = $cart->totalbonuspoint;//紅利
//$cart->setAllSaleOff($_GET['key']);

//購物車中商品
$items_array = $cart->getCartGroup($_GET['key']);
//print_r($items_array);
if (!is_array($items_array) || count($items_array)<=0){
	header("Location:shopping.php");
}
$Cart_item = array();
$i = 0;


foreach($items_array as $k => $v){
	$Cart_item[$i] = $v;
	if ($v['ifbonus']==0 && $v['ifpresent']==0 && $v['Js_price']==0  && $v['ifchange']==0 && $v['nosaleoff']==0 && $v['ifadd']==0 && $v['ifds']==0 && $v['rebate']==0){
		//$MemberPiceReturn = $FUNCTIONS->MemberLevelPrice($_SESSION['user_level'],$v['gid'],$v['detail_id']);
		$Cart_item[$i]['price']       = $cart->setPrice($_GET['key'],$k);
		$cart->changeItems($_GET['key'],$v['gkey'],"price",intval($Cart_item[$i]['price']));
	}
	if ($Cart_item[$i]['memberorprice'] == 2){
		$Cart_item[$i]['total'] = $v['count'] * $v['memberprice'];
		$Cart_item[$i]['totalcombi'] = $v['count'] * $v['combipoint'];
	}else{
		//$Cart_item[$i]['price']       = $cart->setSaleoff($_GET['key'],$v['gkey']);
		$Cart_item[$i]['total'] = $v['count'] * $Cart_item[$i]['price'];
	}
	
	
	$i++;
}
//print_r($Cart_item);
$cart->setTotal($_GET['key']);
$Cart_totalPrices = $cart->totalPrices;//商品網絡總計
$cart->getdiscount($_GET['key']);
$Cart_discount_totalPrices = $cart->discount_totalPrices;//優惠金額
$Cart_combipoint = $cart->combipoint;//優惠金額
$Cart_tickets = $cart->tickets;//優惠卷
$Cart_bonus = $cart->bonus['point'];//紅利
$key_value = explode("P",$_GET['key']);



//折價券
$Sql = "select *,sum(ut.count) as count from `{$INFO[DBPrefix]}userticket` as ut inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid where ut.count>0 and ut.userid=".intval($_SESSION['user_id'])." and t.use_starttime<='" . date('Y-m-d',time()) . "' and t.use_endtime>='" . date('Y-m-d',time()) . "' group by ut.ticketid";
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


//最多可以使用的折抵紅利點數
$MaxbonusPoint = round($Cart_totalPrices * $rebate/100,0)>$point?$point:round($Cart_totalPrices * $rebate/100,0);



//額滿禮
$goods_sql = "select * from `{$INFO[DBPrefix]}goods` as g where g.ifpub='1' and g.ifjs!=1  and g.ifbonus!='1' and g.ifpresent=1 and g.storage>0 having g.present_endmoney>='" . $Cart_discount_totalPrices . "' and g.present_money<='" . $Cart_discount_totalPrices . "' and g.present_money<(" . $Cart_discount_totalPrices . "+1000)";
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
	$provider_name = $Rs_p['provider_name'] . "[運費：" . $Rs_p['yunfei'] . "元，滿" . $Rs_p['mianyunfei'] . "元免運費]";
}

$tpl->assign("provider_id",     $key_value[1]);
$tpl->assign("provider_name",     $provider_name);
$tpl->assign("saleoffinfo",     $cart->saleoffinfo);
$tpl->assign("Cart_combipoint",   $Cart_combipoint);
$tpl->assign("sumpoint",    intval($point)); 
$tpl->assign("em_goods_array",     $em_goods_array);
$tpl->assign("MaxbonusPoint",      $MaxbonusPoint);
$tpl->assign("TicketList",         $TicketList);
$tpl->assign("Cart_item",          $Cart_item);
$tpl->assign("Cart_totalPrices",   $Cart_totalPrices);
$tpl->assign("Cart_tickets",       $Cart_tickets);
$tpl->assign("Cart_bonus",         $Cart_bonus);
$tpl->assign("sid",         $sid);
$tpl->assign("Cart_discount_totalPrices", $Cart_discount_totalPrices);
$tpl->assign("Gpicpath",         $INFO['good_pic_path']);
$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Basic_Command);
if (substr($_GET['key'],0,2)=="FB")
$tpl->display("shopping2_fb.html");
else
$tpl->display("shopping2.html");
?>
