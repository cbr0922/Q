<?php
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");
$gid = intval($_GET['gid']);
$saleid = $_GET['saleid'];
if ($_GET['color'] == "undefined")
		$_GET['color'] = "";
	if ($_GET['size'] == "undefined")
		$_GET['size'] = "";
if ($_GET['act'] == "checkstorage"){
	
	if ($gid>0){
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($gid)."  limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			if ($Result['storage']>0){
				$Sql_s      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($gid) . " and size='" . phpUnescape($_GET['size']) . "' and color = '" . phpUnescape($_GET['color']) . "'";
				$Query_s    = $DB->query($Sql_s);
				$Nums_s      = $DB->num_rows($Query_s);	
				if ($Nums_s>0){
					$Rs_s=$DB->fetch_array($Query_s);
					echo $Rs_s['storage'];	
				}else{
					echo $Result['storage'];	
				}
			}else{
				echo "0";	
			}
		}else{
		echo "0";
		}
	}
}

if ($_GET['act'] == "buy"){ 
	
	if ($gid>0 && $_GET['count']>0){
		
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($gid)."  limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			if ($Result['storage']>0){
				$Sql_s      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($gid) . " and size='" . phpUnescape($_GET['size']) . "' and color = '" . phpUnescape($_GET['color']) . "'";
				$Query_s    = $DB->query($Sql_s);
				$Nums_s      = $DB->num_rows($Query_s);	
				if ($Nums_s>0){
					$Rs_s=$DB->fetch_array($Query_s);
					$storage = $Rs_s['storage'];
				}else{
					$storage = $Result['storage'];
				}
					
				
			}
			if ($storage > 0){
				$goods_array = $_COOKIE['discountgoods'][$saleid];
				$goods_color_array = $_COOKIE['discountgoods_color'][$saleid];
				$goods_size_array = $_COOKIE['discountgoods_size'][$saleid];
				$goods_count_array = $_COOKIE['discountgoods_count'][$saleid];
						$goodscount = count($_COOKIE['discountgoods'][$saleid]);
						$flag = 0;
						/*
						if (isset($_COOKIE['discountgoods'][$saleid])){
							foreach($_COOKIE['discountgoods'][$saleid] as $k=>$v){
								if ($v == intval($gid) && phpUnescape($_GET['color'])==$_COOKIE['discountgoods_color'][$saleid][$k] && phpUnescape($_GET['size'])==$_COOKIE['discountgoods_size'][$saleid][$k]){
									$flag = 1;
								}
							}
						}
						*/
						if (isset($goods_array))
							ksort($goods_array);
						if (isset($goods_color_array))
							ksort($goods_color_array);
						if (isset($goods_size_array))
							ksort($goods_size_array);
						if (isset($goods_count_array))
							ksort($goods_count_array);
							/*
						if (isset($_COOKIE['discountgoods'][$saleid])){
							
							foreach($_COOKIE['discountgoods'][$saleid] as $k=>$v){
								
								setcookie("discountgoods[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
								setcookie("discountgoods_color[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
								setcookie("discountgoods_size[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
							}
						}
						*/
						
						
						$j = 0;
						$maxkey = 0;
						if ($flag == 0){
							if (isset($goods_array)){
								foreach($goods_array as $k=>$v){
									/*
									if ($goods_array[$k]>0){
										setcookie("discountgoods[" . $saleid . "][" . $j . "]", $goods_array[$k],time()+60*60*24,"/");
										setcookie("discountgoods_color[" . $saleid . "][" . $j . "]", $goods_color_array[$k],time()+60*60*24,"/");
										setcookie("discountgoods_size[" . $saleid . "][" . $j . "]", $goods_size_array[$k],time()+60*60*24,"/");
										$j++;
									}
									*/
									$maxkey = $k;
								}
							}
							$maxkey++;
							setcookie("discountgoods[" . $saleid . "][" . $maxkey . "]", intval($gid),time()+60*60*24,"/");
							setcookie("discountgoods_color[" . $saleid . "][" . $maxkey . "]", phpUnescape($_GET['color']),time()+60*60*24,"/");
							setcookie("discountgoods_size[" . $saleid . "][" . $maxkey . "]", phpUnescape($_GET['size']),time()+60*60*24,"/");
							setcookie("discountgoods_count[" . $saleid . "][" . $maxkey . "]", intval($_GET['count']),time()+60*60*24,"/");
							echo "1";
							//echo $maxkey;
							//print_r($_COOKIE['discountgoods']);
						}	
					}
		}
	}
}

if ($_GET['act'] == "del"){ 
	if (isset($_COOKIE['discountgoods'][$saleid])){
		$i = 0;
		$goods_array = $_COOKIE['discountgoods'][$saleid];
		$goods_color_array = $_COOKIE['discountgoods_color'][$saleid];
		$goods_size_array = $_COOKIE['discountgoods_size'][$saleid];
		$ii = 0;
		foreach($_COOKIE['discountgoods'][$saleid] as $k=>$v){
			if ($ii == 0){
				$j = $k;	
			}
			if ($v == intval($gid) && phpUnescape($_GET['color'])==$_COOKIE['discountgoods_color'][$saleid][$k] && phpUnescape($_GET['size'])==$_COOKIE['discountgoods_size'][$saleid][$k] && intval($_GET['key']) == $k){
				/*
				array_splice($goods_array,$i,1);
				array_splice($goods_color_array,$i,1);
				array_splice($goods_size_array,$i,1);
				*/
				$goods_array[$k] = 0;
				$goods_color_array[$k] = 0;
				$goods_size_array[$k] = 0;
			}
			setcookie("discountgoods[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			setcookie("discountgoods_color[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			setcookie("discountgoods_size[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			$i++;
			$ii++;
		}
		//print_r($_COOKIE['discountgoods']);
		//$j = 0;
		foreach($goods_array as $k=>$v){
			if ($goods_array[$k]>0){
				setcookie("discountgoods[" . $saleid . "][" . $j . "]", $goods_array[$k],time()+60*60*24,"/");
				setcookie("discountgoods_color[" . $saleid . "][" . $j . "]", $goods_color_array[$k],time()+60*60*24,"/");
				setcookie("discountgoods_size[" . $saleid . "][" . $j . "]", $goods_size_array[$k],time()+60*60*24,"/");
				$j++;
			}
			
		}
	}
	//print_r($goods_array);
	echo "1";
}

function phpUnescape($escstr)   
{   
    preg_match_all("/%u[0-9A-Za-z]{4}|%.{2}|[0-9a-zA-Z.+-_]+/", $escstr, $matches);   
    $ar = &$matches[0];   
    $c = "";   
    foreach($ar as $val)   
    {   
        if (substr($val, 0, 1) != "%")   
        {   
            $c .= $val;   
        } elseif (substr($val, 1, 1) != "u")   
        {   
            $x = hexdec(substr($val, 1, 2));   
            $c .= chr($x);   
        }    
        else  
        {   
           $val = intval(substr($val, 2), 16);   
            if ($val < 0x7F) // 0000-007F   
            {   
                $c .= chr($val);   
            } elseif ($val < 0x800) // 0080-0800   
            {   
                $c .= chr(0xC0 | ($val / 64));   
                $c .= chr(0x80 | ($val % 64));   
            }    
            else // 0800-FFFF   
            {   
                $c .= chr(0xE0 | (($val / 64) / 64));   
                $c .= chr(0x80 | (($val / 64) % 64));   
                $c .= chr(0x80 | ($val % 64));   
            }    
        }    
    }    
  
    return $c;   
} 
?>