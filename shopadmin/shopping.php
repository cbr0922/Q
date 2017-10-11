<?php
require_once('../Classes/cart.class.php' );
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

//unset($_SESSION['cart']);

if(!isset($_SESSION['cart'])) {
	$_SESSION['cart'] = new Cart;
}
$cart =& $_SESSION['cart'];

if(function_exists("method_exists")){
	if(is_object($cart)){
		if (!method_exists($cart,"getCartGoods")){
		   $_SESSION['cart'] = new Cart;
		   $cart =& $_SESSION['cart'];
		}
	}else{
		$_SESSION['cart'] = new Cart;
		$cart =& $_SESSION['cart'];	
	}
}


$cart->setTotalbonuspoint();
$Cart_bonus = $cart->bonus['point'];//紅利
$totalbonuspoint = $cart->totalbonuspoint;//紅利

if (intval($_SESSION['user_id'])>0){
	$member_point = $FUNCTIONS->Userpoint(intval($_SESSION['user_id']),1);
	$point = $member_point-$totalbonuspoint-$Cart_bonus;
}
//print_r( $_COOKIE);
//放入購物車
if (intval($INFO['allsaleoff'])>0 && intval($INFO['allsaleoff'])<100 && date("Y-m-d",time())>=$INFO['allsaleoff_begintime'] && date("Y-m-d",time())<=$INFO['allsaleoff_endtime']){
	$ifallsaleoff = 1;
}else{
	$ifallsaleoff = 0;	
}
if ($_GET['Action']=="Add"){
	
	if ($_GET['type'] == "sale"){
		$goods_id_array  = $_COOKIE['buysalegoods'][$_GET['saleid']];
	}elseif ($_GET['type'] == "discount"){
		$goods_id_array  = $_COOKIE['discountgoods'][$_GET['saleid']];
	}elseif ($_GET['type'] == "FB"){
		$goods_id_array  = $_COOKIE['fbgoods'];
	}elseif ($_GET['type'] == "manyunfei"){
		$goods_id_array  = $_COOKIE['mangoods'][$_GET['bid']];
	}else{
		$goods_id_array[0]  = $FUNCTIONS->Value_Manage(intval($_GET['goods_id']),'','shopping.php','');
	}
	if (is_array($goods_id_array)){
		foreach($goods_id_array as $k=>$goods_id){
			//echo $goods_id;exit;
			if ($goods_id > 0){
				if ($_GET['type'] == "sale"){
					$saleid = $_GET['saleid'];
					$_GET['good_size'] = $_COOKIE['buysalegoods_size'][$_GET['saleid']][$k];
					$_GET['good_color'] = $_COOKIE['buysalegoods_color'][$_GET['saleid']][$k];
				}
				if ($_GET['type'] == "discount"){
					$dsid = $_GET['saleid'];
					$_GET['good_size'] = $_COOKIE['discountgoods_size'][$_GET['saleid']][$k];
					$_GET['good_color'] = $_COOKIE['discountgoods_color'][$_GET['saleid']][$k];
					$_GET['count'] = $_COOKIE['discountgoods_count'][$_GET['saleid']][$k];
				}
				if ($_GET['type'] == "FB"){
					$_GET['count'] = $_COOKIE['fbgoods_count'][$k];
				}
				if ($_GET['type'] == "manyunfei"){
					$bid = $_GET['bid'];
					$_GET['good_size'] = $_COOKIE['mangoods_size'][$_GET['bid']][$k];
					$_GET['good_color'] = $_COOKIE['mangoods_color'][$_GET['bid']][$k];
					$_GET['count'] = $_COOKIE['mangoods_count'][$_GET['bid']][$k];
				}
				//echo $goods_id;
			$Query = $DB->query("select g.gid,g.goodsname,g.unit,g.provider_id,g.good_color,g.good_size,g.nocarriage,g.smallimg,g.price,g.pricedesc,g.point ,g.ifjs,g.js_begtime,g.js_endtime,g.storage,g.if_monthprice,g.ifpresent,g.trans_special_money,g.trans_special,g.iftransabroad,g.trans_type,g.ifxygoods,g.ifchange,g.ifbonus,g.bonusnum,g.ifalarm,g.addmoney,g.ifadd,g.addprice,g.oeid,g.timesale_starttime,g.timesale_endtime,g.iftimesale,g.saleoffprice,g.ifsales,g.sale_price,g.sale_subject,g.ifalarm,g.transtype,g.ifmood,g.addtransmoney,g.transtypemonty,g.memberprice,g.combipoint,g.bn,g.iftogether,g.weight,g.shopid,g.cost,g.salecost,g.bid,g.if_monthprice,g.month,g.bonus_statetime,g.bonus_endtime,newuser_starttime,newuser_endtime,newuser_price,g.olduser_price,g.ifpack  from `{$INFO[DBPrefix]}goods` g where gid=".intval($goods_id)." and g.ifchange!=1 and g.ifpub=1 limit 0,1 ");
			 $Num   = $DB->num_rows($Query);
			if ($Num>0) {
				$goods_array = array();
				$Rs=$DB->fetch_array($Query);
					if ((intval($Rs['ifjs'])==1 && !($Rs['js_begtime']<=date("Y-m-d",time()) && $Rs['js_endtime']>=date("Y-m-d",time())))){
						//echo $Good[AlertZeroExDate]; //【【集殺商品已過期】】
						//echo "<br>  <a href='javascript:window.history.back(-1);'>Back</a>";
						//exit;
						$Rs['ifjs'] = 0;
					}
					if (intval($_GET['GetJs_price'])>0){   //_REQUEST
						$goods_array['Js_price']=  trim($_GET['GetJs_price']); //_REQUEST
					}else{
						$goods_array['Js_price']=  0;
					}
					
					//運費
					$goods_array['transtype'] = $Rs['transtype'];
					$goods_array['ifmood'] = $Rs['ifmood'];
					$goods_array['addtransmoney'] = $Rs['addtransmoney'];
					$goods_array['transtypemonty'] = $Rs['transtypemonty'];
					$goods_array['cost'] = $Rs['cost'];
					$goods_array['month'] = $Rs['month'];
					$goods_array['salecost'] = $Rs['salecost'];
					$goods_array['ifinstall'] = intval($_GET['ifinstall']);
					
					$goods_array['ifjs'] = $Rs['ifjs'];
					$goods_array['iftogether'] = $Rs['iftogether'];
					$goods_array['gid'] = $Rs['gid'];
					$goods_array['bn'] = $Rs['bn'];
					$goods_array['provider_id'] = $Rs['provider_id'];
					$goods_array['goodsname'] = $Rs['goodsname'];
					$goods_array['temp_price'] = $Rs['price'];
					$goods_array['ifalarm'] = $Rs['ifalarm'];
					$goods_array['weight'] = $Rs['weight'];
					$goods_array['shopid'] = $Rs['shopid'];
					$goods_array['ifpack'] = $Rs['ifpack'];
					//分類折扣
					//echo intval($Rs['bid']);
					$rebate_array = $FUNCTIONS->getTopClass(intval($Rs['bid']));
					//print_r($rebate_array);exit;
					$goods_array['rebate'] = $rebate_array[0];
					$goods_array['costrebate'] = $rebate_array[1];
					if ($goods_array['rebate']>0 && $Rs['ifbonus']==0 && $Rs['ifpresent']==0 && $goods_array['Js_price']==0 && $Rs['iftimesale']==0 && $Rs['ifchange']==0 && $Rs['ifsale']==0 && $Rs['ifadd']==0&& intval($dsid)==0 && intval($Rs['shopid'])==0){
						$memberprice = round(round($Rs['pricedesc']/100,2)*$goods_array['rebate'],0);
						$goods_array['cost'] = round(round($Rs['cost']/100,2)*$goods_array['costrebate'],0);
						$goods_array['rebateinfo'] = "[館別折扣x"  . round(intval($goods_array['rebate'])/100,2) . "]";
						if ($goods_array['costrebate']>0)
							$goods_array['costinfo'] = "館別促銷折扣成本x"  . round(intval($goods_array['costrebate'])/100,2) . "";
					}elseif($ifallsaleoff==0)
						$memberprice = $FUNCTIONS->MemberLevelPrice($_SESSION['user_level'],intval($goods_id),0);
					
					
					if ( $Rs['ifjs'] == 1){
						$goods_array['price'] = $goods_array['Js_price'];
					}else{
						$goods_array['price'] = $memberprice > 0?$memberprice:$Rs['pricedesc'];
					}
					$goods_array['org_price'] = $goods_array['price'];
					$goods_array['nosaleoff']  = 0;
					//詳細內容
					 if (intval($_GET['detail_id']) > 0){
						 $Detail_Sql      = "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . intval($goods_id) . "' and detail_id='" . intval($_GET['detail_id']) . "' order by detail_id desc ";
						
						$Detail_Query    = $DB->query($Detail_Sql);
						$Detail_Num   = $DB->num_rows($Detail_Query);
						if ($Detail_Num > 0){
							$Detail_Rs = $DB->fetch_array($Detail_Query);
							$goods_array['detail_id'] = $Detail_Rs['detail_id'];
							$goods_array['detail_bn'] = $Detail_Rs['detail_bn'];
							$goods_array['bn'] = $Detail_Rs['detail_bn'];
							$goods_array['detail_name'] = $Detail_Rs['detail_name'];
							$goods_array['detail_des'] = $Detail_Rs['detail_des'];
							$goods_array['detail_storage'] = $Detail_Rs['storage'];
							$goods_array['temp_price'] = $Detail_Rs['detail_price'];
							$goods_array['cost'] = $Detail_Rs['detail_cost'];
							if($ifallsaleoff==0)
								$memberprice = $FUNCTIONS->MemberLevelPrice($_SESSION['user_level'],intval($goods_id),intval($_GET['detail_id']));
							$goods_array['price'] = $memberprice > 0?$memberprice:$Detail_Rs['detail_pricedes'];
						}
					 }
					 if ($Rs['ifpresent']==1){
						 $goods_array['detail_name'] = "額滿禮";
					 }
					 //庫存
					$goods_array['storage'] = $Rs['storage'];
					if($Rs['ifpack']==1){
						$storage = 0;
						$Sql_p         = "select gl.* ,g.goodsname,g.bn,g.storage from `{$INFO[DBPrefix]}goods_pack` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.packgid=g.gid) where gl.gid=".intval($goods_id)." order by gl.idate desc ";
						$Query_p    = $DB->query($Sql_p);
						$Nums_p      = $DB->num_rows($Query_p);	
						while($Rs_p=$DB->fetch_array($Query_p)){
							if($Rs_p['storage']>0 && ($storage==0 || $Rs_p['storage']<$storage))
								$storage = $Rs_p['storage'];
						}
						$goods_array['storage'] = $storage;
					}
					$goods_array['unit'] = $Rs['unit'];
					$goods_array['good_color'] = $_GET['good_color'];
					$goods_array['good_size'] = $_GET['good_size'];
					if ($_GET['good_color']!="" || $_GET['good_size']!=""){
						$goods_Sql = "select * from `{$INFO[DBPrefix]}attributeno` where gid='" . $goods_id . "' and size='" . $_GET['good_size'] . "' and color='" . $_GET['good_color'] . "'";
						$goods_Query =  $DB->query($goods_Sql);
						$goods_Num   =  $DB->num_rows($goods_Query );
						if ($goods_Num>0){
							$goods_Rs = $DB->fetch_array($goods_Query);
							$goods_array['bn'] = $goods_Rs['goodsno'];
						}	
					}
					//屬性庫存
					$storage_Sql      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($goods_id) . " and color='" . $_GET['good_color'] . "' and size='" . $_GET['good_size'] . "'";
					$storage_Query    = $DB->query($storage_Sql);
					$storage_Nums      = $DB->num_rows($storage_Query);
					if ($storage_Nums>0){
						$storage_Rs=$DB->fetch_array($storage_Query);
						$goods_array['color_size_storage'] = $storage_Rs['storage'];
					}else{
						$goods_array['color_size_storage'] = 0;
					}
		
					$goods_array['smallimg'] = $Rs['smallimg'];
					$goods_array['point'] = $Rs['point'];
					if (intval($_GET['count'])>0)
						$goods_array['count'] = intval($_GET['count']);
					else
						$goods_array['count'] = 1;
					$goods_array['trans_type'] = $Rs['trans_type'];
					$goods_array['special_trans_money'] = $Rs['trans_special_money'];
					$goods_array['trans_special'] = $Rs['trans_special'];
					$goods_array['iftransabroad'] = $Rs['iftransabroad'];
					
					$goods_array['ifxygoods'] = $Rs['ifxygoods'];
					$goods_array['xygoods'] = $_GET['xygid'];
					$goods_array['xygoods_color'] = $_GET['xycolor'];
					$goods_array['xygoods_size'] = $_GET['xyxize'];
					
					//紅利
					$goods_array['ifbonus'] = $Rs['ifbonus'];
					$goods_array['bonuspoint'] = $Rs['bonusnum'];
					if ($Rs['ifbonus']==1){
						 $goods_array['detail_name'] = "紅利兌換[" . $Rs['bonusnum'] . "點]";
						 $goods_array['price'] = 0;
						 $goods_array['org_price'] = 0;
						 if(intval($_SESSION['user_id']) <= 0){
							echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('請您登入後再兌換紅利商品');location.href='../member/login_windows.php';</script>";exit;
						 }
						 if ($point<$Rs['bonusnum']){
							echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您的紅利積點不夠兌換此商品，您目前可以兌換" . $point . "點紅利商品');history.back(-1)</script>";exit;	 
						 }
					 }
					 
					 if($Rs['ifalarm']==0 && $goods_array['storage']==0)
						$goods_array['storage'] = $INFO['buy_product_max_num'];
					
					//超值任選
					if(is_array($_GET['xygid']) && $Rs['ifxygoods']==1){
						foreach($_GET['xygid'] as $xyk=>$xyv){
							$Query_xy = $DB->query("select g.gid,g.goodsname,g.unit,g.provider_id,g.good_color,g.good_size,g.nocarriage,g.smallimg,g.price,g.pricedesc,g.point ,g.ifjs,g.js_begtime,g.js_endtime,g.storage,g.if_monthprice,g.ifpresent,g.trans_special_money,g.trans_special,g.iftransabroad,g.trans_type  from `{$INFO[DBPrefix]}goods` g where gid=".intval($xyv)." limit 0,1 ");
							$Num_xy   = $DB->num_rows($Query_xy);
							if ($Num_xy>0){
								$Rs_xy=$DB->fetch_array($Query_xy);
								$goods_array['xygoods_des'] .= "<br>" . $Rs_xy['goodsname'];
								if ($_GET['xycolor'.$xyv]!=""){
										$goods_array['xygoods_des'] .= "<br>顏色：" . $_GET['xycolor'.$xyv];
								}
								if ($_GET['xyxize'.$xyv]!=""){
										$goods_array['xygoods_des'] .= "<br>尺寸：" . $_GET['xyxize'.$xyv];
								}
							}
						}
					}
					
					//多件折扣
					$goods_array['ifsale'] = $Rs['ifsales'];
					
					if ($Rs['ifsales'] == 1 && $Rs['sale_subject'] > 0){
						$goods_array['sale_price'] = $Rs['sale_price'];
						$goods_array['sale_subject'] = $Rs['sale_subject'];
						$goods_array['org_price'] = $goods_array['price'];
						$Query_sale = $DB->query("select subject_name,subject_content,salecount  from  `{$INFO[DBPrefix]}sale_subject`  where subject_id='" . $Rs['sale_subject'] ."' and subject_open=1  limit 0,1");
						$Rs_sale=$DB->fetch_array($Query_sale);
						$Num_sale   = $DB->num_rows($Query_sale);
						if ($Num_sale > 0){
							$cart->setSaleSu($Rs['sale_subject'],$Rs_sale['salecount']);	
						}
					}
					//會員價格
					$goods_array['memberprice'] = $Rs['memberprice'];
					$goods_array['combipoint'] = $Rs['combipoint'];
					$goods_array['memberorprice'] = 1;
					$goods_array['ifadd'] = $_GET['ifadd'];
					$goods_array['oeid'] = $Rs['oeid'];
					$goods_array['addmoney'] = $Rs['addmoney'];
					if($goods_array['ifadd'] == 1){
						$goods_array['price'] = $Rs['addprice'];
						$goods_array['nosaleoff']  = 1;
					}
					//正點促銷
					if ($Rs['iftimesale']==1 && $Rs['timesale_starttime']<=time() && $Rs['timesale_endtime']>=time()){
						$goods_array['price']  = $Rs['saleoffprice'];
						$goods_array['org_price']  = $Rs['saleoffprice'];
						$goods_array['iftimesale']  = $Rs['iftimesale'];
						$goods_array['detail_name'] = "促銷價格會員不再折扣";
						$goods_array['nosaleoff']  = 1;
						//$goods_array['storage'] = 1;
						$goods_array['memberprice'] = $Rs['addprice'];
						$goods_array['memberorprice'] = 1;
					}
					//主題活動
					//if($dsid>0){
						$Sql_ds   = "select dg.price as sale_price,dg.cost,dg.dsid from `{$INFO[DBPrefix]}discountgoods` as dg inner join `{$INFO[DBPrefix]}goods` as g on dg.gid=g.gid where g.ifpub=1 and  g.gid=".intval($goods_id);
						$Query_ds = $DB->query($Sql_ds);
						while($Rs_ds =  $DB->fetch_array($Query_ds)){
							//print_r($Rs_ds);
							$Query_sale = $DB->query("select * from  `{$INFO[DBPrefix]}discountsubject`  where dsid='" . $Rs_ds['dsid'] ."' and start_date<='" . date("Y-m-d",time()) . "' and end_date>='" . date("Y-m-d",time()) . "' and subject_open=1  limit 0,1");
							$Rs_sale=$DB->fetch_array($Query_sale);
							 $Num_sale   = $DB->num_rows($Query_sale);
							if ($Num_sale > 0){
								$dsid = $Rs_sale['dsid'];
								$cart->setDiscountInfo($dsid,$Rs_sale['min_count'],$Rs_sale['min_money'],$Rs_sale['mianyunfei'],$Rs_sale['saleoff'],$Rs_sale['buytype'],$Rs_sale['buycount'],$Rs_sale['buyprice']);	
								$goods_array['dsid'] = $dsid;	
								$goods_array['dsprice'] = $Rs_ds['sale_price'];	
								$goods_array['cost'] = $Rs_ds['cost'];	
								$goods_array['costinfo'] = "促銷活動成本";
							}
						}
						//exit;
				//	}
					
					//額滿加購
					
					
					//新會員促銷價格
					if(intval($_SESSION['user_id'])>0)
						$ifnew = $FUNCTIONS->getSaleOrder(intval($_SESSION['user_id']));
					if($Rs['newuser_starttime']<=time() && $Rs['newuser_endtime']>=time() && ($Rs['newuser_price']>0 || $Rs['olduser_price']>0)){ 
						$goods_array['ifneworold'] = 1;
						if($ifnew==1 && $Rs['newuser_price']>0){
							$goods_array['price']  = $Rs['newuser_price'];	
							$goods_array['org_price']  = $Rs['newuser_price'];	
						}elseif($Rs['olduser_price']>0 && intval($_SESSION['user_id'])>0){
							$goods_array['price']  = $Rs['olduser_price'];	
							$goods_array['org_price']  = $Rs['olduser_price'];		
						}
					}
					
					
					//$goods_array['ifchange'] = $_POST['ifchange'];
					//$goods_array['changegid'] = $_POST['changegid'];
					
					//print_r($goods_array);
					//exit;
					//exit;
					if (intval($_GET['ifinstall']) == 1){
						$key = time();
						if ($goods_array['storage'] >1 )
							$goods_array['storage'] = 1;
						$cart->addItems($goods_array,$key);
						if (intval($_SESSION['user_id'])>0){
							header("Location:shopping2.php?key=" . $key);exit;
						}else{
							header("Location:shoppingop.php?key=" . $key);exit;
						}
					}
					if ($_GET['type'] == "manyunfei"){
						$key = "M" . $bid;
						//print_r($goods_array);
						$cart->addItems($goods_array,$key);
						setcookie("mangoods[" . $bid . "][" . $k . "]", 0,time(),"/");
						setcookie("mangoods_color[" . $bid . "][" . $k . "]", 0,time(),"/");
						setcookie("mangoods_size[" . $bid . "][" . $k . "]", 0,time(),"/");
						//header("Location:shopping2.php?key=" . $key);
						//exit;
					}else{
						$key = $cart->addItems($goods_array,$_GET['key']);
					}
					if ($_GET['type'] == "sale"){
						setcookie("buysalegoods[" . $saleid . "][" . $k . "]", 0,time(),"/");
						setcookie("buysalegoods_color[" . $saleid . "][" . $k . "]", 0,time(),"/");
						setcookie("buysalegoods_size[" . $saleid . "][" . $k . "]", 0,time(),"/");
					}
					if ($_GET['type'] == "discount"){
						setcookie("discountgoods[" . $saleid . "][" . $k . "]", 0,time(),"/");
						setcookie("discountgoods_color[" . $saleid . "][" . $k . "]", 0,time(),"/");
						setcookie("discountgoods_size[" . $saleid . "][" . $k . "]", 0,time(),"/");
					}
					
					if ($_GET['type'] == "FB"){
						setcookie("fbgoods[" . $k . "]", 0,time(),"/");
						setcookie("fbgoods_count[" . $k . "]", 0,time(),"/");
					}
					if(is_array($_GET['changegid'])){
						$z = 0;
						foreach($_GET['changegid'] as $k=>$v){
							$color_array[$z] = $_GET['changecolor' . $v];
							$size_array[$z] = $_GET['changesize' . $v];
							$z++;
						}
						$change_array['changegid'] = $_GET['changegid'];
						$change_array['changecolor'] = $color_array;
						$change_array['changesize'] = $size_array;
						$change_array['trans_type'] = $goods_array['trans_type'];
						$change_array['special_trans_money'] = 0;
						$change_array['trans_special'] = $goods_array['trans_special'];
						$change_array['iftransabroad'] = $goods_array['iftransabroad'];
						//print_r($change_array);exit;
						addchangegoods($change_array);
					}
					if($Rs['ifpack']==1){
						addpackgoods(intval($goods_id));	
					}
				
			}
			}
		}
	}
	//echo $key;
	//exit;
	if ($_GET['type'] == "manyunfei" || $_GET['type'] == "discount"){
		header("Location:shopping2.php?key=" . $key);
		exit;
	}
	if ($_GET['Type'] == "goods"){
		echo 1;
		exit;
	}
	header("Location:shopping.php?type=" . $_GET['type']);
}

