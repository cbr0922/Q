<?php

/**
 * @file api.php
 *
 * @copyright Chun-Yu Lee (Mat) <matlinuxer2@gmail.com>. All rights reserved.
 */


require_once( dirname(__FILE__)."/common.php" );
require_once( dirname(__FILE__)."/../../configs.inc.php" );
require_once( dirname(__FILE__)."/../../Classes/function.php" );


// 查詢 POS -> smartshop
$xmlstr1 = "
<request>
  <store_id>0054</store_id>
  <member_no>TA032976</member_no>
  <member_name>王大明</member_name>
  <mobile>0921876543</mobile>
</request>
";


// 查詢 smartshop -> POS
/*
<response>
  <member>
    <member_no>TA032976</member_no>
    <member_name>王大明</member_name>
    <mobile>0921876543</mobile>
    <total>987</total>
  </member>
</response>
*/
$xmlstr2 = "
<response>
  <member>
    <member_no>%s</member_no>
    <member_name>%s</member_name>
    <mobile>%s</mobile>
    <total>%s</total>
  </member>
</response>
";

// 結帳 POS -> smartshop
$xmlstr3 = "
<request>
  <checksum>fef378ad</checksum>
  <store_id>0054</store_id>
  <member_no>TA032976</member_no>
  <member_name>王大明</member_name>
  <auth_code>0321</auth_code>
  <total>983</total>
  <amt>100</amt>
  <after>883</after>
  <invoice>00822415</invoice>
</request>
";

// 結帳 smartshop -> POS
/*
<response>
  <checksum>57f1b9b2</checksum>
  <store_id>0054</store_id>
  <member_no>TA032976</member_no>
  <total>983</total>
  <amt>100</amt>
  <after>883</after>
  <invoice>00822415</invoice>
  <status_code>000</status_code>
  <status_text>success</status_text>
</response>
*/
$xmlstr4 = "
<response>
  <checksum>%s</checksum>
  <store_id>%s</store_id>
  <member_no>%s</member_no>
  <total>%s</total>
  <amt>%s</amt>
  <after>%s</after>
  <invoice>%s</invoice>
  <status_code>%s</status_code>
  <status_text>%s</status_text>
</response>
";


$xmlstr6 = "
<response>
  <item>
    <sn>%s</sn>
    <limit>%s</limit>
  </item>
</response>
";

function process_request_query2( $xmlstr ){
	$xml = new SimpleXMLElement($xmlstr);

	$sn_ary = array();
	$result    = $xml->xpath('/request/item/sn');

	for( $i=0; $i < count( $result ); $i++ ){
		array_push( $sn_ary, trim($result[$i]) );
	}	
	
	$res['sn'] = $sn_ary;

	return $res;
}

function process_request_query( $xmlstr ){
	$xml = new SimpleXMLElement($xmlstr);

	$res['store_id']    = $xml->xpath('/request/store_id');
	$res['member_no']   = $xml->xpath('/request/member_no');
	$res['member_name'] = $xml->xpath('/request/member_name');
	$res['mobile']      = $xml->xpath('/request/mobile');

	foreach( $res as $k => $v ){
		$res[$k] = trim($v[0]);
	}

	return $res;
}

function process_request_checkout( $xmlstr ){
	$xml = new SimpleXMLElement($xmlstr);

	$res['checksum']    = $xml->xpath('/request/checksum');
	$res['store_id']    = $xml->xpath('/request/store_id');
	$res['member_no']   = $xml->xpath('/request/member_no');
	$res['member_name'] = $xml->xpath('/request/member_name');
	$res['auth_code']   = $xml->xpath('/request/auth_code');
	$res['total']       = $xml->xpath('/request/total');
	$res['amt']         = $xml->xpath('/request/amt');
	$res['after']       = $xml->xpath('/request/after');
	$res['invoice']     = $xml->xpath('/request/invoice');

	foreach( $res as $k => $v ){
		$res[$k] = trim($v[0]);
	}

	return $res;
}

function response_query( $data_ary ){
	global $xmlstr2;
	$result = "";

	foreach( $data_ary as $data ){
		$temp = sprintf( $xmlstr2,
				$data["member_no"],
				$data["member_name"],
				$data["mobile"],
				$data["total"]
				);
		$result = $result . "\n" . trim( $temp );
	}

	$result = str_replace( "", "", $result );
	$result = str_replace( "</response>\n<response>\n", "", $result );

	$result = trim( $result );
	echo $result;
}

