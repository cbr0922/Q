<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
session_start();
include ("../../configs.inc.php");
$username = $_GET['username'];
$token=$_GET['token'];
$sh = $_GET['sh'];
if ($username!="" && $token!="" && $sh !=""){
	if($token == "d96ef98d76a64c2c9d7e5a0d819efacd"){
		if($sh == md5($username . $token))	{
			$Query = $DB->query("select  user_id from `{$INFO[DBPrefix]}user` where username='".trim($username)."' limit 0,1");
			$Num   = $DB->num_rows($Query);	
			if ($Num<=0){
				$return_array['result'] = "1";
				echo JSON($return_array);
				exit;
			}else{
				$Result = $DB->fetch_array($Query);	
				$user_id = $Result['user_id'];
				$member_grouppoint = $FUNCTIONS->Grouppoint(intval($user_id));
				if ($member_grouppoint>0){
					$return_array['result'] = "3";
					$return_array['point'] = $member_grouppoint;
					echo JSON($return_array);
					exit;
				}else{
					$return_array['result'] = "2";
					echo JSON($return_array);
					exit;
				}
			}
		}
	}
}

function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
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

function checkFanid($fanid){
	global $DB,$INFO;
	$sid = 0;
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}shopinfo` where fanid=".intval($fanid)." limit 0,1");
	$Num   = $DB->num_rows($Query);
	if($Num>0){
		$Result= $DB->fetch_array($Query);	
		$sid = intval($Result['sid']);
	}
	return $sid;
}

function fomatstring($str){
	$str = str_replace("\"","",$str);
	return $str;
}
?>
