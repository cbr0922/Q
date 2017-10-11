<?php
session_start();
include("../configs.inc.php");
require_once RootDocument.'/Classes/cart_group.class.php';
include("global.php");
@header("Content-type: text/html; charset=utf-8");
if(!isset($_SESSION['cart_group']) || $_GET['key'] == "") {
	exit;
}

$cart =&$_SESSION['cart_group'];

if ($cart->get_key != $_GET['key']){
	$cart->resetCart();
}
$cart->get_key = $_GET['key'];
//購物車中商品
$items_array = $cart->getCartGroup($_GET['key']);
//print_r($items_array);
if (!is_array($items_array) || count($items_array)<=0){
	exit;
}

$county = urldecode($_GET['county']);
$province = urldecode($_GET['province']);
$ishave = 0;
$trans_sql = "select * from `{$INFO[DBPrefix]}area` at where at.areaname='" . $county . "' and at.top_id=0";
$trans_Query    = $DB->query($trans_sql);
$trans_Num      = $DB->num_rows($trans_Query);	
if ($trans_Num>0){
	$trans_Rs=$DB->fetch_array($trans_Query);
	$county_id = $trans_Rs['area_id'];
	$county_sql = "select * from `{$INFO[DBPrefix]}area` at where at.top_id='" . $county_id . "'";
	$county_Query    = $DB->query($county_sql);
	$county_Num      = $DB->num_rows($county_Query);
	$province_sql = "select * from `{$INFO[DBPrefix]}area` at where at.areaname='" . $province . "' and  at.top_id='" . $county_id . "'";
	$province_Query    = $DB->query($province_sql);
	$province_Num      = $DB->num_rows($province_Query);
	$province_Rs=$DB->fetch_array($province_Query);
	$province_id = $province_Rs['area_id'];
	if ($county_Num>0 && $province_Num>0){
		$ishave = 1;
	}elseif($county_Num==0){
		$ishave = 1;
	}
}
$Cart_item = array();
//是否允許海外運送
$ifarea = 1;	
$cart->transname_area=$county;
$cart->transname_area2=$province;
//運費
$sys_trans['PayFreetrans'] = $INFO[Group_PayFreetrans];
$cart->setIniTrans(0,$sys_trans);
$cart->setTotal($_GET['key']);
$Cart_totalPrices = $cart->totalPrices;//商品網絡總計
$Cart_totalGrouppoint = $cart->totalGrouppoinit;
$Cart_sys_trans_type = $cart->sys_trans_type;//自定義配送方式，配送公式
$Cart_sys_trans = $cart->sys_trans;//配送信息
	//配送方式，如果配送方式沒設地區，就是所有地區都能使用，費用是配送方式的費用，否則費用是地區運費中設置的
	$group_sql = "select * from `{$INFO[DBPrefix]}areatrans` where area_id='" . $county_id . "' or area_id='" . $province_id . "'";
	$group_Query    = $DB->query($group_sql);
	$group_array = array();
	$j = 0;
	while($group_Rs=$DB->fetch_array($group_Query)){
		$group_array[$j] = $group_Rs['group_id'];
		$j++;
	}
	if($j>0)
		$subSql = "  or group_id in (" . implode(",",$group_array) . "))";
	else
		$subSql = ") ";
	 $trasport_sql = "select * from `{$INFO[DBPrefix]}transportation` as t left join `{$INFO[DBPrefix]}transgroup` as tg on t.transport_id=tg.trans_id  where  t.type like'%1%' and (group_id is null  " . $subSql . $subsql1;
	$Query  = $DB->query($trasport_sql);
	$i=0;
	while($Rs=$DB->fetch_array($Query)){
		if (($i==0 && intval($_GET['transid'])<=0) || (intval($_GET['transid'])>0 && intval($_GET['transid'])==$Rs['transport_id'])){
			$man_nomal_type = $Rs['transport_id'];
			$cart->transname_id = $Rs['transport_id'];
			if (intval($Rs['group_id'])==0)
				$trans_money = $Rs['transport_price'];
			else
				$trans_money = $Rs['money'];
			$trans_permoney = intval($Rs['permoney']);	
			
			$cart->transname = $Rs['transport_name'];
			$cart->transname_content = $Rs['transport_content'];
			if ($Rs['transport_id']==15){
					$Sql_s      = "select * from `{$INFO[DBPrefix]}store` where store_id = '" . intval($_GET['store_id']) . "' limit 0,1";
					$Query_s    = $DB->query($Sql_s);
					$Num_s      = $DB->num_rows($Query_s);
					$Rs_s=$DB->fetch_array($Query_s);
					$store_array['id'] = $Rs_s['store_id'];
					$store_array['name'] = $Rs_s['store_name'];
					$store_array['code'] = $Rs_s['store_code'];
					$store_array['province'] = $Rs_s['province'];
					$store_array['city'] = $Rs_s['city'];
					$store_array['address'] = $Rs_s['address'];
					$store_array['tel'] = $Rs_s['tel'];
					$store_array['map'] = $Rs_s['map'];
					$cart->transname .= "[門市：" . $Rs_s['store_name'] . "]";
					$cart->setStore($_GET['key'],$store_array);
			}else{
				$cart->setStore($_GET['key'],array());	
			}
		}
		$SendType[$i][transport_id]      = $Rs['transport_id'];
		$SendType[$i][transport_name]    = $Rs['transport_name'];
		$SendType[$i][transport_price]   =intval($Rs['transport_price']);
		$SendType[$i][money]   =intval($Rs['money']);
		$SendType[$i][permoney]   =intval($Rs['permoney']);
		$SendType[$i][transport_content] = $Rs['transport_content'];
		$i++;
	}
	//print_r($SendType);
$cart->setTransMoney($man_trans_type,$_GET['key'],intval($man_nomal_type),intval($trans_money),intval($trans_permoney),$ifarea);
$Cart_transmoney = $cart->transmoney;//配送配用
$Cart_nomal_trans_type = $cart->nomal_trans_type;

$tpl->assign("SendType",  $SendType);
$tpl->assign("man_trans_type",     $man_trans_type);
$tpl->assign("Cart_item",          $Cart_item);
$tpl->assign("Cart_combipoint",   $Cart_combipoint);
$tpl->assign("Cart_totalGrouppoint",   $Cart_totalGrouppoint);
$tpl->assign("transname",  $cart->transname);
$tpl->assign("sumpoint",    intval($point)); 
$tpl->assign("Cart_totalPrices",   $Cart_totalPrices);
$tpl->assign("Cart_bonus",         $Cart_bonus);
$tpl->assign("Cart_sys_trans_type",         $Cart_sys_trans_type);
$tpl->assign("Cart_sys_trans",         $Cart_sys_trans);
$tpl->assign("Cart_discount_totalPrices", $Cart_discount_totalPrices);
$tpl->assign("Cart_transmoney", $Cart_transmoney);
$tpl->assign("Cart_nomal_trans_type", $Cart_nomal_trans_type);
if ($ishave==1)
	$tpl->display("shopping_g_ajax_transport.html");
?>