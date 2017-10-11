<?php
//error_reporting(0);
session_start();
include("../configs.inc.php");
require_once RootDocument.'/Classes/cart_group.class.php';
include("global.php");
include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";

require "check_member.php";
@header("Content-type: text/html; charset=utf-8");

/**
 cart_LOGO的尺寸
*/
$tpl->assign("cart_logo_width",  $INFO["cart_logo_width"]);
$tpl->assign("cart_logo_height", $INFO["cart_logo_height"]);

if(!isset($_SESSION['cart_group'])) {
	header("Location:shopping_g.php");
}


$cart =&$_SESSION['cart_group'];
if ($_GET['key']==""){
	$_GET['key']=$cart->get_key;
}

//購物車中商品
$items_array = $cart->getCartGroup($_GET['key']);
if (!is_array($items_array) || count($items_array)<=0){
	header("Location:shopping_g.php");
}

$Cart_item = array();
$i = 0;
$ifmonth = 0;
$month = array(3,6);
$ismonth = array(3=>"0",6=>"0");
foreach($items_array as $k => $v){
	$Cart_item[$i] = $v;
	$Cart_item[$i]['price']       = $cart->setSaleoff($_GET['key'],$v['gkey']);
	if ($v['buytype']==0)
		$Cart_item[$i]['total'] = $v['count'] * $Cart_item[$i]['price'];
	if ($v['buytype']==1){
		$Cart_item[$i]['total'] = $v['count'] * $v['memberprice'];
		$Cart_item[$i]['totalpoint'] = $v['count'] * $Cart_item[$i]['grouppoint'];
	}
	$i++;
}

$cart->setTotal($_GET['key']);
$Cart_totalPrices = $cart->totalPrices;//商品網絡總計
$Cart_totalGrouppoint = $cart->totalGrouppoinit;
$Cart_buypoint = $cart->totalBuypoint;
$Cart_sys_trans_type = $cart->sys_trans_type;//自定義配送方式，配送公式
$Cart_sys_trans = $cart->sys_trans;//配送信息
$Cart_transmoney = $cart->transmoney;//配送配用

$Cart_nomal_trans_type = $cart->nomal_trans_type;//一般配送方式所選擇的配送方式


/**
 *  这里是获得宅配時間
 */

$Query  = $DB->query("select transtime_id,transtime_name from `{$INFO[DBPrefix]}transtime` order by transtime_id asc ");
$i=0;
while($Rs=$DB->fetch_array($Query)){
	$HomeTimeType[$i][transtime_id]              = $Rs['transtime_id'];
	$HomeTimeType[$i][transtime_name]            = $Rs['transtime_name'];
	$HomeTimeType[$i][transtime64encode_name]    = base64_encode($Rs['transtime_name']);
	$HomeTimeType[$i][transtime64decode_name]    = $Rs['transtime_name'];

	$i++;
}
$tpl->assign("HomeTimeType",  $HomeTimeType);

/**
金流
**/
$i = 0;
$i3 = 0;
$i6 = 0;
//echo $cart->transname_id;
if ($cart->transname_id!=13)
	$sub_sql = " and p.mid<>65";
else
	$sub_sql = " and p.mid=65";