function response_query2( $data_ary ){
	global $xmlstr6;
	$result = "";

	foreach( $data_ary as $sn => $limit ){
		$temp = sprintf( $xmlstr6, $sn, $limit );
		$result = $result . "\n" . trim( $temp );
	}

	$result = str_replace( "", "", $result );
	$result = str_replace( "</response>\n<response>\n", "", $result );

	$result = trim( $result );
	echo $result;
}

function response_checkout( $data ){
	global $xmlstr4;

	$result = sprintf( $xmlstr4,
			$data["checksum"],
			$data["store_id"],
			$data["member_no"],
			$data["total"],
			$data["amt"],
			$data["after"],
			$data["invoice"],
			$data["status_code"],
			$data["status_text"]
			);

	$result = trim( $result );
	echo $result;
}

function parse_header( $data ){
	$result = "";

	$xml = new SimpleXMLElement( $data );

	$chk_store_id    = ( $xml->xpath('/request/store_id')    != FALSE );
	$chk_member_no   = ( $xml->xpath('/request/member_no')   != FALSE );
	$chk_member_name = ( $xml->xpath('/request/member_name') != FALSE );
	$chk_mobile      = ( $xml->xpath('/request/mobile')      != FALSE );
	$chk_checksum    = ( $xml->xpath('/request/checksum')    != FALSE );
	$chk_auth_code   = ( $xml->xpath('/request/auth_code')   != FALSE );
	$chk_total       = ( $xml->xpath('/request/total')       != FALSE );
	$chk_amt         = ( $xml->xpath('/request/amt')         != FALSE );
	$chk_after       = ( $xml->xpath('/request/after')       != FALSE );
	$chk_invoice     = ( $xml->xpath('/request/invoice')     != FALSE );
	$chk_sn          = ( $xml->xpath('/request/item/sn')     != FALSE );

	/// @todo get the header action
	if( $chk_mobile ){
		$result = "query";
	}
	else if( $chk_checksum && $chk_auth_code && $chk_invoice ){
		$result = "checkout";
	}
	else if( $chk_sn ){
		$result = "query2";
	}

	return $result;
}

function check_content( $data ){
	// check content length
	$len = strlen( $data );
	if( $len < 50 || $len > 2048 ){ return false; }

	// check content has injection

	// check xml parsable
	libxml_use_internal_errors(true);
	try{
		$xmlToObject = new SimpleXMLElement($data);
	} catch (Exception $e){
		echo '<error>xml format error</error>';
		return false;
	}

	return true;
}

/**
 * 
 */
