<?php

include ("../../configs.inc.php");
include ("global.php");

function dencrypt($string, $isEncrypt = true, $key = '')
{

	$key = '23rcafgdf2e';    //加密密钥
	$dynKey = $isEncrypt ? hash('sha1', microtime(true)) : substr($string, 0, 40);    
	$fixedKey = hash('sha1', $key);


	$dynKeyPart1 = substr($dynKey, 0, 20);
	$dynKeyPart2 = substr($dynKey, 20);
	$fixedKeyPart1 = substr($fixedKey, 0, 20);
	$fixedKeyPart2 = substr($fixedKey, 20);

	$key = hash('sha1', $dynKeyPart1 . $fixedKeyPart1 . $dynKeyPart2 . $fixedKeyPart2);

	$string = $isEncrypt ? $fixedKeyPart1 . $string . $dynKeyPart2 : (isset($string{339}) ? gzuncompress(base64_decode(substr($string, 40))) : base64_decode(substr($string, 40)));

	$n = 0;
	$result = '';
	$len = strlen($string);

	for ($n = 0; $n < $len; $n++)
	{
		$result .= chr(ord($string{$n}) ^ ord($key{$n % 40}));
	}

	return $isEncrypt ? $dynKey . str_replace('=', '_', base64_encode($n > 299 ? gzcompress($result) : $result)) : substr($result, 20, -20);    
}


/**
* 函數：提供給RPC客戶端調用的函數
* 參數：
* $method 客戶端需要調用的函數
* $params 客戶端需要調用的函數的參數數組
* 返回：返回指定調用結果
*/
function rpc_server_func($method, $params) {
$parameter = $params[0];
   if ($parameter == "get"){
       $return = "This data by get method";
       $return = dencrypt( $return, true );
   }else{
       $return = "Not specify method or params";
       $return = dencrypt( $return, true );
   }
   return $return;
}


function rpc_ping_func( $method, $params ){
	$result = NULL;
	$var0 = dencrypt( $params[0], false );

	if( $var0 == "ping" ){
		$result = "pong";
	} 

	return dencrypt($result,true);
}


function rpc_list_latest_orders_func( $methods, $params ){
	$result = array();
	$var0 = dencrypt( $params[0], false );
	$var1 = dencrypt( $params[1], false );

	global $DB,$INFO;
	$query = "SELECT order_id, totalprice, createtime "
		." FROM ntssi_order_table "
		." WHERE 1";

	$gResult = $DB->query( $query );
	$num_row = $DB->num_rows($gResult);
	$total_price = 0;
	if( $num_row > 0 ){
		while ( $gRow = $DB->fetch_array($gResult) ){
			$total_price += $gRow['totalprice'];
		}
	}

	$result = array( 'count' => $num_row, 'total' => $total_price );

	return dencrypt( json_encode($result), true );
}

function rpc_list_latest_members_func( $methods, $params ){
	$result = array();
	$var0 = dencrypt( $params[0], false );
	$var1 = dencrypt( $params[1], false );

	global $DB,$INFO;
	$query = "SELECT user_id "
		." FROM ntssi_user "
		." WHERE 1";

	$gResult = $DB->query( $query );
	$num_row = $DB->num_rows($gResult);
	$total_member = 0;
	if( $num_row > 0 ){
		while ( $gRow = $DB->fetch_array($gResult) ){
			$total_member += 1;
		}
	}

	$result = array( 'count' => $num_row, 'total' => $total_member );

	return dencrypt( json_encode($result), true );
}


$xmlrpc_server = xmlrpc_server_create();

xmlrpc_server_register_method($xmlrpc_server, "rpc_server", "rpc_server_func");
xmlrpc_server_register_method($xmlrpc_server, "ping", "rpc_ping_func");
xmlrpc_server_register_method($xmlrpc_server, "list_latest_orders", "rpc_list_latest_orders_func");
xmlrpc_server_register_method($xmlrpc_server, "list_latest_members", "rpc_list_latest_members_func");

$request = $HTTP_RAW_POST_DATA;
$xmlrpc_response = xmlrpc_server_call_method($xmlrpc_server, $request, null);
header("Content-Type: text/xml");
echo $xmlrpc_response;
xmlrpc_server_destroy($xmlrpc_server);

?>
