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
switch($_GET['Action']){
	//添加
	case "Add":
		if ($_GET['type'] == "sale"){
			$goods_id_array  = $_COOKIE['buysalegoods'][$_GET['saleid']];
		}elseif ($_GET['type'] == "discount"){
			$goods_id_array  = $_COOKIE['discountgoods'][$_GET['saleid']];
		}elseif ($_GET['type'] == "redgreen"){
			//$goods_id_array  = $_COOKIE['red'][$_GET['saleid']];
			$goods_id_array  = array_merge($_COOKIE['redgoods'][$_GET['saleid']],$_COOKIE['greengoods'][$_GET['saleid']]);
			$redgoods_size  = array_merge($_COOKIE['redgoods_size'][$_GET['saleid']],$_COOKIE['greengoods_size'][$_GET['saleid']]);
			$redgoods_color  = array_merge($_COOKIE['redgoods_color'][$_GET['saleid']],$_COOKIE['greengoods_color'][$_GET['saleid']]);
			$redgoods_detail  = array_merge($_COOKIE['redgoods_detail'][$_GET['saleid']],$_COOKIE['greengoods_detail'][$_GET['saleid']]);
			$redgoods_count  = array_merge($_COOKIE['redgoods_count'][$_GET['saleid']],$_COOKIE['greengoods_count'][$_GET['saleid']]);
			//print_r($redgoods_count);exit;
			//print_r($_COOKIE['redgoods'][$_GET['saleid']]);exit;
		}elseif ($_GET['type'] == "manyunfei"){
			$goods_id_array  = $_COOKIE['mangoods'][$_GET['bid']];
		}else{
			$goods_id_array[0]  = $FUNCTIONS->Value_Manage(intval($_GET['goods_id']),'','shopping.php','');
		}
		if (is_array($goods_id_array)){
			foreach($goods_id_array as $k=>$goods_id){
				if ($goods_id > 0){
					if ($_GET['type'] == "sale"){
						$saleid = $_GET['saleid'];
						$_GET['good_size'] = $_COOKIE['buysalegoods_size'][$_GET['saleid']][$k];
						$_GET['good_color'] = $_COOKIE['buysalegoods_color'][$_GET['saleid']][$k];
						$_GET['detail_id'] = $_COOKIE['discountgoods_detail'][$_GET['saleid']][$k];
					}
					if ($_GET['type'] == "discount"){
						$dsid = $_GET['saleid'];
						$_GET['good_size'] = $_COOKIE['discountgoods_size'][$_GET['saleid']][$k];
						$_GET['good_color'] = $_COOKIE['discountgoods_color'][$_GET['saleid']][$k];
						$_GET['detail_id'] = $_COOKIE['discountgoods_detail'][$_GET['saleid']][$k];
						$_GET['count'] = $_COOKIE['discountgoods_count'][$_GET['saleid']][$k];

					}
					if ($_GET['type'] == "redgreen"){
						$dsid = $_GET['saleid'];
						$_GET['rgid'] = $dsid;
						/**
						if(in_array($goods_id,$_COOKIE['redgoods'][$_GET['saleid']])){
							$_GET['good_size'] = $_COOKIE['redgoods_size'][$_GET['saleid']][$k];
							$_GET['good_color'] = $_COOKIE['redgoods_color'][$_GET['saleid']][$k];
							$_GET['detail_id'] = $_COOKIE['redgoods_detail'][$_GET['saleid']][$k];
							$_GET['count'] = $_COOKIE['redgoods_count'][$_GET['saleid']][$k];
							$_GET['redgreen_type'] = 1;
						}elseif(in_array($goods_id,$_COOKIE['greengoods'][$_GET['saleid']])){
							$_GET['good_size'] = $_COOKIE['greengoods_size'][$_GET['saleid']][$k];
							$_GET['good_color'] = $_COOKIE['greengoods_color'][$_GET['saleid']][$k];
							$_GET['detail_id'] = $_COOKIE['greengoods_detail'][$_GET['saleid']][$k];
							$_GET['count'] = $_COOKIE['greengoods_count'][$_GET['saleid']][$k];
							$_GET['redgreen_type'] = 2;
						}
						**/
						if(in_array($goods_id,$_COOKIE['redgoods'][$_GET['saleid']])){
							$_GET['redgreen_type'] = 1;
						}elseif(in_array($goods_id,$_COOKIE['greengoods'][$_GET['saleid']])){
							$_GET['redgreen_type'] = 2;
						}
						$_GET['good_size'] = $redgoods_size[$k];
						$_GET['good_color'] = $redgoods_color[$k];
						$_GET['detail_id'] = $redgoods_detail[$k];
						$_GET['count'] = $redgoods_count[$k];
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
					if ($_GET['type'] == "manyunfei")
						$key = "M" . $bid;
					else
						$key = "";
					$cart->getShoppingGoods($goods_id,$_GET,"",$key);

					if ($_GET['type'] == "manyunfei"){
						setcookie("mangoods[" . $bid . "][" . $k . "]", 0,time(),"/");
						setcookie("mangoods_color[" . $bid . "][" . $k . "]", 0,time(),"/");
						setcookie("mangoods_size[" . $bid . "][" . $k . "]", 0,time(),"/");
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
					if ($_GET['type'] == "redgreen"){
						setcookie("redgoods[" . $saleid . "][" . $k . "]", 0,time(),"/");
							setcookie("redgoods_color[" . $saleid . "][" . $k . "]", 0,time(),"/");
							setcookie("redgoods_size[" . $saleid . "][" . $k . "]", 0,time(),"/");
							setcookie("redgoods_count[" . $saleid . "][" . $k . "]", 0,time(),"/");
						//}else{
							setcookie("greengoods[" . $saleid . "][" . $k . "]", 0,time(),"/");
							setcookie("greengoods_color[" . $saleid . "][" . $k . "]", 0,time(),"/");
							setcookie("greengoods_size[" . $saleid . "][" . $k . "]", 0,time(),"/");
							setcookie("greengoods_count[" . $saleid . "][" . $k . "]", 0,time(),"/");
					}
				}
			}
		}
		if ($_GET['Type'] == "goods"){
			echo 1;
			exit;
		}
		header("Location:shopping.php?type=" . $_GET['type']);
		break;
	case "clear":
	//清空
		$cart->clearGoods("");
		$_SESSION['cart'] = "";
		unset($_SESSION['cart']);
		header("Location:shopping.php");
		break;
	case "remove":
	//刪除
		$cart->deleItems($_GET['key'],$_GET['gkey']);
		$cart->clearAddGoods($_GET['key'],$_GET['gkey']);
		echo "<script>location.href='shopping.php';</script>";
		break;
	case "change":
	//更改數量
		$result = $cart->changeItemsCount($_GET['key'],$_GET['gkey'],intval($_GET['count']));
		if($result==0){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您的點數不夠兌換紅利商品');location.href='shopping.php';</script>";exit;
		}
		$cart->clearAddGoods($_GET['key'],$_GET['gkey']);
		echo "<script>location.href='shopping.php';</script>";
		break;
	case "move";
	//收藏
		if ($_SESSION['user_id']=="" || empty($_SESSION['user_id'])){
			$FUNCTIONS->header_location("../member/login_windows.php?Url=" . urlencode("../shopping/shopping.php?gid=" . $_GET['gid'] . "&key=" . $_GET['key'] . "&gkey=" . $_GET['gkey'] . "&Action=move"));
		}
		$Goods_id = intval($_GET['gid']);
		$Querys   = $DB->query("select * from `{$INFO[DBPrefix]}collection_goods` where gid='".$Goods_id."' and user_id='".$_SESSION['user_id']."'  limit 0,1");
		$Nums   = $DB->num_rows($Querys);
		if ( $Nums==0 ) {  //如果不存在资料,就将执行插入操作
			$DB->query("insert into  `{$INFO[DBPrefix]}collection_goods` (gid,user_id,cidate) values('".intval($Goods_id)."','".intval($_SESSION['user_id'])."','" . time() . "')");
		}
		$cart->deleItems($_GET['key'],$_GET['gkey']);
		$cart->clearAddGoods($_GET['key'],$_GET['gkey']);
		echo "<script>location.href='shopping.php';</script>";
		break;
	case "addgoods":
	//加購商品
		$goods_array['count'] = 1;
		$goods_array['ifadd'] = 1;
		$cart->getShoppingGoods(intval($_GET['gid']),$goods_array,"addgoods",$_GET['key']);
		echo "<script>location.href='shopping.php';</script>";
		break;
	case "presentgoods":
	//額滿禮商品
		$goods_array['count'] = 1;
		$goods_array['ifpresent'] = 1;
		$cart->getShoppingGoods(intval($_GET['gid']),$goods_array,"presentgoods",$_GET['key']);
		echo "<script>location.href='shopping3.php?key=" . $_GET['key'] . "';</script>";
		break;
	case "bonus":
	//設置紅利
		if(intval($_POST['point'])<0){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('請填寫正確的紅利點數');location.href='shopping3.php?key=" . $_POST['key'] . "';</script>";exit;
		}
		$result = $cart->setBonus(intval($_POST['point']),$_POST['key']);
		if($result[0]==0){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您最多可以抵扣" . $result[1] . "點紅利');location.href='shopping3.php?key=" . $_POST['key'] . "';</script>";exit;
		}
		echo "<script>location.href='shopping3.php?key=" . $_POST['key'] . "';</script>";
		break;
	case "ticket":
	//折價券
		$result = $cart->setTicket(intval($_GET['ticketid']),$_GET['key'],$_GET['ticketcode']);
		//print_r($result);
		if($result[0]==0){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('需要購買" . $result[1] . "商品才能使用此折價券');location.href='shopping3.php?key=" . $_POST['key'] . "';</script>";exit;
		}elseif($result[0]==-1){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('此現金券已使用過，每張現金券只能只用一次');location.href='shopping3.php?key=" . $_GET['key'] . "';</script>";exit;
		}elseif($result[0]==-4){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您已經的優惠券號碼已超過使用次數');location.href='shopping2.php?key=" . $_GET['key'] . "';</script>";exit;
		}elseif($result[0]==-2){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您使用的是無效現金券');location.href='shopping3.php?key=" . $_GET['key'] . "';</script>";exit;
		}elseif($result[0]==-3){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您使用的折價券是針對特定商品的，您並沒有購買相關商品，所以不能使用。');location.href='shopping3.php?key=" . $_GET['key'] . "';</script>";exit;
		}
		echo "<script>location.href='shopping3.php?key=" . $_GET['key'] . "';</script>";
		break;
	case "bonusgoods":
	//紅利商品兌換
		$goods_array['count'] = 1;
		$goods_array['ifbonus'] = 1;
		$cart->getShoppingGoods(intval($_GET['gid']),$goods_array,"bonusgoods",$_GET['key']);
		echo "<script>location.href='shopping.php';</script>";
		break;
	case "buypoint":
	//購物金
		$cart->setTotal($_POST['key']);
		$Cart_totalPrices = $cart->totalPrices;//商品網絡總計
		$Cart_totalGrouppoint = $cart->totalGrouppoinit;
		$Cart_transmoney = $cart->transmoney;
		if(intval($_SESSION['user_id'])>0)
			$myBuyPoint = $FUNCTIONS->Buypoint(intval($_SESSION['user_id']));
		
		if(intval($_POST['buypoint']) > $myBuyPoint){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您最多可以使用" . $myBuyPoint . "購物金');location.href='shopping3.php?key=" . $_POST['key'] . "';</script>";exit;
		}
		if(intval($_POST['buypoint']) > $Cart_totalPrices+$Cart_transmoney){
			$_POST['buypoint'] = $Cart_totalPrices+$Cart_transmoney;
		}
		$usebuypoint = intval($_POST['buypoint']);
		
		$cart->setBuypoint($usebuypoint);
		
		header("Location:shopping3.php?key=" . $_POST['key'] . "&hometype=" . $_POST['hometype']);
		break;
	default:
		echo "Error!";
}
?>
