<?php
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");
$gid = intval($_GET['gid']);
if ($_GET['color'] == "undefined")
	$_GET['color'] = "";
if ($_GET['size'] == "undefined")
	$_GET['size'] = "";
//echo $_GET['color'];
if ($_GET['act'] == "checkstorage"){
	
	if ($gid>0){
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
		echo $storage_array[0];exit;
	}
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