function addpackgoods($goods_id){
	global $DB,$INFO,$cart;
	$Sql         = "select gl.* ,g.gid,g.goodsname,g.bn,g.unit,g.provider_id,g.good_color,g.good_size,g.nocarriage,g.smallimg,g.pricedesc as price,g.point ,g.ifjs,g.js_begtime,g.js_endtime,g.storage,g.if_monthprice,g.ifpresent,g.trans_special_money,g.trans_special,g.iftransabroad,g.trans_type,g.ifxygoods,g.ifchange,g.iftogether,g.ifmood from `{$INFO[DBPrefix]}goods_pack` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.packgid=g.gid) where gl.gid=".intval($goods_id)." and g.ifpub=1 order by gl.idate desc ";
	$Query       = $DB->query($Sql);
	$Num         = $DB->num_rows($Query);
	while ($Rs=$DB->fetch_array($Query)){
		$goods_array = array();
				$goods_array['gid'] = $Rs['gid'];
				$goods_array['cost'] = $Rs['cost'];
				$goods_array['packgid'] = $goods_id;
				$goods_array['bn'] = $Rs['bn'];
				$goods_array['provider_id'] = $Rs['provider_id'];
				$goods_array['ifmood'] = $Rs['ifmood'];
				$goods_array['goodsname'] = $Rs['goodsname'];
				$goods_array['price'] = $Rs['price'];
				$goods_array['org_price']  = $Rs['price'];
				$goods_array['storage'] = $Rs['storage'];
				$goods_array['unit'] = $Rs['unit'];
				$goods_array['good_color'] = $_GET['changecolor' . $Rs['gid']];
				$goods_array['good_size'] = $_GET['changesize' . $Rs['gid']];
				$goods_array['iftogether'] = $Rs['iftogether'];
				$goods_array['memberorprice'] = 1;
				//屬性庫存
				$storage_Sql      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($Rs['gid']) . " and color='" . $goods_array['good_color'] . "' and size='" . $goods_array['good_size'] . "'";
				$storage_Query    = $DB->query($storage_Sql);
				$storage_Nums      = $DB->num_rows($storage_Query);
				if ($storage_Nums>0){
					$storage_Rs=$DB->fetch_array($storage_Query);
					$goods_array['color_size_storage'] = $storage_Rs['storage'];
				}else{
					$goods_array['color_size_storage'] = 0;
				}
	
				$goods_array['smallimg'] = $Rs['smallimg'];
				if($Rs['bonus_statetime']<=time() && $Rs['bonus_endtime']>=time()){
					$goods_array['point'] = $Rs['point'];
				}else{
					$goods_array['point'] = 0;	
				}
				$goods_array['count'] = intval($_GET['count']);
				$goods_array['trans_type'] = $Rs['trans_type'];
				$goods_array['special_trans_money'] = $Rs['trans_special_money'];
				$goods_array['trans_special'] = $Rs['trans_special'];
				$goods_array['iftransabroad'] = $Rs['iftransabroad'];
				//print_r($goods_array);exit;
				$cart->addItems($goods_array);
	}
}

