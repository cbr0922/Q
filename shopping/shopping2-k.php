<?php

//error_reporting(0);

require_once 'cart.class.php';

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



if ($_GET['Action']=="Add"){

	$goods_id  = $FUNCTIONS->Value_Manage(intval($_GET['goods_id']),'','shopping.php','');

	$Query = $DB->query("select g.gid,g.goodsname,g.unit,g.provider_id,g.good_color,g.good_size,g.nocarriage,g.smallimg,g.price,g.pricedesc,g.point ,g.ifjs,g.js_begtime,g.js_endtime,g.storage,g.if_monthprice,g.ifpresent,g.trans_special_money,g.trans_special,g.iftransabroad,g.trans_type,g.present_money,g.ifmood  from `{$INFO[DBPrefix]}goods` g where gid=".intval($goods_id)." limit 0,1 ");

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

			$goods_array['ifmood'] = $Rs['ifmood'];

			$goods_array['smallimg'] = $Rs['smallimg'];

			$goods_array['count'] = 1;

			$goods_array['ifpresent'] = $Rs['ifpresent'];

			$goods_array['present_money'] = $Rs['present_money'];

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

//運費

$sys_trans['PayFreetrans'] = $INFO[PayFreetrans];

$sys_trans['PayStartprice'] = $INFO[PayStartprice];

$sys_trans['PayEndprice'] = $INFO[PayEndprice];

$cart->setIniTrans($INFO['Paytype'],$sys_trans);

//購物車中商品

$items_array = $cart->getCartGroup($_GET['key']);

if (!is_array($items_array) || count($items_array)<=0){

	header("Location:shopping.php");

}

$Cart_item = array();

$i = 0;

//print_r($items_array);

$ifarea = 1;

foreach($items_array as $k => $v){

	$Cart_item[$i] = $v;

	if ($v['ifbonus']==0 && $v['ifpresent']==0 && $v['Js_price']==0 && $v['iftimesale']==0 && $v['ifchange']==0 && $v['ifsale']==0 && $v['ifadd']==0){

		$MemberPiceReturn = $FUNCTIONS->MemberLevelPrice($_SESSION['user_level'],$v['gid'],$v['detail_id']);

		$Cart_item[$i]['price']       = $MemberPiceReturn>0 ? $MemberPiceReturn : $v['price'] ;

		$cart->changeItems($_GET['key'],$v['gkey'],"price",intval($Cart_item[$i]['price']));

	}

	$Cart_item[$i]['price']       = $cart->setSaleoff($_GET['key'],$v['gkey']);

	$Cart_item[$i]['total'] = $v['count'] * $Cart_item[$i]['price'];

	if ($Cart_item[$i]['iftransabroad']==0){

		$ifarea = 0;	

	}

	$i++;

}



$cart->setTotal($_GET['key']);

$Cart_totalPrices = $cart->totalPrices;//商品網絡總計

$Cart_discount_totalPrices = $cart->discount_totalPrices;//優惠金額

$Cart_tickets = $cart->tickets;//優惠卷

$Cart_bonus = $cart->bonus['point'];//紅利

$Cart_sys_trans_type = $cart->sys_trans_type;//自定義配送方式，配送公式

$Cart_sys_trans = $cart->sys_trans;//配送信息





if($_GET['key']=="T1"){

	$man_trans_type	 =2 ;//中型

	//$cart->getTransType($_GET['key']);

}else if($_GET['key']=="T2"){

	$man_trans_type	 =3 ;//大型

	$cart->transname = "請洽詢客服";

}else if ($_GET['key'] == 0){

	$man_trans_type	 =0 ;

}elseif (intval($_GET['key']) > 0){

	$man_trans_type	 =1 ;//是否是特殊配送方式

} 

$ifabroad = 0;

if ($_GET['op'] == "area"){

	//更改海外運費



	$trans_sql = "select * from `{$INFO[DBPrefix]}areatrans` at inner join `{$INFO[DBPrefix]}transgroup` as tg on at.group_id=tg.group_id left join `{$INFO[DBPrefix]}area` as a on at.area_id=a.area_id where a.areaname='" . $_GET['transarea'] . "'";

	$trans_Query    = $DB->query($trans_sql);

	$trans_Num      = $DB->num_rows($trans_Query);

	if ($trans_Num>0){

		if ($ifarea ==0 && $_GET['transarea'] != "台灣"){

			echo "<script>alert('不支持海外運送');</script>";

			$_GET['transarea'] = "台灣";

		}else{

			$trans_Rs=$DB->fetch_array($trans_Query);

			$free = $trans_Rs['money'];

			$cart->transname    =  "海外運送（" . $_GET['transarea'] . "）";

			$cart->setabroad($man_trans_type,$_GET['key'],$free);//設置運費

			$ifareatrans = 1;

			

		}

	}

	if ($_GET['transarea'] != "台灣"){

		$ifabroad = 1;	

	}

}else{

	$_GET['transarea'] = $cart->transname_area;

}

if ($_GET['transarea']!="")

	$cart->transname_area = $_GET['transarea'];



if ($_GET['op'] != "trans" && ($_GET['transarea'] == "台灣" || $_GET['transarea'] == "")){

	$cart->setTransMoney($man_trans_type,$_GET['key'],0,$cart->transmoney,$ifabroad);//設置運費

	$Cart_sys_trans_type = $cart->sys_trans_type;//自定義配送方式，配送公式

}

$Cart_special_trans_type = $cart->special_trans_type;//是否是特殊配送方式



//配送方式

if ($Cart_special_trans_type == 1 && $ifareatrans!=1){

	$Sql      = "select * from `{$INFO[DBPrefix]}transportation_special` where trid='" . intval($_GET['key']) . "' order by trid ";

	$Query    = $DB->query($Sql);

	$Num      = $DB->num_rows($Query);	

	if ($Num>0){

		$Rs=$DB->fetch_array($Query);

		$Cart_special_trans_name = $Rs['name'];

		$cart->transname_content = $Rs['content'];

	}	

}

//print_r($Cart_sys_trans);





//print_r($Cart_item);



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



$Sql = "SELECT  member_point FROM `{$INFO[DBPrefix]}user`  where user_id='".intval($_SESSION['user_id'])."' limit 0,1";

$Query  = $DB->query($Sql);

$Rs=$DB->fetch_array($Query);

$member_point = $Rs[member_point];  //紅利點數



//最多可以使用的折抵紅利點數

$MaxbonusPoint = round($Cart_totalPrices * $rebate/100,0)>$member_point?$member_point:round($Cart_totalPrices * $rebate/100,0);



//一般配送的配送方式

if (($_GET['transarea'] == "台灣") && $cart->ifnotrans == 0 &&$Cart_special_trans_type == 0 && $man_trans_type !=3){



	$Query  = $DB->query("select transport_id,transport_name,transport_price,transport_content from `{$INFO[DBPrefix]}transportation` order by transport_id asc ");

	$i=0;

	$Cart_nomal_trans_type = $cart->nomal_trans_type;//一般配送方式所選擇的配送方式

	while($Rs=$DB->fetch_array($Query)){

		if ($i==0 && $_GET['op'] != "trans" && ($_GET['op'] != "area" || $ifarea==0 || $_GET['transarea'] == "台灣" || $_GET['transarea'] == "" ) && $_GET['key']!="T1" && $_GET['key'] != "T2"){

			$cart->setTransMoney($man_trans_type,($_GET['key']),$Rs['transport_id'],intval($Rs['transport_price']));

			$cart->transname = $Rs['transport_name'];

			$cart->transname_content = $Rs['transport_content'];

			$cart->transname_id = $Rs['transport_id'];

		}

		$SendType[$i][transport_id]      = $Rs['transport_id'];

		$SendType[$i][transport_name]    = $Rs['transport_name'];

		$SendType[$i][transport_price]   =intval($Rs['transport_price']);

		$SendType[$i][transport_content] = $Rs['transport_content'];

		//$SendType[$i][ceils] = ceil(($Rs['transport_price']+$_GET['t'])*1.05);

		$i++;

	}

}



//額滿禮

$goods_sql = "select * from `{$INFO[DBPrefix]}goods` as g where g.ifpub='1' and g.ifjs!=1  and g.ifbonus!='1' and g.ifpresent=1 and g.storage>0 having g.present_endmoney>='" . $Cart_discount_totalPrices . "' and g.present_money<='" . $Cart_discount_totalPrices . "' and g.present_money<(" . $Cart_discount_totalPrices . "+1000)";

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





$Cart_transmoney = $cart->transmoney;//配送配用

$Cart_nomal_trans_type = $cart->nomal_trans_type;//一般配送方式所選擇的配送方式



//紅利

if (intval($_SESSION['user_id'])>0){

	$point_Sql = "select member_point from `{$INFO[DBPrefix]}user` where user_id='" . intval($_SESSION['user_id']) . "'";

	$point_Query    = $DB->query($point_Sql);

	$point_Rs = $DB->fetch_array($point_Query);

	//echo $point_Rs['member_point'];

	$point = $point_Rs['member_point']-intval($Cart_bonus)-intval($totalbonuspoint);

	

}



//配送地區

$Sql      = "select * from `{$INFO[DBPrefix]}area` where top_id=0";



$Query    = $DB->query($Sql);

$Num      = $DB->num_rows($Query);

$i = 0;

while($Rs=$DB->fetch_array($Query)){

	$area_array[$i]['areaname'] = $Rs['areaname'];

	$i++;

}

$tpl->assign("saleoffinfo",     $cart->saleoffinfo);

$tpl->assign("area_array",     $area_array);

$tpl->assign("ifareatrans",     $ifareatrans);

$tpl->assign("transname",  $cart->transname);

$tpl->assign("sumpoint",    intval($point)); 

$tpl->assign("em_goods_array",     $em_goods_array);

$tpl->assign("MaxbonusPoint",      $MaxbonusPoint);

$tpl->assign("TicketList",         $TicketList);

$tpl->assign("Cart_item",          $Cart_item);

$tpl->assign("Cart_totalPrices",   $Cart_totalPrices);

$tpl->assign("Cart_tickets",       $Cart_tickets);

$tpl->assign("Cart_bonus",         $Cart_bonus);

$tpl->assign("Cart_sys_trans_type",         $Cart_sys_trans_type);

$tpl->assign("Cart_sys_trans",         $Cart_sys_trans);

$tpl->assign("Cart_discount_totalPrices", $Cart_discount_totalPrices);

$tpl->assign("Cart_special_trans_name", $Cart_special_trans_name);

$tpl->assign("Cart_special_trans_type", $Cart_special_trans_type);

$tpl->assign("Cart_transmoney", $Cart_transmoney);

$tpl->assign("Cart_nomal_trans_type", $Cart_nomal_trans_type);

$tpl->assign("SendType",  $SendType);

$tpl->assign("Gpicpath",         $INFO['good_pic_path']);

$tpl->assign($Cart);

$tpl->assign($Good);

$tpl->assign($Basic_Command);



$tpl->display("shopping2.html");

?>