function process_payment( $store_id, $member_no, $auth_code, $total, $amt, $after, $invoice ){
	$FUNCTIONS = new FUNCTIONS();

	$ret_code = -1;
	$userid = get_user_id_by_memberno( $member_no );
	$buypoint = $FUNCTIONS->Buypoint($userid);

	$chk1 = false; // 檢查認證碼
	$chk2 = false; // 檢查數字金額是否正確
	$chk3 = false;
	$chk4 = false;

	// 檢查 $auth_code 是否吻合 1. 身份證後4碼 or 2. 生日後4碼
	$user_data = get_user_data_by_user_id( $userid );
	$birthday  = $user_data['born_date'];
	$certcode  = $user_data['certcode'];

	$ary = explode( "-",$birthday );
	$month = str_pad( $ary[1], 2, "0", STR_PAD_LEFT );
	$day   = str_pad( $ary[2], 2, "0", STR_PAD_LEFT );
	$auth1 = $month.$day; // 生日的後4碼
	$auth2 = substr( $certcode, -4, 4); // 身份證後4碼

	if( $auth_code == $auth1 || $auth_code == $auth2 ){
		$chk1 = true;
	}
	else {
		// 認證碼不符合
		$ret_code = -2;
		return $ret_code;
	}

	// 檢查數字金額是否符合
	if( ( $total >= 0 )
			// 先允許購物金退貨 // && ( $amt   >= 0 ) 
			&& ( $after >= 0 )
			&& ( $total == ( $amt + $after ) ) 
	  )
	{
		$chk2 = true;
	}
	else { 
		$ret_code = -3; 
		return $ret_code;
	}

	// 檢查發票號碼格式是否正常
	preg_match('/^[a-zA-Z][a-zA-Z]\d{8}/', $invoice, $matches);
	if( count( $matches ) == 1 ){
		$chk3 = true;
	}
	else{
		$ret_code = -4;
		return $ret_code;
	}

	// 檢查 store_id 是否正確
	$store_list = array( "0054", "9527" );
	if( in_array( $store_id, $store_list ) ){
		$chk4 = true;
	}
	else{
		// 商店代號不正確
		$ret_code = -5;
		return $ret_code;
	}

	// 參數檢查都通過，進入點數扣除程序
	if ( $chk1 && $chk2 && $chk3 && $chk4 ) {
		
		$point    = abs( $amt );
		$type     = $amt > 0 ? 1 : 0 ; 
		if( $type == 1 ){
			$content  = "POS 結帳扣款，發票:".strtoupper($invoice)."";
		}
		else if( $type == 0 ){
			$content  = "POS 退貨回款，發票:".strtoupper($invoice)."";
		}
		else{ 
			; // 例外狀況
		}
		$orderid  = 0;
		$sa_id    = 0;
		$sa_type  = 0;

		$ret = $FUNCTIONS->AddBuypoint($userid, $point, $type, $content, $orderid, $sa_id, $sa_type);
		if( $ret != TRUE ){
			$ret_code = -9; // 扣款函式 AddBuypoint 失敗
			return $ret_code;
		}
		
		$ret_code = 0;
	}

	return $ret_code;
}

function get_user_id_by_memberno( $memberno ){
	$result = NULL;

	$query = "SELECT * "
		." FROM `ntssi_user`"
		." WHERE `memberno`='$memberno' ";

	$db = Database::getInstance();
	$res = $db->query( $query );
	if( count($res) > 0 ){
		$result = $res[0]["user_id"];
	}

	return $result;
}

function get_user_data_by_user_id( $user_id ){
	$result = NULL;

	$query = "SELECT * "
		." FROM `ntssi_user`"
		." WHERE `user_id`='$user_id' ";

	$db = Database::getInstance();
	$res = $db->query( $query );
	if( count($res) > 0 ){
		$result = $res[0];
	}

	return $result;
}

function find_buypt_limit_by_bn( $sn ){
	return 0;
}

function search_member( $member_no, $member_name, $mobile )
{
	$FUNCTIONS = new FUNCTIONS();

	$result = array();

	if( $member_no == "" && $member_name == "" && $mobile == "" ){
		return $result;
	}

	$cond1 = " (
		REPLACE( REPLACE( REPLACE( tel, '-', '' ) , '(', '' ) , ')', '' ) LIKE '%".$mobile."%'
		OR REPLACE( REPLACE( REPLACE( other_tel, '-', '' ) , '(', '' ) , ')', '' ) LIKE '%".$mobile."%'
		)
		";
	$cond2 = "(
		REPLACE( true_name, ' ', '' ) LIKE '%".$member_name."%'
		)
		";
	$cond3 = " (
		memberno LIKE '".$member_no."'
		)
		";

	if( $mobile == "" ){ $cond1 = " 1=1 "; }
	if( $member_name == "" ){ $cond2 = " 1=1 "; }
	if( $member_no == "" ){ $cond3 = " 1=1 "; }

	$query = " SELECT * FROM `ntssi_user` "
		. " WHERE ".$cond1." AND ".$cond2." AND ".$cond3." LIMIT 5 ";
	$db = Database::getInstance();
	$res = $db->query( $query );
	foreach( $res as $row ){
		$data = array();
		$data['member_no']   = $row['memberno'];
		$data['member_name'] = $row['true_name'];
		$data['mobile']      = $row['other_tel'].",".$row['tel'];
		$data['total']       = intval( $FUNCTIONS->Buypoint( $row['user_id'] ) );
		array_push( $result, $data );
	}

	return $result;
}

function save_request( $input_data ){

}


