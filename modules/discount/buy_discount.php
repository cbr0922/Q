<?php
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/../../configs.inc.php");
include( RootDocument."/".Classes."/global.php");
$gid = intval($_GET['gid']);
$saleid = $_GET['saleid'];
if ($_GET['color'] == "undefined")
	$_GET['color'] = "";
if ($_GET['size'] == "undefined")
	$_GET['size'] = "";
if ($_GET['detail_id'] == "undefined")
	$_GET['detail_id'] = "";
include("product.class.php");
$PRODUCT = new PRODUCT();

if ($_GET['act'] == "buy"){

	if ($gid>0 && $_GET['count']>0){


		$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($gid)."  limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			/*
			if ($Result['storage']>0){
				$Sql_s      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($gid) . " and size='" . ($_GET['size']) . "' and color = '" . ($_GET['color']) . "'";
				$Query_s    = $DB->query($Sql_s);
				$Nums_s      = $DB->num_rows($Query_s);
				if ($Nums_s>0){
					$Rs_s=$DB->fetch_array($Query_s);
					$storage = $Rs_s['storage'];
				}else{
					$storage = $Result['storage'];
				}


			}
			*/
			$storage = $PRODUCT->checkStorage($gid,$_GET['detail_id'],$_GET['color'],$_GET['size'],1);

			if ($storage > 0){
				$goods_array = $_COOKIE['discountgoods'][$saleid];
				$goods_color_array = $_COOKIE['discountgoods_color'][$saleid];
				$goods_size_array = $_COOKIE['discountgoods_size'][$saleid];
				$goods_detail_array = $_COOKIE['discountgoods_detail'][$saleid];
				$goods_count_array = $_COOKIE['discountgoods_count'][$saleid];
						$goodscount = count($_COOKIE['discountgoods'][$saleid]);
						$flag = 0;

						if (isset($goods_array))
							ksort($goods_array);
						if (isset($goods_color_array))
							ksort($goods_color_array);
						if (isset($goods_size_array))
							ksort($goods_size_array);
						if (isset($goods_detail_array))
							ksort($goods_detail_array);
						if (isset($goods_count_array))
							ksort($goods_count_array);



						$j = 0;
						$maxkey = 0;
						if ($flag == 0){

							if (is_array($goods_array)){
								foreach($goods_array as $k=>$v){

									$maxkey = $k;
								}
							}
							$maxkey++;
							setcookie("discountgoods[" . $saleid . "][" . $maxkey . "]", intval($gid),time()+60*60*24,"/");
							setcookie("discountgoods_color[" . $saleid . "][" . $maxkey . "]", ($_GET['color']),time()+60*60*24,"/");
							setcookie("discountgoods_size[" . $saleid . "][" . $maxkey . "]", ($_GET['size']),time()+60*60*24,"/");
							setcookie("discountgoods_detail[" . $saleid . "][" . $maxkey . "]", ($_GET['detail_id']),time()+60*60*24,"/");
							setcookie("discountgoods_count[" . $saleid . "][" . $maxkey . "]", intval($_GET['count']),time()+60*60*24,"/");

							echo "1";exit;

						}
					}
		}
	}
	echo "1";exit;
}

if ($_GET['act'] == "del"){
	if (isset($_COOKIE['discountgoods'][$saleid])){
		$i = 0;
		$goods_array = $_COOKIE['discountgoods'][$saleid];
		$goods_color_array = $_COOKIE['discountgoods_color'][$saleid];
		$goods_size_array = $_COOKIE['discountgoods_size'][$saleid];
		$goods_detail_array = $_COOKIE['discountgoods_detail'][$saleid];
		$goods_count_array = $_COOKIE['discountgoods_count'][$saleid];
		$ii = 0;
		foreach($_COOKIE['discountgoods'][$saleid] as $k=>$v){
			if ($ii == 0){
				$j = $k;
			}
			if ($v == intval($gid) && ($_GET['color'])==$_COOKIE['discountgoods_color'][$saleid][$k] && ($_GET['size'])==$_COOKIE['discountgoods_size'][$saleid][$k] && ($_GET['detail_id'])==$_COOKIE['discountgoods_detail'][$saleid][$k] && intval($_GET['key']) == $k){

				$goods_array[$k] = 0;
				$goods_color_array[$k] = 0;
				$goods_size_array[$k] = 0;
				$goods_detail_array[$k] = 0;
			}
			setcookie("discountgoods[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			setcookie("discountgoods_color[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			setcookie("discountgoods_size[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			setcookie("discountgoods_detail[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			setcookie("discountgoods_count[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			$i++;
			$ii++;
		}

		foreach($goods_array as $k=>$v){
			if ($goods_array[$k]>0){
				setcookie("discountgoods[" . $saleid . "][" . $j . "]", $goods_array[$k],time()+60*60*24,"/");
				setcookie("discountgoods_color[" . $saleid . "][" . $j . "]", $goods_color_array[$k],time()+60*60*24,"/");
				setcookie("discountgoods_size[" . $saleid . "][" . $j . "]", $goods_size_array[$k],time()+60*60*24,"/");
				setcookie("discountgoods_detail[" . $saleid . "][" . $j . "]", $goods_detail_array[$k],time()+60*60*24,"/");
				setcookie("discountgoods_count[" . $saleid . "][" . $j . "]", $goods_count_array[$k],time()+60*60*24,"/");
				$j++;
			}

		}
	}
	//print_r($goods_array);
	echo "1";exit;
}


?>
