<?php
//error_reporting(0);
session_start();
include("../configs.inc.php");
require_once RootDocument.'/Classes/cart_group.class.php';
include("global.php");
include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";
$hometype = "group";
require "check_member.php";
@header("Content-type: text/html; charset=utf-8");

/**
 cart_LOGO的尺寸
*/
$tpl->assign("cart_logo_width",  $INFO["cart_logo_width"]);
$tpl->assign("cart_logo_height", $INFO["cart_logo_height"]);

//unset($_SESSION['cart']);

if(!isset($_SESSION['cart_group'])) {
	$_SESSION['cart_group'] = new Cart;
}
$cart =& $_SESSION['cart_group'];

if (!method_exists($cart,"getCartGoods")){
   $_SESSION['cart_group'] = new Cart;
   $cart =& $_SESSION['cart_group'];
}
if (intval($_SESSION['user_id'])>0){
	$member_grouppoint = $FUNCTIONS->Grouppoint(intval($_SESSION['user_id']));
}
//放入購物車
if ($_GET['Action']=="Add"){
	if ($_GET['type'] == "subject"){
		$goods_id_array  = $_COOKIE['groupgoods'][$_GET['saleid']];
	}else{
		$goods_id_array[0]  = $FUNCTIONS->Value_Manage(intval($_GET['goods_id']),'','shopping.php','');
	}
	if (is_array($goods_id_array)){
		foreach($goods_id_array as $k=>$goods_id){
			//echo $goods_id;exit;
			if ($goods_id > 0){
				if ($_GET['type'] == "subject"){
					$saleid = $_GET['saleid'];
					$_GET['count'] = $_COOKIE['groupgoods_count'][$_GET['saleid']][$k];
					$_GET['size'] = $_COOKIE['groupgoods_size'][$_GET['saleid']][$k];
					$_GET['color'] = $_COOKIE['groupgoods_color'][$_GET['saleid']][$k];
					$_GET['buytype'] = $_COOKIE['groupgoods_buytype'][$_GET['saleid']][$k];
				}
				$Query = $DB->query("select * from `{$INFO[DBPrefix]}groupdetail` where gdid='" . intval($goods_id) . "' and ifpub=1");
				$Num   = $DB->num_rows($Query);
				if ($Num>0) {
					$goods_array = array();
					$Rs=$DB->fetch_array($Query);
					
					
					$goods_array['gid'] = $Rs['gdid'];
					$goods_array['bn'] = $Rs['groupbn'];
					$goods_array['goodsname'] = $Rs['groupname'];
					$goods_array['temp_price'] = $Rs['groupprice'];
					$goods_array['price'] = $Rs['groupprice'];
					$goods_array['weight'] = 0;
					$goods_array['temp_grouppoint'] = $Rs['grouppoint'];
					$goods_array['grouppoint'] = $Rs['grouppoint'];
					$goods_array['subject'] = intval($saleid);
					$goods_array['smallimg'] = $Rs['groupSimg'];
					$goods_array['goodslist'] = $Rs['goodslist'];
					$goods_array['size'] = $_GET['size'];
					$goods_array['color'] = $_GET['color'];
					$goods_array['buytype'] = intval($_GET['buytype']);
					$goods_array['memberprice'] = intval($Rs['memberprice']);
					if (intval($_GET['count'])<=0)
						$goods_array['count'] = 1;
					else
						$goods_array['count'] = intval($_GET['count']);
					$goods_array['storage'] = $Rs['storage'];
					//團購專區價格
					if ($_GET['type'] == "subject"){
						$s_Sql      = "select * from `{$INFO[DBPrefix]}groupgoods` as dg where dg.gsid='" . intval($saleid) . "' and dg.gid='" . $goods_id . "' ";
						$s_Query    = $DB->query($s_Sql);
						$s_Num   = $DB->num_rows($s_Query);
						if ($s_Num>0){
							$s_Rs=$DB->fetch_array($s_Query);
							$goods_array['subject_grouppoint'] = $s_Rs['grouppoint'];
							$goods_array['price'] = $goods_array['subject_price'] = $s_Rs['price'];
							
						}
					}
					/*
					if ($goods_array['buytype']==0){
						$goods_array['grouppoint'] = 0;	
					}else{
						$goods_array['price'] = $Rs['memberprice'];	
					}
					*/
					//庫存
					$goods_d_array  = explode(",",$Rs['goodslist']);
					foreach($goods_d_array as $kk=>$v){
						$Query_d = $DB->query("select * from `{$INFO[DBPrefix]}goods` where bn='".trim($v)."'  limit 0,1");
						$Num_d   = $DB->num_rows($Query_d);
						if ($Num_d>0){
							$Result_d= $DB->fetch_array($Query_d);
							if ($Result_d['storage']>0){
								$storage_array[$i] = $Result_d['storage'];
							}else{
								$goods_array['storage'] = 0;
							}
							$goods_array['weight'] += $Result_d['weight'];
						}else{
							$goods_array['storage'] = 0;
						}
						$i++;
					}
					if (is_array($storage_array)){
						sort($storage_array,SORT_NUMERIC);
						$goods_array['storage'] = $storage_array[0];
						if ($goods_array['count'] > intval($INFO['buy_product_max_num']))
							$goods_array['count']  = intval($INFO['buy_product_max_num']);
					}
					//print_r( $goods_array);exit;
					if ($goods_array['storage']>0){
						$cart->addItems($goods_array);
					}
					
					if ($_GET['type'] == "subject"){
						setcookie("groupgoods[" . $saleid . "][" . $k . "]", 0,time(),"/");
						setcookie("groupgoods_count[" . $saleid . "][" . $k . "]", 0,time(),"/");
						setcookie("groupgoods_size[" . $saleid . "][" . $k . "]", '',time(),"/");
						setcookie("groupgoods_color[" . $saleid . "][" . $k . "]", '',time(),"/");
						setcookie("groupgoods_buytype[" . $saleid . "][" . $k . "]", 0,time(),"/");
					}
				}
			}
		}
	}
	//print_r($goods_array);
	//exit;
	header("Location:shopping_g.php");
}