function addchangegoods($changegoods_array){
	global $DB,$INFO,$goods_id_array,$cart;
	$goods_id = $goods_id_array[0];
	if(is_array($changegoods_array['changegid'])){
		foreach($changegoods_array['changegid'] as $k=>$v){
			$Query = $DB->query("select g.gid,g.goodsname,g.bn,g.unit,g.provider_id,g.good_color,g.good_size,g.nocarriage,g.smallimg,g.price,g.pricedesc,g.point ,g.ifjs,g.js_begtime,g.js_endtime,g.storage,g.if_monthprice,g.ifpresent,g.trans_special_money,g.trans_special,g.iftransabroad,g.trans_type,g.ifxygoods,g.ifchange,gc.price,g.iftogether,g.ifmood  from `{$INFO[DBPrefix]}goods_change` as gc inner join `{$INFO[DBPrefix]}goods` g on g.gid=gc.changegid where gc.changegid=".intval($v)." and gc.gid='" . $goods_id . "' and g.ifchange=1 limit 0,1 ");
			$Num   = $DB->num_rows($Query);
			if ($Num>0) {
				$goods_array = array();
				$Rs=$DB->fetch_array($Query);
				$goods_array['gid'] = $Rs['gid'];
				$goods_array['bn'] = $Rs['bn'];
				$goods_array['provider_id'] = $Rs['provider_id'];
				$goods_array['ifmood'] = $Rs['ifmood'];
				$goods_array['goodsname'] = $Rs['goodsname'];
				$goods_array['price'] = $Rs['price'];
				$goods_array['org_price']  = $Rs['price'];
				$goods_array['storage'] = $Rs['storage'];
				$goods_array['unit'] = $Rs['unit'];
				$goods_array['good_color'] = $changegoods_array['changecolor'][$k];
				$goods_array['good_size'] = $changegoods_array['changesize'][$k];
				$goods_array['iftogether'] = $Rs['iftogether'];
				$goods_array['memberorprice'] = 1;
				//屬性庫存
				$storage_Sql      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($v) . " and color='" . $goods_array['good_color'] . "' and size='" . $goods_array['good_size'] . "'";
				$storage_Query    = $DB->query($storage_Sql);
				$storage_Nums      = $DB->num_rows($storage_Query);
				if ($storage_Nums>0){
					$storage_Rs=$DB->fetch_array($storage_Query);
					$goods_array['color_size_storage'] = $storage_Rs['storage'];
				}else{
					$goods_array['color_size_storage'] = 0;
				}
	
				$goods_array['smallimg'] = $Rs['smallimg'];
				if($Rs['bonus_statetime']<=time() && $Rs['bonus_endtime']>=time()){
					$goods_array['point'] = $Rs['point'];
				}else{
					$goods_array['point'] = 0;	
				}
				$goods_array['count'] = 1;
				$goods_array['ifchange'] = 1;
				$goods_array['changegid'] = $goods_id;
				$goods_array['trans_type'] = $changegoods_array['trans_type'];
				$goods_array['special_trans_money'] = $changegoods_array['trans_special_money'];
				$goods_array['trans_special'] = $changegoods_array['trans_special'];
				$goods_array['iftransabroad'] = $changegoods_array['iftransabroad'];
				//print_r($goods_array);exit;
				$cart->addItems($goods_array);
			}
		}	
	}
}

