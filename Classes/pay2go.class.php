<?php
include("../configs.inc.php");
/*
$Sql = "select o.*,u.* from `{$INFO[DBPrefix]}order_table` o inner join `{$INFO[DBPrefix]}user` as u on u.user_id=o.user_id where o.order_id='64'";
$Query    = $DB->query($Sql);
$Rs = $DB->fetch_array($Query);
//print_r($Rs);
$allpay = new pay2go;
$allpay->postInvo($Rs);
*/

class pay2go{
	function postInvo($order){
		//return 0;
		global $INFO,$DB,$FUNCTIONS;
		//if(($order['ifinvoice']==0||$order['ifinvoice']==1||$order['ifinvoice']==3) && $order['iflocal']==1){
			//print_r($order);
			//if($order['invoice_num']!="")
			//	$print = 1;
			//else
			//	$print = 0;
			if($order['invoice_donate']!=""){
				$Donation = 1;
				$LoveCode = $order['invoice_donate'];
				$CarrierType = "";
			}else
				$Donation = 0;
			if($order['invoice_print']=="yes" || $order['invoice_num']!="")
				$PrintFlag = "Y";
			else
				$PrintFlag = "N";
			
			if($order['ifinvoice']==1)
				$Category = "B2B";
			else
				$Category = "B2C";
			$discount_totalPrices = $order['discount_totalPrices'];
			$totalprice = $order['totalprice'];
			$transport_price = $order['transport_price'];
			$saleoff = $discount_totalPrices/$totalprice;
			$saleoffprice = $totalprice-$discount_totalPrices;
			$d_Sql = "select * from `{$INFO[DBPrefix]}order_detail` where order_id='" . $order['order_id'] . "'";
			$d_Query    = $DB->query($d_Sql);
			$i = 0;
			while ($d_Rs = $DB->fetch_array($d_Query)){
				$ItemName .= $flag . ($d_Rs['goodsname']);
				$ItemCount .= $flag . $d_Rs['goodscount'];
				if($d_Rs['unit']=="")
					$d_Rs['unit'] = "件";
				$ItemWord .= $flag . ($d_Rs['unit']);
				$ItemPrice .= $flag . $d_Rs['price'];
				$ItemTaxType .= $flag . "1";
				//if($saleoffprice>0){
					//if($saleoffprice<=$d_Rs['goodscount']*$d_Rs['price']){
						//$ItemAmount .= $flag . ($d_Rs['goodscount']*$d_Rs['price']-$saleoffprice);
						//$saleoffprice = 0;
					//}else{
						//$ItemAmount .= $flag . "0";
						//$saleoffprice = $saleoffprice - ($d_Rs['goodscount']*$d_Rs['price']);
					//}
				//}
				//else{
					$ItemAmount .= $flag . ($d_Rs['goodscount']*$d_Rs['price']);	
				//}
				//$ItemAmount .= $flag . round($d_Rs['goodscount']*$d_Rs['price']*$saleoff,0);
				//$Amount[$i] = round($d_Rs['goodscount']*$d_Rs['price']*$saleoff,0);
				$flag = "|";
				$i++;
			}
			//$t = 0;
			//foreach($Amount as $k=>$v){
			//	$t += $v;	
			//}
			//$Amount[0] = $Amount[0] + ($discount_totalPrices-$t);
			if($transport_price>0){
				$ItemName .= $flag . ("運費");
				$ItemCount .= $flag . "1";
				$ItemWord .= $flag . ("件");
				$ItemPrice .= $flag . $transport_price;
				$ItemTaxType .= $flag . "1";
				$ItemAmount .= $flag . $transport_price;
			}
			if($saleoffprice>0){
				$ItemName .= $flag . ("折扣");
				$ItemCount .= $flag . "1";
				$ItemWord .= $flag . ("件");
				$ItemPrice .= $flag . "-" .$saleoffprice;
				$ItemTaxType .= $flag . "1";
				$ItemAmount .= $flag . "-" .$saleoffprice;
			}
			$Sql = "SELECT  * FROM `{$INFO[DBPrefix]}user`  where user_id='".intval($order['user_id'])."' limit 0,1";
				$Query  = $DB->query($Sql);
				$Rs=$DB->fetch_array($Query);
			if($order['invoiceform']!="")
				$CustomerName = $order['invoiceform'];
			else{
				
				$CustomerName = $Rs['true_name'];
			}
			if($CustomerName=="")
				$print = 0;
			//$Amount[$i] = $transport_price;
			//$ItemAmount = implode("|",$Amount);
			$CarrierType = "";
			$CarrierNum = "";
			switch($order['pCarrierType']){
				case 3:
					$order['pCarrierType'] = 1;
					break;
				case 1:
					$order['pCarrierType'] = 2;
					break;
				case 2:
					$order['pCarrierType'] = 0;
			}
			if($LoveCode=="" && $order['invoice_num']==""){
				$CarrierType = $order['pCarrierType'];
				if($CarrierType==2)
					$CarrierNum = rawurlencode($order['receiver_email']);
				else
					$CarrierNum = rawurlencode($order['pCarrierNum']);
			}
			$Time = time();
			$curl_parameters = array(
				"RespondType" => "JSON",
				"Version" => "1.3",
				"TimeStamp" => time(),
				"TransNum" => "",
				"MerchantOrderNo" => $order['order_serial'],
				"Status" =>1,
				"CreateStatusTime" => "",
				"Category" => $Category,
				"BuyerName" => ($CustomerName),
				"BuyerUBN" => $order['invoice_num'],
				"BuyerAddress" => $Rs['addr'],
				"BuyerEmail" => ($order['receiver_email']),
				"CarrierType" =>$CarrierType,
				"CarrierNum" =>$CarrierNum ,
				"LoveCode" => $LoveCode,
				"PrintFlag" => $PrintFlag,
				"TaxType" => "1",
				"TaxRate" => 5,
				"Amt" => round(($order['discount_totalPrices']+$transport_price)*0.95,0),
				"TaxAmt" => $order['discount_totalPrices']+$transport_price- round(($order['discount_totalPrices']+$transport_price)*0.95,0),
				"TotalAmt" => ($order['discount_totalPrices']+$transport_price),
				"ItemName" => $ItemName,
				"ItemCount" => $ItemCount,
				"ItemUnit" => $ItemWord,
				"ItemPrice" => $ItemPrice,
				"ItemAmt" => $ItemAmount,
				"ItemTaxType" => $ItemTaxType,
			);
			$post_data_str = http_build_query($curl_parameters);//轉成字串排列 
			$key = $INFO['pay2go_HashKey']; //商店專屬串接金鑰HashKey值 
			$iv = $INFO['pay2go_HashIV']; //商店專屬串接金鑰HashIV值 
			$post_data = trim(bin2hex(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $this->addpadding($post_data_str), MCRYPT_MODE_CBC, $iv))); //加密
			$url = "https://cinv.pay2go.com/API/invoice_issue"; 
			$MerchantID = $INFO['pay2go_MerchantID']; //商店代號 
			$transaction_data_array = array( //送出欄位
											"MerchantID_" =>$INFO['pay2go_MerchantID'], 
											"PostData_" => $post_data ); 
			$transaction_data_str = http_build_query($transaction_data_array); 
			$result = $this->curl_work($url, $transaction_data_str);
			//print_r($curl_parameters);
			//print_r($transaction_data_array);
			//echo $result['web_info'];
			 $returns = (array)json_decode($result['web_info']);
			// print_r( $returns);
			
