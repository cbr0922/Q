<?php
require_once('../Classes/cart.class.php' );
session_start();
include("../configs.inc.php");
include("global.php");

include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";
@header("Content-type: text/html; charset=utf-8");

if(!isset($_SESSION['cart'])) {
	$_SESSION['cart'] = new Cart;
}
$cart =& $_SESSION['cart'];

if(function_exists("method_exists")){
	if(is_object($cart)){
		if (!method_exists($cart,"getCart")){
		   $_SESSION['cart'] = new Cart;
		   $cart =& $_SESSION['cart'];
		}
	}else{
		$_SESSION['cart'] = new Cart;
		$cart =& $_SESSION['cart'];
	}
}

//初始化購物車
$cart->resetCart();
//購物車中商品
$items_array = $cart->getCart();
$Cart_item = array();

$i = 0;
foreach($items_array as $k=>$v){
	//print_r($v);
	if(substr($k,0,1)=="M"){
		$m_key_value = explode("_",$k);
		$bid = substr($m_key_value[0],1);
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($bid)." limit 0,1 ");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result     =  $DB->fetch_array($Query);
			$manyunfei = $Result['manyunfei'];
			$catname = $Result['catname'];
		}
		$Cart_item[$i]['trans_name'] = $catname . "館，滿" . $manyunfei . "元免運費";
		//echo $Cart_item[$i]['totalprice'];
		if ($Cart_item[$i]['totalprice']<$manyunfei)
			$Cart_item[$i]['ifbuy'] = "0";
	}else{
		$key_value = explode("_",$k);
		//print_r($key_value);
		//商店
		if($key_value[0]>0){
			$Sql_p      = "select * from `{$INFO[DBPrefix]}shopinfo` where  sid=".intval($key_value[0]) . " ";
			$Query_p    = $DB->query($Sql_p);
			$Rs_p=$DB->fetch_array($Query_p);
			$Cart_item[$i]['shop_name'] = $Rs_p['shopname'];
		}elseif($key_value[1]>0){
			//供應商
			$Sql_p      = "select * from `{$INFO[DBPrefix]}provider` where  provider_id=".intval($key_value[1]) . " order by provider_idate  ";
			$Query_p    = $DB->query($Sql_p);
			$Rs_p=$DB->fetch_array($Query_p);
			$Cart_item[$i]['provider_name'] = "廠商配送(" . $Rs_p['providerno'] .")";
			$Cart_item[$i]['trans_name'] = "[運費：" . $Rs_p['yunfei'] . "元，滿" . $Rs_p['mianyunfei'] . "元免運費]";
		}

		if($key_value[2]>0||$Cart_item[$i]['trans_name']==""){
			$transtype = $key_value[2];
			if ($transtype == 0){
				$Cart_item[$i]['trans_name'] = "您的清單";//常溫
			}
			if ($transtype == 1){
				$Cart_item[$i]['trans_name'] = "冷藏配送";
			}
			if ($transtype == 2){
				$Cart_item[$i]['trans_name'] = "冷凍配送";
			}
		}
	}


	$j = 0;
	$Cart_item[$i]['key'] = addslashes($k);
	if (is_array($v)){
		foreach($v as $kk=>$vv){
			//print_r($vv);
			if (intval($vv['packgid'])==0){
				$Cart_item[$i]['goods'][$j] = $vv;
					$Cart_item[$i]['goods'][$j]['total'] = $vv['count'] * $vv['price'];
					$Cart_item[$i]['totalcount'] += $vv['count'];
					$Cart_item[$i]['totalprice'] = intval($Cart_item[$i]['totalprice']) + intval($Cart_item[$i]['goods'][$j]['total']);

					if ($Cart_item[$i]['goods'][$j][ifbonus] == 1 || $Cart_item[$i]['goods'][$j][ifgoodspresent] == 1 || $Cart_item[$i]['goods'][$j][ifpresent] == 1 || $Cart_item[$i]['goods'][$j][ifchange] == 1 || $Cart_item[$i]['goods'][$j][ifadd] == 1) {
						$urlisbox=1;
					}else{
						$urlisbox=0;
					}
					
					//購買數量下拉框值
					for ($z=1;$z<=intval($INFO['buy_product_max_num']) && $z<=intval($Cart_item[$i]['goods'][$j]['storage']);$z++){
						$Cart_item[$i]['goods'][$j]['storagelist'][$z] = $z;
					}					$Cart_item[$i]['goods'][$j]['storagecount'] = $z-1;
					$j++;
				}
				//print_r($Cart_item[$i]['goods'][$j]);

			}

	}
	//額滿加購
	$goods_sql = "select * from `{$INFO[DBPrefix]}goods` as g where g.ifpub='1' and g.ifjs!=1  and g.ifbonus!='1' and g.ifadd=1 and g.storage>0 and g.addmoney<='" . $Cart_item[$i]['totalprice'] . "'";
	$goods_sql .= " and g.ttype='" . intval($key_value[2]) . "' ";
	if (intval($key_value[1])>0){
		$goods_sql .= " and g.provider_id=".intval($key_value[1]) . " and g.iftogether=0";
	}else{
		$goods_sql .= " and (g.provider_id=0 or g.iftogether=1)";
	}
	 $goods_sql .= " order by present_money desc,g.idate desc";
	$goods_Query    = $DB->query($goods_sql);
	$j = 0;
	$em_goods_array = array();
	while ($goods_Rs=$DB->fetch_array($goods_Query)) {
		$em_goods_array[$j]['goodsname'] = $goods_Rs['goodsname'];
		$em_goods_array[$j]['gid'] = $goods_Rs['gid'];
		$em_goods_array[$j]['addmoney'] = $goods_Rs['addmoney'];
		$em_goods_array[$j]['addprice'] = $goods_Rs['addprice'];
		$em_goods_array[$j]['smallimg'] = $goods_Rs['smallimg'];
		$em_goods_array[$j]['pricedesc'] = $goods_Rs['pricedesc'];
		$j++;
	}
	$Cart_item[$i]['addgoods'] = $em_goods_array;
	$Cart_item[$i]['addgoodscount'] = $j;

	$i++;
}
//print_r($Cart_item);
//追蹤清單
$Sql = "select g.smallimg,g.goodsname,g.intro,g.price,g.pricedesc,g.gid,c.collection_id from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid)  inner join  `{$INFO[DBPrefix]}collection_goods` c on (c.gid=g.gid)  where  b.catiffb=1 and g.ifpub=1 and c.user_id='".intval($_SESSION['user_id'])."' and g.storage>0 order by c.cidate desc ";

$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);

$tpl->assign("collection_Num",      intval($Num));

//print_r($Cart_item);
$tpl->assign("Session_user_id", $_SESSION['user_id']); //登陆后用户名
$tpl->assign("Cart_count",      $i);
$tpl->assign("Cart_item",      $Cart_item);
$tpl->assign("MinBuyMoney",         $INFO['MinBuyMoney']);
$tpl->assign("Gpicpath",         $INFO['good_pic_path']);
$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Basic_Command);
/* FB像素InitiateCheckout事件 */
$track_id = '9';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){

  if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
	$track_Js = "fbq('track', 'InitiateCheckout');";
  }
	else $track_Js="";
	$tpl->assign("InitiateCheckout_js",   $track_Js);
}

$tpl->assign("adv_array",     $adv_array);
$tpl->assign("urlisbox",     $urlisbox);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
if ($_GET['type']=="goods")
	$tpl->display("shopping_goods.html");
elseif($_GET['type']=="FB")
	$tpl->display("shopping_fb.html");
else
	$tpl->display("shopping.html");
?>