if ($_GET['Action']=="clear"){
	unset($_SESSION['cart_group']);	
	header("Location:shopping_g.php");
}

if ($_GET['Action']=="remove"){
	$cart->deleItems($_GET['key'],$_GET['gkey']);
	//$cart->clearAddGoods($_GET['key'],$_GET['gkey']);
	echo "<script>location.href='shopping_g.php';</script>";
	//exit;
}

if ($_GET['Action']=="change"){
	$cart->changeItems($_GET['key'],$_GET['gkey'],"count",intval($_GET['count']));
	echo "<script>location.href='shopping_g.php';</script>";
}
if ($_GET['Action']=="changebuytype"){
	//現有團購點
	$point =$FUNCTIONS->Grouppoint(intval($_SESSION['user_id']),1);
	//購物車已經使用的團購點
	$cart->setTotal($_GET['key']);
	$Cart_totalGrouppoint = $cart->totalGrouppoinit;
	$items_array = $cart->getCartGroup($_GET['key']);
	$grouppoint = $items_array[$_GET['gkey']]['grouppoint'];
	if(intval($_GET['buytype'])==1){
		if($point<($Cart_totalGrouppoint+$grouppoint)){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您最多可以使用" . $point . "點團購金');location.href='shopping_g.php';</script>";exit;	
		}else{
			$cart->changeItems($_GET['key'],$_GET['gkey'],"buytype",intval($_GET['buytype']));
		}
	}else{
		$cart->changeItems($_GET['key'],$_GET['gkey'],"buytype",intval($_GET['buytype']));	
	}
	echo "<script>location.href='shopping_g.php';</script>";
}

$cart->resetCart();

//購物車中商品
$items_array = $cart->getCartGoods();
$Cart_item = array();
$i = 0;
foreach($items_array as $k=>$v){
	//print_r($v);exit;
	$j = 0;
	$Cart_item[$i]['key'] = $k;
	if (is_array($v)){
		foreach($v as $kk=>$vv){
			$Cart_item[$i]['goods'][$j] = $vv;
			$Cart_item[$i]['goods'][$j]['price']       = $cart->setSaleoff($k,$kk);
			if ($vv['buytype']==0)
				$Cart_item[$i]['goods'][$j]['total'] = $vv['count'] * $Cart_item[$i]['goods'][$j]['price'];
			if ($vv['buytype']==1){
				$Cart_item[$i]['goods'][$j]['total'] = $vv['count'] * $vv['memberprice'];
				$Cart_item[$i]['goods'][$j]['totalpoint'] = $vv['count'] * $Cart_item[$i]['goods'][$j]['grouppoint'];
			}
			$Cart_item[$i]['totalprice'] = intval($Cart_item[$i]['totalprice']) + intval($Cart_item[$i]['goods'][$j]['total']);
			$Cart_item[$i]['totalpoint'] = intval($Cart_item[$i]['totalpoint']) + intval($Cart_item[$i]['goods'][$j]['totalpoint']);
			//購買數量下拉框值
			for ($z=1;$z<=intval($INFO['buy_product_max_num']) && $z<=intval($Cart_item[$i]['goods'][$j]['storage']);$z++){
				$Cart_item[$i]['goods'][$j]['storagelist'][$z] = $z;
			}
			$j++;
		}
	}

}


$tpl->assign("Session_user_id", $_SESSION['user_id']); //登陆后用户名
$tpl->assign("Cart_count",      $i);
$tpl->assign("Cart_item",      $Cart_item);
$tpl->assign("member_grouppoint",      $member_grouppoint);
$tpl->assign("MinBuyMoney",         $INFO['MinBuyMoney']);
$tpl->assign("Gpicpath",         $INFO['good_pic_path']);
$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Basic_Command);
$tpl->display("shopping_g.html");
?>
