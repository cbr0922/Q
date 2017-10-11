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
	
	if ($gid>0){
		
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
			$counts = 0;
			foreach($_COOKIE['buysalegoods'][$saleid] as $v){
				if ($v==$gid)
					$counts++;
			}
			if (($storage-$counts) > 0){
				
				$goods_array = $_COOKIE['buysalegoods'][$saleid];
				$goods_color_array = $_COOKIE['buysalegoods_color'][$saleid];
				$goods_size_array = $_COOKIE['buysalegoods_size'][$saleid];
				$goods_detail_array = $_COOKIE['buysalegoods_detail'][$saleid];
						$goodscount = count($_COOKIE['buysalegoods'][$saleid]);
						$flag = 0;
						if (isset($goods_array))
							ksort($goods_array);
						if (isset($goods_color_array))
							ksort($goods_color_array);
						if (isset($goods_size_array))
							ksort($goods_size_array);
						if (isset($goods_detail_array))
							ksort($goods_detail_array);
						
						
						$j = 0;
						$maxkey = 0;
						if ($flag == 0){
							if (isset($goods_array)){
								foreach($goods_array as $k=>$v){
									$maxkey = $k;
								}
							}
							$maxkey++;
							setcookie("buysalegoods[" . $saleid . "][" . $maxkey . "]", intval($gid),time()+60*60*24,"/");
							setcookie("buysalegoods_color[" . $saleid . "][" . $maxkey . "]", ($_GET['color']),time()+60*60*24,"/");
							setcookie("buysalegoods_size[" . $saleid . "][" . $maxkey . "]", ($_GET['size']),time()+60*60*24,"/");
							setcookie("buysalegoods_detail[" . $saleid . "][" . $maxkey . "]", ($_GET['detail_id']),time()+60*60*24,"/");
						}	
					}
		}
		echo 1;
	}
}

if ($_GET['act'] == "del"){ 
	if (isset($_COOKIE['buysalegoods'][$saleid])){
		$i = 0;
		$goods_array = $_COOKIE['buysalegoods'][$saleid];
		$goods_color_array = $_COOKIE['buysalegoods_color'][$saleid];
		$goods_size_array = $_COOKIE['buysalegoods_size'][$saleid];
		$goods_detail_array = $_COOKIE['buysalegoods_detail'][$saleid];
		$ii = 0;
		foreach($_COOKIE['buysalegoods'][$saleid] as $k=>$v){
			if ($ii == 0){
				$j = $k;	
			}
			if ($v == intval($gid) && ($_GET['color'])==$_COOKIE['buysalegoods_color'][$saleid][$k] && ($_GET['size'])==$_COOKIE['buysalegoods_size'][$saleid][$k] && ($_GET['detail_id'])==$_COOKIE['buysalegoods_detail'][$saleid][$k] && intval($_GET['key']) == $k){
				$goods_array[$k] = 0;
				$goods_color_array[$k] = 0;
				$goods_size_array[$k] = 0;
				$goods_detail_array[$k] = 0;
			}
			setcookie("buysalegoods[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			setcookie("buysalegoods_color[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			setcookie("buysalegoods_size[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			setcookie("buysalegoods_detail[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			$i++;
			$ii++;
		}
		foreach($goods_array as $k=>$v){
			if ($goods_array[$k]>0){
				setcookie("buysalegoods[" . $saleid . "][" . $j . "]", $goods_array[$k],time()+60*60*24,"/");
				setcookie("buysalegoods_color[" . $saleid . "][" . $j . "]", $goods_color_array[$k],time()+60*60*24,"/");
				setcookie("buysalegoods_size[" . $saleid . "][" . $j . "]", $goods_size_array[$k],time()+60*60*24,"/");
				setcookie("buysalegoods_detail[" . $saleid . "][" . $j . "]", $goods_detail_array[$k],time()+60*60*24,"/");
				$j++;
			}
			
		}
	}
	echo "1";
}


?>