if ($_GET['Action']=="clear"){
	unset($_SESSION['cart']);	
	header("Location:shopping.php");
}

if ($_GET['Action']=="remove"){
	$cart->setBonus(0);
	$cart->setTicket(array());
	
	$cart->deleItems($_GET['key'],$_GET['gkey']);
	$cart->clearAddGoods($_GET['key'],$_GET['gkey']);
	//header("Location:shopping.php");
	echo "<script>location.href='shopping.php';</script>";
}

if ($_GET['Action']=="change"){
	$cart->setBonus(0);
	$cart->setTicket(array());
	$cart->changeItems($_GET['key'],$_GET['gkey'],"count",intval($_GET['count']));
	$cart->clearAddGoods($_GET['key'],$_GET['gkey']);
	//header("Location:shopping.php");
	echo "<script>location.href='shopping.php';</script>";
}

if ($_GET['Action']=="move"){
	if ($_SESSION['user_id']=="" || empty($_SESSION['user_id'])){
		$FUNCTIONS->header_location("../member/login_windows.php?Url=" . base64_encode("../shopping/shopping.php?gid=" . $_GET['gid'] . "&key=" . $_GET['key'] . "&gkey=" . $_GET['gkey'] . "&Action=move"));
	}
	$Goods_id = intval($_GET['gid']);
	$Querys   = $DB->query("select * from `{$INFO[DBPrefix]}collection_goods` where gid='".$Goods_id."' and user_id='".$_SESSION['user_id']."'  limit 0,1");
	$Nums   = $DB->num_rows($Querys);
	if ( $Nums==0 ) {  //如果不存在资料,就将执行插入操作
		$DB->query("insert into  `{$INFO[DBPrefix]}collection_goods` (gid,user_id,cidate) values('".intval($Goods_id)."','".intval($_SESSION['user_id'])."','" . time() . "')");
	}
	$cart->setBonus(0);
	$cart->setTicket(array());
	$cart->deleItems($_GET['key'],$_GET['gkey']);
	$cart->clearAddGoods($_GET['key'],$_GET['gkey']);
	//header("Location:shopping.php");
	echo "<script>location.href='shopping.php';</script>";
}
$cart->resetCart();
//運費
$sys_trans['PayFreetrans'] = $INFO[PayFreetrans];
$sys_trans['PayStartprice'] = $INFO[PayStartprice];
$sys_trans['PayEndprice'] = $INFO[PayEndprice];
$cart->setIniTrans($INFO['Paytype'],$sys_trans);