$paySql = "select *,p.content as pcontent,p.month as pmonth from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pm on p.pid=pm.pid where p.ifopen=1 " . $sub_sql . " order by pm.paytype desc,p.mid";
$payQuery    = $DB->query($paySql);
$payNum      = $DB->num_rows($payQuery);
if($payNum>0){
	while($payRs = $DB->fetch_array($payQuery)){
		if ($payRs['pmonth']==0){
			if ($i == 0)
				$payArray[$i]['checked'] = 1;
			$payArray[$i]['methodname'] = $payRs['methodname'];
			$payArray[$i]['mid'] = $payRs['mid'];
			$payArray[$i]['pcontent'] = $payRs['pcontent'];
			$payArray[$i]['payname'] = $payRs['payname'];
			$payArray[$i]['month'] = $payRs['pmonth'];
			if($payRs['pmonth']>0)
				$payArray[$i]['price'] =  round(($Cart_discount_totalPrices+$Cart_transmoney)/$payRs['pmonth'],0);
			$i++;
		}
		if ($payRs['pmonth']==3 && $ismonth[3]==1){
			$payArray3[$i3]['methodname'] = $payRs['methodname'];
			$payArray3[$i3]['mid'] = $payRs['mid'];
			$payArray3[$i3]['pcontent'] = $payRs['pcontent'];
			$payArray3[$i3]['payname'] = $payRs['payname'];
			$payArray3[$i3]['month'] = $payRs['pmonth'];
			if($payRs['pmonth']>0)
				$payArray3[$i3]['price'] =  round(($Cart_discount_totalPrices+$Cart_transmoney)/$payRs['pmonth'],0);
			$i3++;
		}
		if ($payRs['pmonth']==6 && $ismonth[6]==1){
			$payArray6[$i6]['methodname'] = $payRs['methodname'];
			$payArray6[$i6]['mid'] = $payRs['mid'];
			$payArray6[$i6]['pcontent'] = $payRs['pcontent'];
			$payArray6[$i6]['payname'] = $payRs['payname'];
			$payArray6[$i6]['month'] = $payRs['pmonth'];
			if($payRs['pmonth']>0)
				$payArray6[$i6]['price'] =  round(($Cart_discount_totalPrices+$Cart_transmoney)/$payRs['pmonth'],0);
			$i6++;
		}
		
	}
}
//print_r($payArray);
$tpl->assign("payArray",  $payArray);
$tpl->assign("payArray3",  $payArray3);
$tpl->assign("payArray6",  $payArray6);
$tpl->assign("PleaseInputEmail",      $PleaseInputEmail); //请输入E-MAIL地址！
$tpl->assign("Nocarriage",            $Nocarriage);
$tpl->assign("PleaseInputTrueEmail",  $Cart[PleaseInputTrueEmail]); //必须输入有效的E-MAIL地址！
$tpl->assign("PleaseInputName",       $Cart[PleaseInputName]); //请输入收货人姓名
$tpl->assign("PleaseInputAddr",       $Cart[PleaseInputAddr]); //请输入收货人地址



/**
 * 这里是得到会员的基本资料
 */
if (intval($_SESSION['user_id'])>0){
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}user` where user_id=".intval($_SESSION['user_id'])." limit 0,1 ");
	$Num   =  $DB->num_rows($Query);
	if ( $Num > 0 ){
		$Rs  = $DB->fetch_array($Query);
		$email       = $Rs['email'];
		$tel         = $Rs['tel'];
		$post        = $Rs['post'];
		$city        = $Rs['city'];
		$canton      = $Rs['canton'];
		$Country      = $Rs['Country'];
		$addr        = $Rs['addr'];
		$true_name   = $Rs['true_name'];
		$other_tel   = $Rs['other_tel'];
		$mybuypoint = $FUNCTIONS->Buypoint(intval($Rs['user_id']));
	}
}
$tpl->assign("ismonth3",         $ismonth[3]);
$tpl->assign("ismonth6",         $ismonth[6]);
$tpl->assign("mybuypoint",         intval($mybuypoint));
$tpl->assign("email",         $email);
$tpl->assign("tel",           $tel);
$tpl->assign("city",          $city);
$tpl->assign("canton",        $canton);
$tpl->assign("Country",        $Country);
$tpl->assign("post",          $post);
$tpl->assign("addr",          $addr);
$tpl->assign("true_name",     $true_name);
$tpl->assign("ifmonth",     $ifmonth);
$tpl->assign("other_tel",     $other_tel);
$tpl->assign("Cart_combipoint",   $Cart_combipoint);
$tpl->assign("Cart_item",          $Cart_item);
$tpl->assign("saleoffinfo",     $cart->saleoffinfo);
$tpl->assign("Cart_totalPrices",   $Cart_totalPrices);
$tpl->assign("Cart_tickets",       $Cart_tickets);
$tpl->assign("Cart_buypoint",         $Cart_buypoint);
$tpl->assign("Cart_sys_trans_type",         $Cart_sys_trans_type);
$tpl->assign("Cart_sys_trans",         $Cart_sys_trans);
$tpl->assign("Cart_discount_totalPrices", $Cart_discount_totalPrices);
$tpl->assign("Cart_special_trans_name", $Cart_special_trans_name);
$tpl->assign("Cart_special_trans_type", $Cart_special_trans_type);
$tpl->assign("Cart_transmoney", $Cart_transmoney);
$tpl->assign("Cart_nomal_trans_type", $Cart_nomal_trans_type);
$tpl->assign("SendType",  $SendType);
$tpl->assign("Gpicpath",         $INFO['good_pic_path']);
$tpl->assign("IfNeed_invoice",       intval($INFO['Need_invoice']));  //系统中指定的是否需要发
$tpl->assign("transname",  $cart->transname);
$tpl->assign("transname_area",  urlencode($cart->transname_area));
$tpl->assign("transname_area2",  urlencode($cart->transname_area2));
$tpl->assign("Cart_totalGrouppoint",   $Cart_totalGrouppoint);
$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Basic_Command);
$tpl->display("shopping3_g.html");
?>
