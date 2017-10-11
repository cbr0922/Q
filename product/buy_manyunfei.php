<?php
include("../configs.inc.php");
header("Content-type: text/html; charset=utf-8");
$gid = intval($_GET['gid']);
$saleid = $_GET['bid'];
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
				if ($_GET['size']!="" || $_GET['color']!=""){
					$Sql_s      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($gid) . " and size='" . ($_GET['size']) . "' and color = '" . ($_GET['color']) . "'";
					$Query_s    = $DB->query($Sql_s);
					$Nums_s      = $DB->num_rows($Query_s);	
					if ($Nums_s>0){
						$Rs_s=$DB->fetch_array($Query_s);
						echo $Rs_s['storage'];	
					}else{
						echo 0;	
					}
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
			if ($storage > 0){
				$goods_array = $_COOKIE['mangoods'][$saleid];
				$goods_color_array = $_COOKIE['mangoods_color'][$saleid];
				$goods_size_array = $_COOKIE['mangoods_size'][$saleid];
				$goods_count_array = $_COOKIE['mangoods_count'][$saleid];
						$goodscount = count($_COOKIE['mangoods'][$saleid]);
						$flag = 0;
						/*
						if (isset($_COOKIE['mangoods'][$saleid])){
							foreach($_COOKIE['mangoods'][$saleid] as $k=>$v){
								if ($v == intval($gid) && phpUnescape($_GET['color'])==$_COOKIE['mangoods_color'][$saleid][$k] && phpUnescape($_GET['size'])==$_COOKIE['mangoods_size'][$saleid][$k]){
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
						if (isset($_COOKIE['mangoods'][$saleid])){
							
							foreach($_COOKIE['mangoods'][$saleid] as $k=>$v){
								
								setcookie("mangoods[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
								setcookie("mangoods_color[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
								setcookie("mangoods_size[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
							}
						}
						*/
						
						
						$j = 0;
						$maxkey = 0;
						$ifhave = 0;
						if ($flag == 0){
							if (isset($goods_array)){
								if (in_array(intval($gid),$goods_array)){
									foreach($goods_array as $k=>$v){
										if ($gid==$v){
											setcookie("mangoods[" . $saleid . "][" . $k . "]", intval($gid),time()+60*60*24,"/");
							setcookie("mangoods_color[" . $saleid . "][" . $k . "]", ($_GET['color']),time()+60*60*24,"/");
							setcookie("mangoods_size[" . $saleid . "][" . $k . "]", ($_GET['size']),time()+60*60*24,"/");
											setcookie("mangoods_count[" . $saleid . "][" . $k . "]", intval($_GET['count']),time()+60*60*24,"/");	
										}
									}
									$ifhave = 1;
								}
							}
							
							if($ifhave ==0){
								if (isset($goods_array)){
									foreach($goods_array as $k=>$v){
										$maxkey = $k;
									}
								}
								$maxkey++;
								setcookie("mangoods[" . $saleid . "][" . $maxkey . "]", intval($gid),time()+60*60*24,"/");
								setcookie("mangoods_color[" . $saleid . "][" . $maxkey . "]", ($_GET['color']),time()+60*60*24,"/");
								setcookie("mangoods_size[" . $saleid . "][" . $maxkey . "]", ($_GET['size']),time()+60*60*24,"/");
								setcookie("mangoods_count[" . $saleid . "][" . $maxkey . "]", intval($_GET['count']),time()+60*60*24,"/");
							}
							echo "1";
							
							//echo $maxkey;
							//print_r($_COOKIE['mangoods']);
						}	
					}
		}
	}
}

if ($_GET['act'] == "del"){ 
	if (isset($_COOKIE['mangoods'][$saleid])){
		$i = 0;
		$goods_array = $_COOKIE['mangoods'][$saleid];
		$goods_color_array = $_COOKIE['mangoods_color'][$saleid];
		$goods_size_array = $_COOKIE['mangoods_size'][$saleid];
		$goods_count_array = $_COOKIE['mangoods_count'][$saleid];
		$ii = 0;
		foreach($_COOKIE['mangoods'][$saleid] as $k=>$v){
			if ($ii == 0){
				$j = $k;	
			}
			if ($v == intval($gid) ){
				/*
				array_splice($goods_array,$i,1);
				array_splice($goods_color_array,$i,1);
				array_splice($goods_size_array,$i,1);
				*/
				$goods_array[$k] = 0;
				$goods_color_array[$k] = 0;
				$goods_size_array[$k] = 0;
				$goods_count_array[$k] = 0;
				setcookie("mangoods[" . $saleid . "][" . $k . "]", 0,time()-10,"/");
				setcookie("mangoods_color[" . $saleid . "][" . $k . "]", 0,time()-10,"/");
				setcookie("mangoods_size[" . $saleid . "][" . $k . "]", 0,time()-10,"/");
				setcookie("mangoods_count[" . $saleid . "][" . $k . "]", 0,time()-10,"/");
			}
			/*
			setcookie("mangoods[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			setcookie("mangoods_color[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			setcookie("mangoods_size[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			setcookie("mangoods_count[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			*/
			$i++;
			$ii++;
		}
		//print_r($_COOKIE['mangoods']);
		//$j = 0;
		foreach($goods_array as $k=>$v){
			if ($goods_array[$k]>0){
				setcookie("mangoods[" . $saleid . "][" . $j . "]", $goods_array[$k],time()+60*60*24,"/");
				setcookie("mangoods_color[" . $saleid . "][" . $j . "]", $goods_color_array[$k],time()+60*60*24,"/");
				setcookie("mangoods_size[" . $saleid . "][" . $j . "]", $goods_size_array[$k],time()+60*60*24,"/");
				setcookie("mangoods_count[" . $saleid . "][" . $j . "]", $goods_count_array[$k],time()+60*60*24,"/");
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