//購物車中商品
$items_array = $cart->getCartGoods();
foreach($items_array as $k=>$v){
	//echo $k;
	$cart->changeSalePrice($k);
	$cart->changeDiscountPrice($k);
	foreach($v as $kk=>$vv){
		if ($vv['ifpresent'] == 1){
			$cart->deleItems($k,$vv['gkey']);	
		}
	}
}
$items_array = $cart->getCartGoods();
//print_r($items_array);
$Cart_item = array();

$i = 0;
if(intval($_SESSION['user_id'])>0)
	$ifnew = $FUNCTIONS->getSaleOrder(intval($_SESSION['user_id']));
foreach($items_array as $k=>$v){
	$key_value = explode("P",$k);
	$key_value_shop = explode("S",$k);
	if (substr($k,0,2)=="FB"){		
		$cart->transname = "facebook商城";
		$Cart_item[$i]['trans_name'] = "facebook商城";
	}elseif (substr($k,0,1)=="T"){		
		$transtype = substr($key_value[0],1);
		if ($transtype == 1){
			$Cart_item[$i]['trans_name'] = "中小型物件";
			$cart->transname = "中小型物件";
		}
		if ($transtype == 2){
			$Cart_item[$i]['trans_name'] = "大型物件";
			$cart->transname = "大型物件";
		}
	}elseif(substr($k,0,1)=="M"){
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
	}else if($key_value[0] == 0){
		$Cart_item[$i]['trans_name'] = "一起買配送方式";
	}else{
		$Sql      = "select * from `{$INFO[DBPrefix]}transportation_special` where trid='" . intval($key_value[0]) . "' order by trid ";
		$Query    = $DB->query($Sql);
		$Num      = $DB->num_rows($Query);	
		if ($Num>0){
			$Rs=$DB->fetch_array($Query);
			$Cart_item[$i]['trans_name'] = $Rs['name'];
		}else{
			$Cart_item[$i]['trans_name'] = "分期付款";	
		}
	}
	if (intval($key_value[1])>0){
		$Sql_p      = "select * from `{$INFO[DBPrefix]}provider` where  provider_id=".intval($key_value[1]) . " order by provider_idate  ";
		$Query_p    = $DB->query($Sql_p);
		$Rs_p=$DB->fetch_array($Query_p);
		$Cart_item[$i]['provider_name'] = "廠商配送(" . $Rs_p['providerno'] .")";
		$Cart_item[$i]['trans_name'] = "[運費：" . $Rs_p['yunfei'] . "元，滿" . $Rs_p['mianyunfei'] . "元免運費]";
	}
	if (intval($key_value_shop[1])>0){
		$Sql_p      = "select * from `{$INFO[DBPrefix]}shopinfo` where  sid=".intval($key_value_shop[1]) . " ";
		$Query_p    = $DB->query($Sql_p);
		$Rs_p=$DB->fetch_array($Query_p);
		$Cart_item[$i]['shop_name'] = $Rs_p['shopname'];
	}
	$j = 0;
	$Cart_item[$i]['key'] = $k;
	if (is_array($v)){
		foreach($v as $kk=>$vv){
			if ($vv['packgid']==0){
				$Cart_item[$i]['goods'][$j] = $vv;
				//print_r($vv);
				
					if ($vv['ifbonus']==0 && $vv['ifpresent']==0 && $vv['Js_price']==0 && $vv['iftimesale']==0 && $vv['ifchange']==0 && $vv['ifsale']==0 && $vv['ifadd']==0 && $vv['ifneworold']==0 && $ifallsaleoff ==0){
						$MemberPiceReturn = $FUNCTIONS->MemberLevelPrice($_SESSION['user_level'],$vv['gid'],$vv['detail_id']);
						$Cart_item[$i]['goods'][$j]['price']       = $MemberPiceReturn>0 ? $MemberPiceReturn : $vv['price'] ;
						$Cart_item[$i]['goods'][$j]['price']       = $cart->setSaleoff($k,$kk);
					}
				//echo $vv[shopid];
				
					$Cart_item[$i]['goods'][$j]['total'] = $vv['count'] * $Cart_item[$i]['goods'][$j]['price'];
					$Cart_item[$i]['totalcount'] += $vv['count'];
					$Cart_item[$i]['totalprice'] = intval($Cart_item[$i]['totalprice']) + intval($Cart_item[$i]['goods'][$j]['total']);
				
					//購買數量下拉框值
					for ($z=1;$z<=intval($INFO['buy_product_max_num']) && $z<=intval($Cart_item[$i]['goods'][$j]['maxstorage']);$z++){
						$Cart_item[$i]['goods'][$j]['storagelist'][$z] = $z;
					}
				}
				$j++;
			}
		
	}
	//額滿加購
	$goods_sql = "select * from `{$INFO[DBPrefix]}goods` as g where g.ifpub='1' and g.ifjs!=1  and g.ifbonus!='1' and g.ifadd=1 and g.storage>0 and g.addmoney<='" . $Cart_item[$i]['totalprice'] . "'";
	if (intval($key_value[1])>0){
		$goods_sql .= " and provider_id=".intval($key_value[1]) . " and g.iftogether=0";	
	}else{
		$goods_sql .= " and (provider_id=0 or g.iftogether=1)";		
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
		$j++;
	}
	$Cart_item[$i]['addgoods'] = $em_goods_array;
	$Cart_item[$i]['addgoodscount'] = $j;
	
	$i++;
}

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



if ($_GET['type']=="goods")
	$tpl->display("shopping_goods.html");
elseif($_GET['type']=="FB")
	$tpl->display("shopping_fb.html");
else
	$tpl->display("shopping.html");
?>