			//exit;
			$Creatfile ="log/I".$order['order_serial']."-" . time();
			$fh = fopen( $Creatfile.'.txt', 'w+' );
			@chmod ($Creatfile.'.txt',0777);
			foreach ($result as $key => $value) {
				$value = $value;
				$req .= "&$key=$value";
			}
			foreach ($returns as $key => $value) {
				$value = $value;
				$req .= "&$key=$value";
			}
			$req .= $post_data_str;
			fputs ($fh, $req, strlen($req) );
			fclose($fh);
			 if($returns['Status']=="SUCCESS"){
				  $myreturns = (array)json_decode($returns['Result']);
				 $DB->query("update `{$INFO[DBPrefix]}order_table` set invoice_code='" . $myreturns['InvoiceNumber'] . "',invoice_date='" . $myreturns['CreateTime'] . "' where order_id = '" . $order['order_id'] . "'");
			// print_r($ary4);exit;
			 
			 	return 1;
			 }else{
				return 0; 
			 }
		//}	
	}
	
	function addpadding($string, $blocksize = 32) { 
		$len = strlen($string); 
		$pad = $blocksize - ($len % $blocksize); 
		$string .= str_repeat(chr($pad), $pad); 
		return $string;
	} 
	function curl_work($url = "", $parameter = "") { 
		$curl_options = array( 
							  CURLOPT_URL => $url, 
							  CURLOPT_HEADER => false, 
							  CURLOPT_RETURNTRANSFER => true, 
							  CURLOPT_USERAGENT => "Google Bot", 
							  CURLOPT_FOLLOWLOCATION => true, 
							  CURLOPT_SSL_VERIFYPEER => FALSE, 
							  CURLOPT_SSL_VERIFYHOST => FALSE, 
							  CURLOPT_POST => "1", 
							  CURLOPT_POSTFIELDS => $parameter ); 
		$ch = curl_init(); 
		curl_setopt_array($ch, $curl_options);
		$result = curl_exec($ch); 
		$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
		$curl_error = curl_errno($ch); 
		curl_close($ch); 
		$return_info = array( 
							 "url" => $url, 
							 "sent_parameter" => $parameter, 
							 "http_status" => $retcode, 
							 "curl_error_no" => $curl_error, 
							 "web_info" => $result ); 
		return $return_info; 
	}
}
?>