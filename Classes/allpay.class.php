<?php
include("../configs.inc.php");
/*
$Sql = "select o.*,u.* from `{$INFO[DBPrefix]}order_table` o inner join `{$INFO[DBPrefix]}user` as u on u.user_id=o.user_id where o.order_id='475'";
$Query    = $DB->query($Sql);
$Rs = $DB->fetch_array($Query);
//print_r($Rs);
$allpay = new allPay;
$allpay->postInvo($Rs);
*/
class allPay{
	function postInvo($order){
		//return 0;
		global $INFO,$DB;
		if($order['ifinvoice']!=1 ){
			$Sql  = "select * from `{$INFO[DBPrefix]}user` u where u.user_id='".trim($order['user_id'])."'  limit 0,1";
			$Query = $DB->query($Sql);
			$Num   = $DB->num_rows($Query);
			$Result= $DB->fetch_array($Query);
			$memberno = $Result['memberno'];
			//print_r($order);
			//if($order['invoice_num']!="")
			//	$print = 1;
			//else
			//	$print = 0;
			if($order['invoice_donate']!=""){
				$Donation = 1;
				$LoveCode = $order['invoice_donate'];
			}else
				$Donation = 0;
			if($order['invoice_print']=="yes" || $order['invoice_num']!="")
				$print = 1;
			else
				$print = 0;
			if($order['ifinvoice']==1)
				$InvType = "01";
			else
				$InvType = "02";
			$discount_totalPrices = $order['discount_totalPrices'];
			$totalprice = $order['totalprice'];
			$transport_price = $order['transport_price'];
			$saleoff = $discount_totalPrices/$totalprice;
			$saleoffprice = $totalprice-$discount_totalPrices;
			$d_Sql = "select * from `{$INFO[DBPrefix]}order_detail` where order_id='" . $order['order_id'] . "' and packgid=0";
			$d_Query    = $DB->query($d_Sql);
			$i = 0;
			while ($d_Rs = $DB->fetch_array($d_Query)){
				$ItemName .= $flag . ($d_Rs['bn']);
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
				$ItemName .= $flag . ("ZZ00001");
				$ItemCount .= $flag . "1";
				$ItemWord .= $flag . ("件");
				$ItemPrice .= $flag . $transport_price;
				$ItemTaxType .= $flag . "1";
				$ItemAmount .= $flag . $transport_price;
			}
			if($saleoffprice>0){
				$ItemName .= $flag . ("DISCOUNT01");
				$ItemCount .= $flag . "1";
				$ItemWord .= $flag . ("件");
				$ItemPrice .= $flag . "-" .$saleoffprice;
				$ItemTaxType .= $flag . "1";
				$ItemAmount .= $flag . "-" .$saleoffprice;
			}
			$CarrierType = "";
			$CarrierNum = "";
			if($LoveCode=="" && $order['invoice_num']==""){
				$CarrierType = $order['pCarrierType'];
				if($CarrierType==1)
					$CarrierNum = "";
				elseif($CarrierType==3)
					$CarrierNum = "/" . $order['pCarrierNum'];
				else
					$CarrierNum = ($order['pCarrierNum']);
			}
			/*
			if($order['invoiceform']!="")
				$CustomerName = $order['invoiceform'];
			else
				$CustomerName = $Result['true_name'];
				*/
			if($CustomerName=="")
				$print = 0;
			//$Amount[$i] = $transport_price;
			//$ItemAmount = implode("|",$Amount);
			$Time = time();
			$curl_parameters = array(
				"MerchantID" => $INFO['allpay_MerchantID'],
				"RelateNumber" => $order['order_serial'],
				"TimeStamp" => $Time,
				"CustomerID" => $memberno,
				"CustomerIdentifier" => $order['invoice_num'],
				"CustomerName" => urlencode($CustomerName),
				"CustomerAddr" => urlencode($order['receiver_address']),
				"CustomerPhone" => $order['receiver_mobile'],
				"CustomerEmail" => urlencode($order['receiver_email']),
				"ClearanceMark" => "",
				"Print" => $print,
				"Donation" => $Donation,
				"LoveCode" => $LoveCode,
				"CarruerType" => $CarrierType,
				"CarruerNum" => $CarrierNum,
				"TaxType" => "1",
				"SalesAmount" => ($order['discount_totalPrices']+$transport_price),
				"ItemPrice" => $ItemPrice,
				"ItemAmount" => $ItemAmount,
				"ItemCount" => $ItemCount,
				"InvType" => $InvType,
			);
			ksort($curl_parameters);
			 $encode_str = "HashKey=". $INFO['allpay_HashKey']."&" . urldecode(http_build_query($curl_parameters))."&HashIV=".$INFO['allpay_HashIV'];
			$encode_str = urlencode($encode_str);
			$encode_str = strtolower($encode_str);
			$CheckMacValue = strtoupper(md5($encode_str));
			$curl_parameters["CheckMacValue"] = $CheckMacValue;
			$invoiceRemark = urlencode("");
			$curl_parameters["ItemName"] = $ItemName;
			$curl_parameters["ItemWord"] = $ItemWord;
			$curl_parameters["CustomerEmail"]=$order['receiver_email'];
			$curl_parameters["InvoiceRemark"] = $invoiceRemark;
			$curl_parameters["CustomerName"]  = urldecode($CustomerName);
			$curl_parameters["CustomerAddr"]  = urldecode($order['receiver_address']);
			ksort($curl_parameters);
			
			 $postdata = http_build_query($curl_parameters);
			$url = parse_url($INFO['allpay_url']);
			 $postdata = "POST ".$url['path']." HTTP/1.0\r\n".
				 "Content-Type: application/x-www-form-urlencoded\r\n".
				 "Host: ".$url['host']."\r\n".
				 "Content-Length: ".strlen($postdata)."\r\n".
				 "\r\n".
				 $postdata;
		
			$receivedata = "";
			 $fp = fsockopen ($url['host'], 80, $errno, $errstr, 90);
			 if(!$fp) { 
				  echo "$errstr ($errno)<br>\n";
			 }else{ 
				  fputs ($fp, $postdata);
		
				  do{ 
					   if(feof($fp)) break;
					   $tmpstr = fgets($fp,128);
					   $receivedata = $receivedata.$tmpstr;
				  }while(true);
				  fclose ($fp);
			 }
		//echo $receivedata;exit;
		 $req = "post:" . $postdata . "\r\n CheckMacValue:" . $CheckMacValues . " \r\n receive:" . $receivedata;
		$Creatfile ="log/".date("Ymd")."-" . $order['order_serial'];
		$fh = fopen( $Creatfile.'.txt', 'w+' );
		@chmod ($Creatfile.'.txt',0777);
		fputs ($fh, $req, strlen($req) );
		fclose($fh);//exit;
			  $receivedata = str_replace("\r","",trim($receivedata));
			 $isbody = false;
			 $httpcode = null;
			 $httpmessage = null;
			 $result = "";
			 $array1 = split("\n",$receivedata);
			 for($i=0;$i<count($array1);$i++){
				  if($i==0){
					   $array2 = split(" ",$array1[$i]);
					   $httpcode = $array2[1];
					   $httpmessage = $array2[2];
				  }else if(!$isbody){
					   if(strlen($array1[$i])==0) $isbody = true;
				  }else{
					   $result = $result.$array1[$i];
				  }
			 }
			 if($httpcode!="200"){
				 return -1;
		 	}
		 
			 $ary1 = split("&",$result);
			 for($i=0;$i<count($ary1);$i++){
				  $ary2 = split("=",$ary1[$i]);
				  $ary3 = split("=",$ary1[$i]);
				  //print_r($ary3);
				  $ary4[$ary3[0]] = $ary3[1];
				  $ary2[0]."=".$ary2[1]."\n";
			 }
			 if($ary4['RtnCode']==1){
				 $Result = $DB->query("update `{$INFO[DBPrefix]}order_table` set invoice_code='" . $ary4['InvoiceNumber'] . "',invoice_date='" . $ary4['InvoiceDate'] . "' where order_id = '" . $order['order_id'] . "'");
			// print_r($ary4);exit;
			 	return 1;
			 }else{
				return 0; 
			 }
		}	
	}
}
?>