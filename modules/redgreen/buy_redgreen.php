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
if($_GET['type']==1)
	$name = "redgoods";
else
	$name = "greengoods";

if ($_GET['act'] == "buy"){ 
	
	if ($gid>0 && $_GET['count']>0){
		
		
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($gid)."  limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			
			$storage = $PRODUCT->checkStorage($gid,$_GET['detail_id'],$_GET['color'],$_GET['size'],1);
			
			if ($storage > 0){
				$goods_array = $_COOKIE[$name][$saleid];
				$goods_color_array = $_COOKIE[$name . '_color'][$saleid];
				$goods_size_array = $_COOKIE[$name . '_size'][$saleid];
				$goods_detail_array = $_COOKIE[$name . '_detail'][$saleid];
				$goods_count_array = $_COOKIE[$name . '_count'][$saleid];
						$goodscount = count($_COOKIE[$name][$saleid]);
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
							setcookie($name."[" . $saleid . "][" . $maxkey . "]", intval($gid),time()+60*60*24,"/");
							setcookie($name."_color[" . $saleid . "][" . $maxkey . "]", ($_GET['color']),time()+60*60*24,"/");
							setcookie($name . "_size[" . $saleid . "][" . $maxkey . "]", ($_GET['size']),time()+60*60*24,"/");
							setcookie($name . "_detail[" . $saleid . "][" . $maxkey . "]", ($_GET['detail_id']),time()+60*60*24,"/");
							setcookie($name . "_count[" . $saleid . "][" . $maxkey . "]", intval($_GET['count']),time()+60*60*24,"/");
							
							echo "1";exit;
							
						}	
					}
		}
	}
	echo "1";exit;
}

if ($_GET['act'] == "del"){ 
	if (isset($_COOKIE[$name][$saleid])){
		$i = 0;
		$goods_array = $_COOKIE[$name][$saleid];
		$goods_color_array = $_COOKIE[$name . '_color'][$saleid];
		$goods_size_array = $_COOKIE[$name . '_size'][$saleid];
		$goods_detail_array = $_COOKIE[$name . '_detail'][$saleid];
		$goods_count_array = $_COOKIE[$name . '_count'][$saleid];
		$ii = 0;
		foreach($_COOKIE[$name][$saleid] as $k=>$v){
			if ($ii == 0){
				$j = $k;	
			}
			if ($v == intval($gid) && ($_GET['color'])==$_COOKIE[$name . '_color'][$saleid][$k] && ($_GET['size'])==$_COOKIE[$name . '_size'][$saleid][$k] && ($_GET['detail_id'])==$_COOKIE[$name . '_detail'][$saleid][$k] && intval($_GET['key']) == $k){
				
				$goods_array[$k] = 0;
				$goods_color_array[$k] = "";
				$goods_size_array[$k] = "";
				$goods_detail_array[$k] = 0;
				$goods_count_array[$k] = 0;
			}
			setcookie($name . "[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			setcookie($name . "_color[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			setcookie($name. "_size[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			setcookie($name . "_detail[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			setcookie($name . "_count[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			$i++;
			$ii++;
		}
		
		foreach($goods_array as $k=>$v){
			if ($goods_array[$k]>0){
				setcookie($name . "[" . $saleid . "][" . $j . "]", $goods_array[$k],time()+60*60*24,"/");
				setcookie($name . "_color[" . $saleid . "][" . $j . "]", $goods_color_array[$k],time()+60*60*24,"/");
				setcookie($name . "_size[" . $saleid . "][" . $j . "]", $goods_size_array[$k],time()+60*60*24,"/");
				setcookie($name . "_detail[" . $saleid . "][" . $j . "]", $goods_detail_array[$k],time()+60*60*24,"/");
				setcookie($name . "_count[" . $saleid . "][" . $j . "]", $goods_count_array[$k],time()+60*60*24,"/");
				$j++;
			}
			
		}
	}
	//print_r($goods_array);
	echo "1";exit;
}


?>