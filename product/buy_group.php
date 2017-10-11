<?php
include("../configs.inc.php");
header("Content-type: text/html; charset=utf-8");
if ($_SESSION['user_id']=="" || empty($_SESSION['user_id'])){
	echo "-9";
	exit;
}
$gid = intval($_GET['gid']);
$saleid = $_GET['saleid'];
if ($_GET['color'] == "undefined")
	$_GET['color'] = "";
if ($_GET['size'] == "undefined")
	$_GET['size'] = "";
if ($_GET['act'] == "buy"){ 
	
	if ($gid>0 && $_GET['count']>0){
		
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}groupdetail` where gdid=".intval($gid)."  limit 0,1");
		$storage_array = array();
		$i = 0;
		$Result= $DB->fetch_array($Query);
		$goods_array  = explode(",",$Result['goodslist']);
		foreach($goods_array as $k=>$v){
			$Query_d = $DB->query("select * from `{$INFO[DBPrefix]}goods` where bn=".trim($v)."  limit 0,1");
			$Num_d   = $DB->num_rows($Query_d);
			if ($Num_d>0){
				$Result_d= $DB->fetch_array($Query_d);
				if ($Result_d['storage']>0){
					$storage_array[$i] = $Result_d['storage'];
					if ($_GET['size']!="" || $_GET['color']!=""){
						 $Sql_s      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($Result_d['gid']) . " and size='" . ($_GET['size']) . "' and color = '" . ($_GET['color']) . "'";
						$Query_s    = $DB->query($Sql_s);
						$Nums_s      = $DB->num_rows($Query_s);	
						if ($Nums_s>0){
							$Rs_s=$DB->fetch_array($Query_s);
							$storage_array[$i] = $Rs_s['storage'];
						}else{
							echo 0;	exit;
						}
					}else{
						$storage_array[$i] = $Result_d['storage'];
					}
				}else{
					echo "0";exit;
				}
			}else{
				echo "0";exit;
			}
			$i++;
		}
		sort($storage_array,SORT_NUMERIC);
		$storage  = $storage_array[0];
		if ($storage>0){
			if ($_GET['count']>$storage){
				$_GET['count']  = $storage;
			}
			$goods_array = $_COOKIE['groupgoods'][$saleid];
			$goods_count_array = $_COOKIE['groupgoods_count'][$saleid];
			$goodscount = count($_COOKIE['groupgoods'][$saleid]);
			$goods_size_array = $_COOKIE['groupgoods_size'][$saleid];
			$goods_color_array = $_COOKIE['groupgoods_color'][$saleid];
			$goods_buytype_array = $_COOKIE['groupgoods_buytype'][$saleid];
			$flag = 0;
			if (isset($goods_array))
				ksort($goods_array);
			if (isset($goods_count_array))
				ksort($goods_count_array);
						
						
			$j = 0;
			$maxkey = 0;
			$ifhave = 0;
			if ($flag == 0){
				if (isset($goods_array)){
					if (in_array(intval($gid),$goods_array)){
						foreach($goods_array as $k=>$v){
							if ($gid==$v){
								setcookie("groupgoods[" . $saleid . "][" . $k . "]", intval($gid),time()+60*60*24,"/");
								setcookie("groupgoods_count[" . $saleid . "][" . $k . "]", intval($_GET['count']),time()+60*60*24,"/");	
								setcookie("groupgoods_size[" . $saleid . "][" . $k . "]", trim($_GET['size']),time()+60*60*24,"/");
								setcookie("groupgoods_color[" . $saleid . "][" . $k . "]", trim($_GET['color']),time()+60*60*24,"/");
								setcookie("groupgoods_buytype[" . $saleid . "][" . $k . "]", trim($_GET['buytype']),time()+60*60*24,"/");
							}
						}
						$ifhave = 1;
					}
				}
			}
			  if($ifhave ==0){
				  if (isset($goods_array)){
					  foreach($goods_array as $k=>$v){
						  $maxkey = $k;
					  }
				  }
				  $maxkey++;
				  setcookie("groupgoods[" . $saleid . "][" . $maxkey . "]", intval($gid),time()+60*60*24,"/");
				  setcookie("groupgoods_count[" . $saleid . "][" . $maxkey . "]", intval($_GET['count']),time()+60*60*24,"/");
				  setcookie("groupgoods_size[" . $saleid . "][" . $k . "]", trim($_GET['size']),time()+60*60*24,"/");
				  setcookie("groupgoods_color[" . $saleid . "][" . $k . "]", trim($_GET['color']),time()+60*60*24,"/");
				  setcookie("groupgoods_buytype[" . $saleid . "][" . $k . "]", trim($_GET['buytype']),time()+60*60*24,"/");
			  }
			  echo "1";exit;
			  
		  
	  }
	}
	//echo "1";exit;
}

if ($_GET['act'] == "del"){ 
	if (isset($_COOKIE['groupgoods'][$saleid])){
		$i = 0;
		$goods_array = $_COOKIE['groupgoods'][$saleid];
		$goods_count_array = $_COOKIE['groupgoods_count'][$saleid];
		$goods_size_array = $_COOKIE['groupgoods_size'][$saleid];
		$goods_color_array = $_COOKIE['groupgoods_color'][$saleid];
		$goods_buytype_array = $_COOKIE['groupgoods_buytype'][$saleid];
		$ii = 0;
		foreach($_COOKIE['groupgoods'][$saleid] as $k=>$v){
			if ($ii == 0){
				$j = $k;	
			}
			if ($v == intval($gid) ){
				$goods_array[$k] = 0;
				$goods_count_array[$k] = 0;
				setcookie("groupgoods[" . $saleid . "][" . $k . "]", 0,time()-10,"/");
				setcookie("groupgoods_count[" . $saleid . "][" . $k . "]", 0,time()-10,"/");
				 setcookie("groupgoods_size[" . $saleid . "][" . $k . "]", "",time()+60*60*24,"/");
				  setcookie("groupgoods_color[" . $saleid . "][" . $k . "]", "",time()+60*60*24,"/");
				  setcookie("groupgoods_buytype[" . $saleid . "][" . $k . "]", 0,time()+60*60*24,"/");
			}
			$i++;
			$ii++;
		}
		foreach($goods_array as $k=>$v){
			if ($goods_array[$k]>0){
				setcookie("groupgoods[" . $saleid . "][" . $j . "]", $goods_array[$k],time()+60*60*24,"/");
				setcookie("groupgoods_count[" . $saleid . "][" . $j . "]", $goods_count_array[$k],time()+60*60*24,"/");
				setcookie("groupgoods_size[" . $saleid . "][" . $k . "]", $goods_size_array[$k],time()+60*60*24,"/");
				  setcookie("groupgoods_color[" . $saleid . "][" . $k . "]", $goods_color_array[$k],time()+60*60*24,"/");
				  setcookie("groupgoods_buytype[" . $saleid . "][" . $k . "]", $goods_buytype_array[$k],time()+60*60*24,"/");
				$j++;
			}
			
		}
	}
	echo "1";
}

if ($_GET['act'] == "buytype"){ 
	if (isset($_COOKIE['groupgoods'][$saleid])){
		$i = 0;
		$goods_array = $_COOKIE['groupgoods'][$saleid];
		$goods_count_array = $_COOKIE['groupgoods_count'][$saleid];
		$goods_size_array = $_COOKIE['groupgoods_size'][$saleid];
		$goods_color_array = $_COOKIE['groupgoods_color'][$saleid];
		$goods_buytype_array = $_COOKIE['groupgoods_buytype'][$saleid];
		$ii = 0;
		foreach($_COOKIE['groupgoods'][$saleid] as $k=>$v){
			if ($v == intval($gid) ){
				 setcookie("groupgoods_buytype[" . $saleid . "][" . $k . "]", intval($_GET['buytype']),time()+60*60*24,"/");
			}
			$i++;
			$ii++;
		}
		
	}
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