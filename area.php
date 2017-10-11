<?php
error_reporting(7);
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
header('Content-Type: text/x-json; charset=utf-8"');

include (dirname(__FILE__)."/configs.inc.php");
include ("global.php");
//$area_array = array();
$list = 0;
//echo "{";
//function getArea($top_id=0,$selectvalue="",$level=0){
	//global $DB,$INFO,$area_array,$list;
	$gResult = $DB->query("select * from `{$INFO[DBPrefix]}area` where top_id=0");
	$num_row = $DB->num_rows($gResult);
	if($num_row>0){
		$i = 0;
		while ($gRow =  $DB->fetch_array($gResult)){
			//$area_array[$i]['text'] = $gRow['areaname'];
			$gResult1 = $DB->query("select * from `{$INFO[DBPrefix]}area` where top_id='" .$gRow['area_id']  . "'");
			$num_row1 = $DB->num_rows($gResult1);
			if($num_row1>0){
				$j = 0;
				while ($gRow1 =  $DB->fetch_array($gResult1)){
					$gResult2 = $DB->query("select * from `{$INFO[DBPrefix]}area` where top_id='" .$gRow1['area_id']  . "'");
					$num_row2 = $DB->num_rows($gResult2);
					if($num_row2>0){
						$j = 0;
						while ($gRow2 =  $DB->fetch_array($gResult2)){
							$area_array[$gRow['areaname']][$gRow1['areaname']] = $gRow2['areaname'];
						}	
					}
					$j++;
				}
				
			}
			$i++;
			
		}
		
	
	}
//}
//getArea(0,"",0);
//$new_array = array();
//$i = 0;
//foreach($area_array as $k=>$v){
//	foreach($v as $kk=>$vv){
//		if($k == 0)
//			$new_array[$vv] = $area_array[$kk];
		
//	}
//}
//print_r($area_array);
echo JSON($area_array);
function arrayRecursive($array, $function, $apply_to_keys_also = false)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) arrayRecursive($array[$key], $function, $apply_to_keys_also);
        else $array[$key] = $function($value);

        if ($apply_to_keys_also && is_string($key)) { $new_key = $function($key); if ($new_key != $key) { $array[$new_key] = $array[$key]; unset($array[$key]); } }
    }
}
function JSON($array) {
    arrayRecursive($array, 'urlencode', true);
    $json = jsonEncode($array); // 或者 $json = php_json_encode($array);
    return urldecode($json);
}

function jsonEncode($var) {
    if (function_exists('json_encode')) {
        return json_encode($var);
    } else {
        switch (gettype($var)) {
            case 'boolean':
                return $var ? 'true' : 'false'; // Lowercase necessary!
            case 'integer':
            case 'double':
                return $var;
            case 'resource':
            case 'string':
                return '"'. str_replace(array("\r", "\n", "<", ">", "&"),
                    array('\r', '\n', '\x3c', '\x3e', '\x26'),
                    addslashes($var)) .'"';
            case 'array':
                // Arrays in JSON can't be associative. If the array is empty or if it
                // has sequential whole number keys starting with 0, it's not associative
                // so we can go ahead and convert it as an array.
                if (empty ($var) || array_keys($var) === range(0, sizeof($var) - 1)) {
                    $output = array();
                    foreach ($var as $v) {
                        $output[] = jsonEncode($v);
                    }
                    return '[ '. implode(', ', $output) .' ]';
                }
                // Otherwise, fall through to convert the array as an object.
            case 'object':
                $output = array();
                foreach ($var as $k => $v) {
                    $output[] = jsonEncode(strval($k)) .': '. jsonEncode($v);
                }
                return '{ '. implode(', ', $output) .' }';
            default:
                return 'null';
        }
    }
}

//echo "}";
?>