function verify_payment( $checksum, $input_data ) {

	$txt = "";
	$txt .= $input_data["member_no"];
	$txt .= $input_data["member_name"];
	$txt .= $input_data["auth_code"];
	$txt .= $input_data["total"];
	$txt .= $input_data["amt"];
	$txt .= $input_data["after"];
	$txt .= $input_data["invoice"];

	$code = substr( md5( $txt ), 3, 6 );
	return ( $checksum == $code );
}

/**
 * 依購物金來排序, 由多到少
 * ( 以 callback function 方式配合 usort() 使用 )
 */
function cmp_buypoint( $a, $b ){
	if( $a['total'] <= $b['total'] ){
		return true;
	}
	else{
		return false;
	}
}

/*************************************************************************/
/*************************************************************************/

/// @todo prevent sql injection
/// @todo add ip restriction
if( isset( $_GET['XMLData'] ) ){                                            
        $data = $_GET['XMLData'];
}

if( isset( $_POST['XMLData'] ) ){
	$data = $_POST['XMLData'];
}

$isOk   = check_content( $data );
if( ! $isOk ){
	exit();
}

$action = parse_header( $data );

if( $action === "query" ){
	$input_data = process_request_query( $data );

	save_request( $input_data );

	$store_id    = $input_data['store_id'];
	$member_no   = $input_data['member_no'];
	$member_name = $input_data['member_name'];
	$mobile      = $input_data['mobile'];

	$members = search_member( $member_no, $member_name, $mobile );

	if( count( $members ) > 0 ){
		$output_data = array();
		foreach( $members as $item ){
			array_push( $output_data, $item );
		}
	}
	else{
		$output_data = array();
	}

	// 依購物金多少來作排序
	usort( $output_data, "cmp_buypoint" );

	$response = response_query( $output_data );
	echo $response;
}
else if( $action === "query2" ){
	$input_data = process_request_query2( $data );

	save_request( $input_data );

	$sn_ary  = $input_data['sn'];
	$output_data = array();
	foreach( $sn_ary as $sn ){

		$buypt_lmt = find_buypt_limit_by_bn( $sn );
		$output_data[$sn] = $buypt_lmt; 
	}

	$response = response_query2( $output_data );
	echo $response;
}
else if( $action === "checkout" ){
	$input_data = process_request_checkout( $data );

	save_request( $input_data );

	$checksum    = $input_data["checksum"];
	$store_id    = $input_data["store_id"];
	$member_no   = $input_data["member_no"];
	$member_name = $input_data["member_name"];
	$auth_code   = $input_data["auth_code"];
	$total       = $input_data["total"];
	$amt         = $input_data["amt"];
	$after       = $input_data["after"];
	$invoice     = $input_data["invoice"];

	// 先作 checksum 的驗證
	$isVerified = verify_payment( $checksum, $input_data );
	if( $isVerified ){
		$ret = process_payment( $store_id, $member_no, $auth_code, $total, $amt, $after, $invoice );
		if( $ret === 0 ){
			$output_data['checksum']    = $input_data['checksum'];
			$output_data['store_id']    = $input_data['store_id'];
			$output_data['member_no']   = $input_data['member_no'];
			$output_data['total']       = $input_data['total'];
			$output_data['amt']         = $input_data['amt'];
			$output_data['after']       = $input_data['after'];
			$output_data['invoice']     = $input_data['invoice'];
			$output_data['status_code'] = "0";
			$output_data['status_text'] = "success";
		}
		else{
			$output_data['status_code'] = "$ret";
			$output_data['status_text'] = "fail";
			if( true ){ // debug info
				if( $ret == -1 )      { $output_data['status_text'] = "fail"; }
				else if( $ret == -2 ) { $output_data['status_text'] = "auth_code不符合"; }
				else if( $ret == -3 ) { $output_data['status_text'] = "金額不符"; }
				else if( $ret == -4 ) { $output_data['status_text'] = "發票格式不符"; }
				else if( $ret == -5 ) { $output_data['status_text'] = "商店代號不符"; }
				else if( $ret == -9 ) { $output_data['status_text'] = "扣款函式失敗"; }
				else                  { $output_data['status_text'] = "unknown"; }
			}
		}
	}
	else{
			$output_data['status_code'] = "-99";
			$output_data['status_text'] = "reject";

	}

	$response = response_checkout( $output_data );
	echo $response;
}
else {
	echo "<error></error>";
}

?>

