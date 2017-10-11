<?php
class PayFunction{
	function CreatePay($paytype,$array,$ifphone=0){
		global $INFO,$DB;
		$Psql = "select p.*,pg.*,p.month as pgmonth from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.mid='" . $paytype . "' order by p.mid";
		$PQuery    = $DB->query($Psql);
		$PRs=$DB->fetch_array($PQuery);
		//print_r($PRs);
		//echo $paytype;
		if ($PRs['pid']==1)
			$return =  $this->TWPay($PRs,$array);
		if ($PRs['pid']==3 || $PRs['pid']==57)
			$return =  $this->LHPay($PRs,$array,$ifphone);
		if ($PRs['pid']==4 || $PRs['pid']==5 || $PRs['pid']==6)
			$return =  $this->ZXPay2($PRs,$array,$ifphone);
		if ($PRs['pid']==7)
			$return =  $this->ZYPay($PRs,$array);
		if ($PRs['pid']==8)
			$return =  $this->HNPay($PRs,$array);
		if ($PRs['pid']==9)
			$return =  $this->TXPay($PRs,$array);
		if ($PRs['pid']==10)
			$return =  $this->LJPay($PRs,$array);
		if ($PRs['pid']==11 || $PRs['pid']==12 || $PRs['pid']==13)
			$return = $this->LXPay($PRs,$array);
		if ($PRs['pid']==14)
			$return =  $this->Paypal($PRs,$array);
		if ($PRs['pid']==15)
			$return =  $this->alipay($PRs,$array);
		if ($PRs['pid']==17 || $PRs['pid']==18 || $PRs['pid']==50 || $PRs['pid']==51 || $PRs['pid']==52)
			$return =  $this->HYPay($PRs,$array);
		if ($PRs['pid']==19)
			$return =  $this->HZPay($PRs,$array);
		if ($PRs['pid']==21 || $PRs['pid']==22)
			$return =  $this->YYPay($PRs,$array);
		if ($PRs['pid']==24)
			$return =  $this->IEPay($PRs,$array);
		if ($PRs['pid']==25)
			$return =  $this->EZPay($PRs,$array);
		if ($PRs['pid']==26)
			$return =  $this->ECPay($PRs,$array);
		if ($PRs['pid']==29)
			$return =  $this->GTPay($PRs,$array);
		if ($PRs['pid']==30)
			$return =  $this->GTWPay($PRs,$array);
		if ($PRs['pid']==33)
			$return =  $this->AFBPay($PRs,$array);
		if ($PRs['pid']==39)
			$return =  $this->YSPay($PRs,$array);
		if ($PRs['pid']==38)
			$return =  $this->YSAtmPay($PRs,$array);
		if ($PRs['pid']==27)
			$return =  $this->HNAtmPay($PRs,$array);
		if ($PRs['pid']==42)
			$return =  $this->YSYlPay($PRs,$array,$ifphone);
		if ($PRs['pid']==41)
			$return =  $this->YSYlsPay($PRs,$array,$ifphone);
		if ($PRs['pid']==54)
			$return =  $this->ZfbPay($PRs,$array,$ifphone);
		if ($PRs['pid']==60)
			$return =  $this->ZHPay($PRs,$array);
		if ($PRs['pid']==62)
			$return =  $this->DyPay($PRs,$array,$ifphone);
		return $return;
	}	
	/**
	台灣里
	**/
	function TWPay($PRs,$array){
		global $INFO,$DB;
		$f1 = explode("|",$PRs['f1']);
		$Shop_I = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$Shop_II = $f2[1];
		$verify = md5(trim($Shop_I)."|".trim($PRs['shopcode'])."|".trim($array['order_serial'])."|".trim($array['total'])."|".trim($Shop_II));
		return "
		<form name=\"form_twpay\" id=\"form_twpay\" method=\"post\" action=\"" . $PRs['payurl'] . "\" >
		  <!--付款介面版本-->
		  <input type=\"hidden\" name=\"version\" value=\"1.0\">
		  <!--商店代号-->
		  <input type=\"hidden\" name=\"mid\" value=\"" . $PRs['shopcode'] . "\">
		  <!--分期贷款  ０为不分期-->
		  <input type=\"hidden\" name=\"iid\" value=\"" . intval($PRs['pgmonth']) . "\">
		  <!--定单号-->
		  <input type=\"hidden\" name=\"txid\" value=\"" . $array['order_serial'] . "\">
		  <!--总金额-->
		  <input type=\"hidden\" name=\"amount\" value=\"" . $array['total'] . "\">
		  <!--页面编码-->
		  <input type=\"hidden\" name=\"charset\" value=\"UTF-8\">
		  <!--验证码-->
		  <input type=\"hidden\" name=\"verify\" value=\"" . $verify . "\">
		  <!--回传地址-->
		  <input type=\"hidden\" name=\"return_url\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\">
		  <input type=\"hidden\" name=\"select_paymethod\" value=\"" . $PRs['payno'] . "\">
		  <!--建议付款方式-->
		  <input type=\"hidden\" name=\"prefer_paymethod\" value=\"" . $PRs['payno'] . "\">
		  <!--消费者名称-->
		  <input type=\"hidden\" name=\"cname\" value=\"" . $array['receiver_name'] . "\">
		  <input type=\"hidden\" name=\"caddress\" value=\"" . $array['receiver_address'] . "\">
		  <input type=\"hidden\" name=\"ctel\" value=\"" . $array['receiver_tele'] . "," . $array['receiver_mobile'] . "\">
		  <!--收货人名称-->
		  <input type=\"hidden\" name=\"xname\" value=\"" . $array['receiver_name'] . "\">
		  <!--收货人地址-->
		  <input type=\"hidden\" name=\"xaddress\" value=\"" . $array['receiver_address'] . "\">
		  <input type=\"hidden\" name=\"xtel\" value=\"" . $array['receiver_tele'] . "," . $array['receiver_mobile'] . "\">
		  <input type=\"hidden\" name=\"cemail\" value=\"" . $array['receiver_email'] . "\">
		  <input type=\"hidden\" name=\"language\" value=\"tchinese\">
		  <!--项目描述-->
		  <input type=\"hidden\" name=\"description\" value=\"\">
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉到台灣里付款系統頁面');
			setTimeout(\"document.all.form_twpay.submit()\",2000);
		</script>
		";
	}
	/**
	聯合信用刷卡
	**/
	function LHPay($PRs,$array,$ifphone=0){
		global $INFO,$DB;
		$f1 = explode("|",$PRs['f1']);
		$TerminalID = $f1[1];
		if($ifphone==1)
			$url = $INFO['site_url'] . "/mobile/cart_paylast.php";
		else
			$url = $INFO['site_url'] . "/shopping/cart_paylast.php";
		return "
		<form name=\"form_lhpay\" id=\"form_lhpay\" method=\"post\" ACTION=\"" . $url . "\">
		  <input type=\"hidden\" name=\"MerchantID\" value=\"" . $PRs['shopcode'] . "\">
		  <INPUT type=\"hidden\" name=\"TerminalID\" value=\"" . $TerminalID . "\">
		  <INPUT type=\"hidden\" name=\"Install\" value=\"" . intval($PRs['pgmonth']) . "\">
		  <INPUT type=\"hidden\" name=\"OrderID\" value=\"" . $array['order_serial'] . "\">
		  <INPUT type=\"hidden\" name=\"TransMode\" value=\"" . $PRs['payno'] . "\">
		  <INPUT type=\"hidden\" name=\"TransAmt\" value=\"" .$array['total']  . "\">
		  <INPUT type=\"hidden\" name=\"NotifyURL\" value=\"\" >
		</form>	
		<script language=\"javascript\">
			alert('請稍後，轉到信用卡付款頁面');
			setTimeout(\"document.all.form_lhpay.submit()\",2000);
		</script>
		";
	}
	
	/**
	中國信託
	**/
	function ZXPay($PRs,$array){
		global $INFO,$DB;
		include 'auth_mpi_mac8.php';
		$f1 = explode("|",$PRs['f1']);
		$MerchantID = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$TerminalID = $f2[1];
		$f3 = explode("|",$PRs['f3']);
		$Key = $f3[1];
		$Option = "";
		if ($PRs['pid'] == 5)
			$Option = $PRs['pgmonth'];
			
		$InMac = auth_in_mac($MerchantID,$TerminalID,$array['order_serial'],$array['total'],$PRs['payno'],$Option,$Key,0);
			
		$return = "
		<form name=\"form_zxpay\" id=\"form_zxpay\" method=\"post\" action=\"" . $PRs['payurl'] . "\" >";
		 if ($PRs['pid'] != 6){
		  $return .= "<input type=\"hidden\" name=\"merID\" value=\"" . $PRs['shopcode'] . "\">
		  <input type=\"hidden\" name=\"MerchantName\" value=\"" . $INFO['company_name'] . "\">";
		 }
		  $return .= "<input type=\"hidden\" name=\"MerchantID\" value=\"" . $MerchantID . "\">
		  <input type=\"hidden\" name=\"TerminalID\" value=\"" . $TerminalID . "\">
		  <input type=\"hidden\" name=\"AuthResURL\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\">
		  <input type=\"hidden\" name=\"lidm\" value=\"" . $array['order_serial'] . "\">";
		 if ($PRs['pid'] != 6){
		  $return .= "<input type=\"hidden\" name=\"OrderDetail\" value=\"orderno:" . $array['order_serial'] . "\">";
		 }
		  $return .= "<input type=\"hidden\" name=\"txType\" value=\"" . $PRs['payno'] . "\">";
		 if ($PRs['pid'] != 6){
		  $return .= "<input type=\"hidden\" name=\"AutoCap\" value=\"0\">";
		 }
		  $return .= "<input type=\"hidden\" name=\"purchAmt\" value=\"" . $array['total'] . "\">
		  <input type=\"hidden\" name=\"InMac\" value=\"" . $InMac . "\">";
		  if ($PRs['pid'] == 5){
		  $return .= "<input type=\"hidden\" name=\"NumberOfPay\" value=\"" . intval($PRs['pgmonth']) . "\">";
		  }
		  
		  if ($PRs['pid'] == 6){
		  $return .= "<input type=\"hidden\" name=\"WebATMAcct\" value=\"" . $array['total'] . "\">
		  <input type=\"hidden\" name=\"storeName\" value=\"" . $INFO['company_name'] . "\">
		  <INPUT type=\"hidden\" name=\"note\" type=\"text\">
		  <INPUT type=\"hidden\" name=\"charset\" type=\"text\" value=\"UTF-8\">
		  <input type=\"hidden\" name=\"billShortDesc\" value=\"orderno:" . $array['order_serial'] . "\">";
		  }
		$return .= "</form>
		<script language=\"javascript\">
			alert('請稍後，轉到中國信託付款頁面');
			setTimeout(\"document.all.form_zxpay.submit()\",2000);
		</script>
		";
		return $return;
	}
	/**
	中國信託
	**/
	function ZXPay2($PRs,$array,$ifphone=0){
		global $INFO,$DB;
		include 'auth_mpi_mac.php';
		$f1 = explode("|",$PRs['f1']);
		$MerchantID = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$TerminalID = $f2[1];
		$f3 = explode("|",$PRs['f3']);
		$Key = $f3[1];
		if($ifphone==1)
			$PRs['returnurl'] = "/mobile/receivePay_zx.php";
		if ($PRs['pid'] == 5){
			$Option = $PRs['pgmonth'];
			$txType = "1";
		}elseif($PRs['pid'] == 6){
			$txType = "9";
			$Option = "";	
		}else{
			$txType = "0";
			$x = "9";
			$Option = "1";		
		}
		if ($PRs['pid'] != 6){
			////$txType = "0";
			//$x = "9";
			//$Option = "1";		
			$InMac = auth_in_mac($MerchantID,$TerminalID,$array['order_serial'],$array['total'],$txType,$Option,$Key, "wonderfulselect",$INFO['site_url'] . $PRs['returnurl'],"orderno:" . $array['order_serial'],1,0,0);
			$URLEnc=get_auth_urlenc($MerchantID,$TerminalID,$array['order_serial'],$array['total'],$txType,$Option,$Key,"wonderfulselect",$INFO['site_url'] . $PRs['returnurl'],"orderno:" . $array['order_serial'],1,0,$InMac,0);
		}else{
			//$txType = "9";
			//$Option = "";
			$InMac = auth_in_mac($MerchantID,$TerminalID,$array['order_serial'],$array['total'],$txType,$Option,$Key, "wonderfulselect",$INFO['site_url'] . $PRs['returnurl'],"orderno:" . $array['order_serial'],1,0,0);
			$URLEnc = get_auth_atmurlenc($MerchantID,$TerminalID,$array['order_serial'],$array['total'],$txType,$Option,$Key,"wonderfulselect",$INFO['site_url'] . $PRs['returnurl'],"orderno:" . $array['order_serial'],"864530005290","",$InMac,0);	
		}
		
			

		
		//auth_in_mac($MerchantID,$TerminalID,$lidm,$purchAmt,$txType,$Option,$Key,$MerchantName,$AuthResURL,$OrderDetail,$AutoCap,$Customize,$debug);
		//echo $MerchantID."|".$TerminalID."|".$array['order_serial']."|".$array['total']."|".$txType."|".$Option."|".$Key;
			
		$return = "
		<form name=\"form_zxpay\" id=\"form_zxpay\" method=\"post\" action=\"" . $PRs['payurl'] . "\" >";
		  $return .= "<input type=\"hidden\" name=\"merID\" value=\"" . $PRs['shopcode'] . "\">
		  <input type=\"hidden\" name=\"URLEnc\" value=\"" . $URLEnc . "\">";
		$return .= "</form>
		<script language=\"javascript\">
			alert('請稍後，轉到中國信託付款頁面');
			setTimeout(\"document.getElementById('form_zxpay').submit()\",2000);
		</script>
		";
		$Creatfile ="log/".date("Ymd")."-" . time() . "-zx";
		$fh = fopen( $Creatfile.'.txt', 'w+' );
		@chmod ($Creatfile.'.txt',0777);
		fputs ($fh, $return, strlen($return) );
		fclose($fh);
		return $return;
	}
	/**
	彰銀
	**/
	function ZYPay($PRs,$array){
		global $INFO,$DB;
		$OrderNo = substr($array['order_serial'],1);
		$OrderNum = $PRs['shopcode'] . $OrderNo;
		return "
		<form name=\"form_zypay\" id=\"form_zypay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\">
		  <input type=\"hidden\" name=\"OrderNum\" value=\"" . $OrderNum . "\">
		  <input type=\"hidden\" name=\"Amt\" value=\"" . $array['total'] . "\">
		  <input type=\"hidden\" name=\"Url\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\">
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉到彰銀虛擬帳號付款頁面');
			setTimeout(\"document.all.form_zypay.submit()\",2000);
		</script>
		";
	}
	
	/**
	華南
	**/
	function HNPay($PRs,$array){
		global $INFO,$DB;
		$f1 = explode("|",$PRs['f1']);
		$TerminalID = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$merID = $f2[1];
		if ($PRs['payno'] == 1)
			$return = "<input type=\"hidden\" name=\"NumberOfPay\" value=\"" . $PRs['pgmonth'] . "\">";
		return "
		<form name=\"form_hnpay\" id=\"form_hnpay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\">
		  <input type=\"hidden\" name=\"MerchantID\" value=\"" . $PRs['shopcode'] . "\">
		  <input type=\"hidden\" name=\"TerminalID\" value=\"" . $TerminalID . "\">
		  <input type=\"hidden\" name=\"MerchantName\" value=\"" . $INFO['company_name'] . "\">
		  <input type=\"hidden\" name=\"lidm\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"merID\" value=\"" . $merID . "\">
		  <input type=\"hidden\" name=\"purchAmt\" value=\"" . $array['total'] . "\">
		  <input type=\"hidden\" name=\"txType\" value=\"" . $PRs['payno'] . "\">
		  " . $return . "
		  <input type=\"hidden\" name=\"AutoCap\" value=\"1\">
		  <input type=\"hidden\" name=\"AuthResURL\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\">
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉到華南線上刷卡付款頁面');
			setTimeout(\"document.all.form_hnpay.submit()\",2000);
		</script>
		";
	}
	/**
	臺新
	**/
	function TXPay($PRs,$array){
		global $INFO,$DB;
		$e17 = date("Ymd",time());
		if ($PRs['payno'] == "Auth"){
			$return = "<input type=\"hidden\" name=\"returnURL\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\">";	
		}else{
			$return = "<INPUT TYPE=\"hidden\" NAME=\"e17\" SIZE=10  value=\"" . $e17 . "\">
		  <input type=\"hidden\" name=\"returnURL\" value=\"" . $INFO['site_url'] . "\">";
		}
		return "
		<form name=\"form_txpay\" id=\"form_txpay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\">
		  <input type=\"hidden\" name=\"Type\" value=\"" . $PRs['payno'] . "\">
		  " . $return . "
		  <input type=\"hidden\" name=\"storeid\" value=\"" . $PRs['shopcode'] . "\">
		  <input type=\"hidden\" name=\"ordernumber\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"amount\" value=\"" . ($array['total']*100) . "\">
		  <input type=\"hidden\" name=\"currency\" value=\"TWD\">
		  <input type=\"hidden\" name=\"orderdesc\" value=\"訂單編號" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"depositflag\" value=\"1\">
		  <input type=\"hidden\" name=\"queryflag\" value=\"1\">
		  <input type=\"hidden\" name=\"merUpdateURL\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\">
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉到台新付款頁面');
			setTimeout(\"document.all.form_txpay.submit()\",2000);
		</script>
		";
	}
	
	/**
	綠界
	**/
	function LJPay($PRs,$array){
		/*
		global $INFO,$DB;
		$f1 = explode("|",$PRs['f1']);
		$CardNo = $f1[1];
		return "
		<form method=post action=\"" . $PRs['payurl'] . "\" name=\"form_ljpay\" id=\"form_ljpay\">
			<input type='hidden' name='client' value='" . $PRs['shopcode'] . "'>
			<input type='hidden' name='act' value='auth'>
			<input type=\"hidden\" name=\"card_no\" value=\"" . $CardNo . "\">
			<input type='hidden' name='od_sob' value='" . $array['order_serial'] . "'>
			<input type='hidden' name='email' value='" . $array['receiver_email'] . "'>
			<input type='hidden' name='amount' value='" . $array['total'] . "'>
			<input type='hidden' name='roturl' value='" . $INFO['site_url'] . $PRs['returnurl'] . "'>
			<input type='hidden' name='okurl' value='" . $INFO['site_url'] . $PRs['returnurl'] . "'>
			<input type='hidden' name='notifytype' value='0'>
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉到綠界付款頁面');
			setTimeout(\"document.all.form_ljpay.submit()\",2000);
		</script>
		";	
		*/
		global $INFO,$DB,$FUNCTIONS;
		$f1 = explode("|",$PRs['f1']);
		$HashKey = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$HashIV = $f2[1];
		
		if($PRs['payno']=="Alipay"){
			$alipay_str = "<input type=\"hidden\" name=\"AlipayItemName\" value=\"" . $array['order_serial'] . "\">
			<input type=\"hidden\" name=\"AlipayItemCounts\" value=\"1\">
			<input type=\"hidden\" name=\"AlipayItemPrice\" value=\"" . $array['total'] . "\">
			<input type=\"hidden\" name=\"Email\" value=\"" . $array['receiver_email'] . "\">
			<input type=\"hidden\" name=\"PhoneNo\" value=\"" . $array['receiver_tele'] ."/". $array['receiver_mobile']."\">
			<input type=\"hidden\" name=\"UserName\" value=\"" . $array['receiver_name'] . "\">
			";
			$check = (md5(strtolower(urlencode("HashKey=" . $HashKey . "&AlipayItemCounts=1&AlipayItemName=" . $array['order_serial'] . "&AlipayItemPrice=" . $array['total'] . "&ChoosePayment=" . $PRs['payno'] . "&ClientBackURL=" . $INFO['site_url'] . "/shopping/showorder.php?orderno=" . substr($array['order_serial'],0,13) . "&Email=" . $array['receiver_email'] . "&ItemName=" . $array['goods_name'] . "&MerchantID=" . $PRs['shopcode'] . "&MerchantTradeDate=" . date("Y/m/d H:i:s",$array['date']) . "&MerchantTradeNo=" . $array['order_serial'] . "&OrderResultURL=" . $INFO['site_url'] . $PRs['returnurl'] . "&PaymentType=aio&PhoneNo=" . $array['receiver_tele'] ."/". $array['receiver_mobile']."&ReturnURL=" . $INFO['site_url'] . $PRs['returnurl2'] . "&TotalAmount=" . $array['total'] . "&TradeDesc=" . $array['order_serial'] . "&UserName=" . $array['receiver_name'] . "&HashIV=" . $HashIV))));	
	
		}elseif($PRs['payno']=="ATM"){
			$alipay_str = "<input type=\"hidden\" name=\"ExpireDate\" value=\"3\">";
			$alipay_str .= "<input type=\"hidden\" name=\"PaymentInfoURL\" value=\"" .$INFO['site_url'] . $PRs['returnurl'] . "\">";
			$check = (md5(strtolower(urlencode("HashKey=" . $HashKey . "&ChoosePayment=" . $PRs['payno'] . "&ClientBackURL=" . $INFO['site_url'] . "/shopping/showorder.php?orderno=" . substr($array['order_serial'],0,13) . "&ExpireDate=3&ItemName=" . $array['goods_name'] . "&MerchantID=" . $PRs['shopcode'] . "&MerchantTradeDate=" . date("Y/m/d H:i:s",$array['date']) . "&MerchantTradeNo=" . $array['order_serial'] . "&OrderResultURL=" . $INFO['site_url'] . $PRs['returnurl'] . "&PaymentInfoURL=" .$INFO['site_url'] . $PRs['returnurl'] . "&PaymentType=aio&ReturnURL=" . $INFO['site_url'] . $PRs['returnurl2'] . "&TotalAmount=" . $array['total'] . "&TradeDesc=" . $array['order_serial'] . "&HashIV=" . $HashIV))));	
	
		}elseif($PRs['payno']=="Tenpay"){
			$alipay_str = "<input type=\"hidden\" name=\"ExpireTime\" value=\"" . date("Y/m/d H:i:s",$array['date']+3*24*60*60) . "\">";
			$check = (md5(strtolower(urlencode("HashKey=" . $HashKey . "&ChoosePayment=" . $PRs['payno'] . "&ClientBackURL=" . $INFO['site_url'] . "/shopping/showorder.php?orderno=" . substr($array['order_serial'],0,13) . "&ExpireTime=" . date("Y/m/d H:i:s",$array['date']+3*24*60*60) . "&ItemName=" . $array['goods_name'] . "&MerchantID=" . $PRs['shopcode'] . "&MerchantTradeDate=" . date("Y/m/d H:i:s",$array['date']) . "&MerchantTradeNo=" . $array['order_serial'] . "&OrderResultURL=" . $INFO['site_url'] . $PRs['returnurl'] . "&PaymentType=aio&ReturnURL=" . $INFO['site_url'] . $PRs['returnurl2'] . "&TotalAmount=" . $array['total'] . "&TradeDesc=" . $array['order_serial'] . "&HashIV=" . $HashIV))));	
	
		}elseif($PRs['payno']=="CVS"||$PRs['payno']=="BARCODE"){
			$alipay_str = "<input type=\"hidden\" name=\"Desc_1\" value=\"\">";
			$alipay_str .= "<input type=\"hidden\" name=\"Desc_2\" value=\"\">";
			$alipay_str .= "<input type=\"hidden\" name=\"Desc_3\" value=\"\">";
			$alipay_str .= "<input type=\"hidden\" name=\"Desc_4\" value=\"\">";
			$alipay_str .= "<input type=\"hidden\" name=\"PaymentInfoURL\" value=\"" .$INFO['site_url'] . $PRs['returnurl'] . "\">";
			$check = (md5(strtolower(urlencode("HashKey=" . $HashKey . "&ChoosePayment=" . $PRs['payno'] . "&ClientBackURL=" . $INFO['site_url'] . "/shopping/showorder.php?orderno=" . substr($array['order_serial'],0,13) . "&Desc_1=&Desc_2=&Desc_3=&Desc_4=&ItemName=" . $array['goods_name'] . "&MerchantID=" . $PRs['shopcode'] . "&MerchantTradeDate=" . date("Y/m/d H:i:s",$array['date']) . "&MerchantTradeNo=" . $array['order_serial'] . "&OrderResultURL=" . $INFO['site_url'] . $PRs['returnurl'] . "&PaymentInfoURL=" .$INFO['site_url'] . $PRs['returnurl'] . "&PaymentType=aio&ReturnURL=" . $INFO['site_url'] . $PRs['returnurl2'] . "&TotalAmount=" . $array['total'] . "&TradeDesc=" . $array['order_serial'] . "&HashIV=" . $HashIV))));
			//echo "HashKey=" . $HashKey . "&ChoosePayment=" . $PRs['payno'] . "&ClientBackURL=" . $INFO['site_url'] . "/shopping/showorder.php?orderno=" . $array['order_serial'] . "&Desc_1=&Desc_2=&Desc_3=&Desc_4=&ItemName=" . $array['goods_name'] . "&MerchantID=" . $PRs['shopcode'] . "&MerchantTradeDate=" . date("Y/m/d H:i:s",$array['date']) . "&MerchantTradeNo=" . $array['order_serial'] . "&OrderResultURL=" . $INFO['site_url'] . $PRs['returnurl'] . "&PaymentInfoURL=" .$INFO['site_url'] . $PRs['returnurl'] . "&PaymentType=aio&ReturnURL=" . $INFO['site_url'] . $PRs['returnurl2'] . "&TotalAmount=" . $array['total'] . "&TradeDesc=" . $array['order_serial'] . "&HashIV=" . $HashIV;
	
		}else{
			$alipay_str = "<input type=\"hidden\" name=\"CreditInstallment\" value=\"" . intval($PRs['pgmonth']) . "\">";
			$check = (md5(strtolower(urlencode("HashKey=" . $HashKey . "&ChoosePayment=" . $PRs['payno'] . "&ClientBackURL=" . $INFO['site_url'] . "/shopping/showorder.php?orderno=" . substr($array['order_serial'],0,13) . "&CreditInstallment=" . intval($PRs['pgmonth']) . "&ItemName=" . $array['goods_name'] . "&MerchantID=" . $PRs['shopcode'] . "&MerchantTradeDate=" . date("Y/m/d H:i:s",$array['date']) . "&MerchantTradeNo=" . $array['order_serial'] . "&OrderResultURL=" . $INFO['site_url'] . $PRs['returnurl'] . "&PaymentType=aio&ReturnURL=" . $INFO['site_url'] . $PRs['returnurl2'] . "&TotalAmount=" . $array['total'] . "&TradeDesc=" . $array['order_serial'] . "&HashIV=" . $HashIV))));	
		}
		//echo date("Y/m/d H:i:s",$array['date']);
		//exit;
		return "
		<form name=\"form_afbpay\" id=\"form_afbpay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\" >
		<input type=\"hidden\" name=\"ItemName\" value=\"" . $array['goods_name'] . "\">
		<input type=\"hidden\" name=\"ChoosePayment\" value=\"" . $PRs['payno'] . "\">
		<input type=\"hidden\" name=\"ClientBackURL\" value=\"" .$INFO['site_url'] . "/shopping/showorder.php?orderno=" . substr($array['order_serial'],0,13) . "\">
		<input type=\"hidden\" name=\"MerchantID\" value=\"" . $PRs['shopcode'] . "\">
		  <input type=\"hidden\" name=\"MerchantTradeDate\" value=\"" . date("Y/m/d H:i:s",$array['date']) . "\">
		  <input type=\"hidden\" name=\"MerchantTradeNo\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"PaymentType\" value=\"aio\">
		  <input type=\"hidden\" name=\"ReturnURL\" value=\"" .$INFO['site_url'] . $PRs['returnurl2'] . "\">
		  <input type=\"hidden\" name=\"OrderResultURL\" value=\"" .$INFO['site_url'] . $PRs['returnurl'] . "\">
		  <input type=\"hidden\" name=\"TotalAmount\" value=\"" .($array['total']) . "\">
		  <input type=\"hidden\" name=\"TradeDesc\" value=\"" . $array['order_serial'] . "\">
		   <input type=\"hidden\" name=\"CheckMacValue\" value=\"" . $check . "\">
		   " . $alipay_str . "
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉歐付寶系統頁面');
			setTimeout(\"document.getElementById('form_afbpay').submit()\",2000);
		</script>
		  ";
	}
	
	/**
	藍新
	**/
	function LXPay($PRs,$array){
		global $INFO,$DB;
		if ($PRs['payno']=="ATM" || $PRs['payno']=="MMK"){
			$this->LXPay_o($PRs,$array);
			return;
		}
		$lanxin_orderno = $array['order_id'];
		$f1 = explode("|",$PRs['f1']);
		$rcode = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$code = $f2[1];
		if($PRs['pid']==11 || $PRs['pid']==12){
			$checkcode = md5($PRs['shopcode'].$lanxin_orderno.$rcode.sprintf("%01.2f", $array['total']));
			$Amount = sprintf("%01.2f", $array['total']);
			$form = "<form action=\"" . $PRs['payurl'] . "\" method=post name=\"form_lxpay\" id=\"form_lxpay\">";
		}
		if($PRs['pid']=="13"){
			$checkcode = md5($PRs['shopcode'].$rcode.intval($array['total']) . $array['order_serial']);
			$Amount = intval($array['total']);
			$form = "<form action=\"" . $PRs['payurl'] . "\" method=post name=\"form_lxpay\" id=\"form_lxpay\">";
		}
		if($PRs['payno']=="CS"){
			$form = "<form action=\"" . $PRs['payurl'] . "\" method=post name=\"form_lxpay\" id=\"form_lxpay\">";
		}
		$return = $form . "
		
			";    
                                      
			if($PRs['pid']==11 || $PRs['pid']==12){
			$return .= "
			<input type=hidden name=\"MerchantNumber\" value=\"" . $PRs['shopcode'] . "\">
                    
			<input type=hidden name=\"Amount\"         value=\"" . $Amount . "\">
			<input type=hidden name=\"ApproveFlag\"    value=\"1\">
			<input type=hidden name=\"DepositFlag\"    value=\"1\">
			<input type=hidden name=\"Englishmode\"    value=\"0\">   
			<input type=hidden name=\"checksum\"       value=\"" . $checkcode . "\">
			<input type=hidden name=\"OrderNumber\"    value=\"" . $lanxin_orderno . "\">
			<input type=hidden name=\"OrgOrderNumber\" value=\"" . $array['order_serial'] . "\">
			<input type=hidden name=\"op\"             value=\"AcceptPayment\">
			<input type=hidden name=\"OrderURL\"       value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "&type=feedback\">
			<input type=hidden name=\"ReturnURL\"      value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "&type=receive\">";
			}else{
			 $return .= "
			 <input type=hidden name=\"merchantNumber\" value=\"" . $PRs['shopcode'] . "\">
                    
			<input type=hidden name=\"amount\"         value=\"" . $Amount . "\">
			 <input type=hidden name=\"bankid\"    value=\"004\">
			<input type=hidden name=\"ordernumber\"    value=\"" . $array['order_serial'] . "\">
			<input type=\"hidden\" name=\"returnvalue\" value=\"0\">
			<input type=\"hidden\" name=\"paytitle\" value=\"" . $array['order_serial'] . "\">
			<input type=hidden name=\"paymenttype\" value=\"" . $PRs['payno'] . "\">
			<input type=hidden name=\"payname\" value=\"" . $array['receiver_name'] . "\">
			<input type=hidden name=\"payphone\" value=\"" . $array['receiver_mobile'] . "\">
			<input type=\"hidden\" name=\"hash\" value=\"" . $checkcode . "\">
			<input type=\"hidden\" name=\"nexturl\" value=\"" . $INFO['site_url'] . "\">";
			
			}
			if($PRs['pid']==12){
			$return .= "<input type=hidden name=\"Period\"         value=\"" . intval($PRs['pgmonth']) . "\">";
			}
		$return .= "</form>
		<script language=\"javascript\">
			alert('確定購買，請按確定完成訂單，隨即幫您連線到付款頁面');
			document.getElementById('form_lxpay').submit();
		";
		//if($PRs['payno']=="CS"){
		//	$return .= "location.href='showorder.php?orderno=" . $array['order_serial'] . "';";
		//}
		$return .= "
		</script>
		";
		return $return;
	}
	/**
	藍新
	**/
	function LXPay_o($PRs,$array){
		global $INFO,$DB;
		$lanxin_orderno = $array['order_id'];
		$f1 = explode("|",$PRs['f1']);
		$rcode = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$code = $f2[1];
		$checkcode = md5($PRs['shopcode'].$rcode.intval($array['total']) . $array['order_serial']);
		$Amount = intval($array['total']);
		$postdata = "merchantnumber=".$PRs['shopcode'].
                 "&ordernumber=".$array['order_serial'].
                 "&amount=".$Amount.
                 "&paymenttype=".$PRs['payno'].
                 "&bankid="."007".
                 "&paytitle=".urlencode($array['order_serial']).
                 "&payname=".urlencode($array['receiver_name']).
                 "&payphone=".urlencode($array['receiver_mobile']).
                 "&returnvalue=1".
                 "&hash=".$checkcode.
                 "&nexturl=".urlencode($INFO['site_url']);

	   $url = parse_url($PRs['payurl']);
	   //print_r($postdata);
	   
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
//echo $postdata;$result;exit;
//echo $httpcode."||";
		if($httpcode!="200"){
			  echo "<script language=\"javascript\">location.href = \"payorder.php?orderno=" . $array['order_serial'] . "&error=" . $httpmessage . "\";</script>";
			  exit;
		 }
		 
		 $ary1 = split("&",$result);
		 for($i=0;$i<count($ary1);$i++){
			  $ary2 = split("=",$ary1[$i]);
			  $ary3 = split("=",$ary1[$i]);
			  //print_r($ary3);
			  $ary4[$ary3[0]] = $ary3[1];
			  $ary2[0]."=".$ary2[1]."\n";
		 }
		 //print_r($ary4);exit;
		
		 if($ary4['rc']!=0){
			echo "<script language=\"javascript\">location.href = \"payorder.php?orderno=" . $array['order_serial'] . "&error=" . $ary4['message'] . "\";</script>";
			  exit;	 
		 }
		 echo "<script language=\"javascript\">location.href = \"receivePay.php?" . $result . "&pid=13\";</script>";
		 exit;
		 
	}
	
	/**P
	Paypal
	**/
	function Paypal($PRs,$array){
		global $INFO,$DB;
		return "
		<form method=\"post\" name=\"paypal_form\" action=\"" . $PRs['payurl'] . "\">

		<!-- PayPal Configuration --> 
		<input type=\"hidden\" name=\"business\" value=\"" . $PRs['shopcode'] . "\"> 
		<input type=\"hidden\" name=\"cmd\" value=\"_xclick\"> 
		<input type=\"hidden\" name=\"image_url\" value=\"\">
		<input type=\"hidden\" name=\"return\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "?action=success\">
		<input type=\"hidden\" name=\"cancel_return\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "?action=cancel\">
		<input type=\"hidden\" name=\"notify_url\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "?action=ipn\">
		<input type=\"hidden\" name=\"rm\" value=\"2\">
		<input type=\"hidden\" name=\"currency_code\" value=\"TWD\">
		<input type=\"hidden\" name=\"lc\" value=\"zh_TW\">
		<input type=\"hidden\" name=\"LOCALECODE\" value=\"zh_TW\">
		<input type=\"hidden\" name=\"bn\" value=\"toolkit-asp\">
		<input type=\"hidden\" name=\"cbt\" value=\"Continue >>\">
		
		<!-- Payment Page Information --> 
		<input type=\"hidden\" name=\"no_shipping\" value=\"\">
		<input type=\"hidden\" name=\"no_note\" value=\"1\">
		<input type=\"hidden\" name=\"cn\" value=\"Comments\"> 
		<input type=\"hidden\" name=\"cs\" value=\"\">
		
		<!-- Product Information --> 
		<input type=\"hidden\" name=\"item_name\" value=\"" . $array['order_serial'] . "\">
		<input type=\"hidden\" name=\"amount\" value=\"" . $array['total'] . "\">
		<input type=\"hidden\" name=\"quantity\" value=\"\"> 
		<input type=\"hidden\" name=\"item_number\" value=\"" . $array['order_serial'] . "\">
		<input type=\"hidden\" name=\"undefined_quantity\" value=\"\">
		<input type=\"hidden\" name=\"on0\" value=\"\">
		<input type=\"hidden\" name=\"os0\" value=\"\">
		<input type=\"hidden\" name=\"on1\" value=\"\">
		<input type=\"hidden\" name=\"os1\" value=\"\">
		
		<!-- Shipping and Misc Information --> 
		<input type=\"hidden\" name=\"shipping\" value=\"\">
		<input type=\"hidden\" name=\"shipping2\" value=\"\">
		<input type=\"hidden\" name=\"handling\" value=\"\">
		<input type=\"hidden\" name=\"tax\" value=\"\">
		<input type=\"hidden\" name=\"custom\" value=\"\">
		<input type=\"hidden\" name=\"invoice\" value=\"\">
		
		<!-- Customer Information --> 
		<input type=\"hidden\" name=\"first_name\" value=\"" . $array['receiver_name'] . "\"> 
		<input type=\"hidden\" name=\"last_name\" value=\"" . $array['receiver_name'] . "\"> 
		<input type=\"hidden\" name=\"address1\" value=\"" . $array['receiver_address'] . "\"> 
		<input type=\"hidden\" name=\"address2\" value=\"" . $array['receiver_address'] . "\"> 
		<input type=\"hidden\" name=\"city\" value=\"\"> 
		<input type=\"hidden\" name=\"state\" value=\"\"> 
		<input type=\"hidden\" name=\"zip\" value=\"" . $array['receiver_post'] . "\"> 
		<input type=\"hidden\" name=\"email\" value=\"" . $array['receiver_email'] . "\"> 
		<input type=\"hidden\" name=\"night_phone_a\" value=\"" . $array['receiver_tele'] . "\"> 
		<input type=\"hidden\" name=\"night_phone_b\" value=\"" . $array['receiver_mobile'] . "\"> 
		<input type=\"hidden\" name=\"night_phone_c\" value=\"\"> 
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉到Paypal付款頁面');
			setTimeout(\"document.all.paypal_form.submit()\",2000);
		</script>
		";
	}
	
	/**
	支付寶
	**/
	
	function alipay($PRs,$array){
		global $INFO,$DB;
		$f1 = explode("|",$PRs['f1']);
		$security_code = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$seller_email = $f2[1];
		require_once("alipay_service.php");
		require_once("alipay_config.php");
		$parameter = array(
		"service" => "trade_create_by_buyer", //交易类型，必填实物交易＝trade_create_by_buyer（需要填写物流） 虚拟物品交易＝create_digital_goods_trade_p 捐赠＝create_donate_trade_p
		"partner" =>$PRs['shopcode'],                                               //合作商户号
		"return_url" =>$INFO['site_url'] . $PRs['returnurl'],  //同步返回
		"notify_url" =>$INFO['site_url'] . $PRs['returnurl2'],  //异步返回
		"_input_charset" => $_input_charset,                                //字符集，默认为GBK
		"subject" => "订单编号：" . $array['order_serial'],                                                //商品名称，必填
		"body" => "订单编号：" . $array['order_serial'],                                           //商品描述，必填
		"out_trade_no" => $array['order_serial'] ,                      //商品外部交易号，必填,每次测试都须修改
		"price" => round($array['total'],2),                                 //商品单价，必填
		"discount"=>"",                                    //折扣
		"payment_type"=>"1",                               // 商品支付类型 1 ＝商品购买 2＝服务购买 3＝网络拍卖 4＝捐赠 5＝邮费补偿 6＝奖金
		"quantity" => "1",                                 //商品数量，必填
		"show_url" => "",            //商品相关网站
	
		"total_fee"=>round($array['total'],2),                   //捐赠金额
	
		"receive_name"=>$array['receiver_name'],                               //收件人姓名
		"receive_address"=>trim($array['receiver_address']),                           //收件人地址
		"receive_zip"=>$array['receiver_post'],                               //收件人邮编
		"receive_phone"=>$array['receiver_tele'],                             //收件人电话
		"receive_mobile"=>$array['receiver_mobile'],                           //收件人手机
	
		"seller_email" => $seller_email,                //卖家邮箱，必填
		"buyer_email" => "",//买家邮箱，选填
		"buyer_msg" => ""                             //卖家邮箱，选填
		);
		$alipay = new alipay_service($parameter,$security_code,$sign_type);
		$link=$alipay->create_url();
		return "
		<script language=\"javascript\">
			alert('請稍後，轉Alipay付款頁面');
			setTimeout(\"location.href='" . $link . "';\",2000);
		</script>
		";
	}
	/**
	紅陽
	**/
	function HYPay($PRs,$array){
		global $INFO,$DB;
		$f1 = explode("|",$PRs['f1']);
		$key = $f1[1];
		$ChkValue=sha1($PRs['shopcode'].$key.$array['total']);
		$ChkValue=strtoupper($ChkValue); 
		/*
		$form_i = 1;
		$total = 0;
		$Query_detail = $DB->query(" select gid,goodsname,market_price,price,goodscount from `{$INFO[DBPrefix]}order_detail` where order_id=".intval($array['order_id'])." order by order_detail_id desc ");
		while ($Rs_detail = $DB->fetch_array($Query_detail)){
			$form_hy .= "<input type=hidden name=ProductName" . $form_i . " value='" . $Rs_detail['goodsname'] . "' > <!-- 購買的第一項產品 --><input type=hidden name=ProductPrice" . $form_i . " value='" . $Rs_detail[price] . "' > <!-- 購買的第一項產品價格 --><input type=hidden name=ProductQuantity" . $form_i . " value='" . $Rs_detail[goodscount] . "' >  <!-- 購買的第一項產品數量 -->";
			$total += $Rs_detail[price]*$Rs_detail[goodscount];
			$form_i++;
		}
		if($total>$array['total']){
			$form_hy .= "<input type=hidden name=ProductName" . $form_i . " value='優惠' > <!-- 購買的第一項產品 --><input type=hidden name=ProductPrice" . $form_i . " value='-" . ($total-$array['total']) . "' > <!-- 購買的第一項產品價格 --><input type=hidden name=ProductQuantity" . $form_i . " value='1' >  <!-- 購買的第一項產品數量 -->";	
		}
		*/
		
		$DueDate = date("Ymd",time()+5*60*60*24);
		$return .="
		<form method=\"post\" name=\"payform_seven5\" id=\"payform_seven5\" action=\"" . $PRs['payurl'] . "\" >
		<input type=\"hidden\" name=\"web\" value=\"" . $PRs['shopcode'] . "\"> <!--商店代號 maxlength=\"10\" -->
		";
		//if ($array['pid']==18)
			$return .= "<input type=hidden name=DueDate value=" . $DueDate . " > <!-- 繳費期限 -->
			<input type=hidden name=ProductName1 value='" . $array['order_serial'] . "' > <!-- 購買的第一項產品 -->
			<input type=hidden name=ProductPrice1 value='" . $array['total'] . "' > <!-- 購買的第一項產品價格 -->
			<input type=hidden name=ProductQuantity1 value='1' >  <!-- 購買的第一項產品數量 -->
			<input type=hidden name=UserNo value=" . $array['userno'] . " > <!-- 自訂給予下訂單的用戶之用戶編號 -->
			<input type=hidden name=BillDate value=" . $DueDate . " >
			";
		$return .="
		<input type=\"hidden\" name=\"Td\" value=\"" . $array['order_serial'] . "\">
		<input type=\"hidden\" name=\"MN\" value=\"" . $array['total'] . "\"> <!-- 交易金額 maxlength=\"8\" -->
		<input type=\"hidden\" name=\"online\" value=\"1\"> <!-- 交易方式-->
		<input type=\"hidden\" name=\"OrderInfo\" value=\"" . $array['order_serial'] . "\"> <!-- 交易內容 maxlength=\"4000\" --> 
		<input type=\"hidden\" name=\"sna\" value=\"" . $array['receiver_name'] . "\"> <!-- 姓名 maxlength=\"30\"--> 
		<input type=\"hidden\" name=\"uI\" value=\"\"> <!-- 身分證 maxlength=\"10\" -->
		<input type=\"hidden\" name=\"sdt\" value=\"" . $array['receiver_tele'] . "\"> <!-- 電話 maxlength=\"24\" -->
		<input type=\"hidden\" name=\"sd\" value=\"" . $array['receiver_address'] . "\"> <!-- 住址 maxlength=\"100\"--> 
		<input type=\"hidden\" name=\"email\" value=\"" . $array['receiver_email'] . "\"> <!-- email maxlength=\"100\" --> 
		<input type=\"hidden\" name=\"note1\" value=\"\"> <!-- 備註1 maxlength=\"4000\"--> 
		<input type=\"hidden\" name=\"note2\" value=\"\"> <!-- 備註2 maxlength=\"200\" --> 
		<input type=\"hidden\" name=\"Card_Type\" value=\"" . $PRs['payno'] . "\"> <!-- 空白 or 0信用卡交易、 1銀聯卡交易。  -->
		<input type=\"hidden\" name=\"ChkValue\" value=\"" . $ChkValue . "\">
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉紅陽付款頁面');
			setTimeout(\"document.getElementById('payform_seven5').submit()\",2000);
		</script>
		";
		return $return;
	}
	
	/**
	合作金流
	**/
	function HZPay($PRs,$array){
		global $INFO,$DB;
		$f1 = explode("|",$PRs['f1']);
		$MerchantID = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$TerminalID = $f2[1];
		return "
		<form name=\"form_hzpay\" id=\"form_hzpay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\">
		  <input type=\"hidden\" name=\"merID\" value=\"" . $PRs['shopcode'] . "\">
		  <input type=\"hidden\" name=\"MerchantID\" value=\"" . $MerchantID . "\">
		  <input type=\"hidden\" name=\"MerchantName\" value=\"" . $INFO['company_name'] . "\">
		  <input type=\"hidden\" name=\"lidm\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"TerminalID\" value=\"" . $TerminalID . "\">
		  <input type=\"hidden\" name=\"purchAmt\" value=\"" . $array['total'] . "\">
		  <input type=\"hidden\" name=\"AutoCap\" value=\"1\">
		  <input type=\"hidden\" name=\"AuthResURL\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\">
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉到合作金庫線上刷卡付款頁面');
			setTimeout(\"document.all.form_hzpay.submit()\",2000);
		</script>
		";
	}
	
	/**
	逸揚國際
	**/
	function YYPay($PRs,$array){
		global $INFO,$DB;
		$f1 = explode("|",$PRs['f1']);
		$checkno = $f1[1];
		$str_check = md5($array['order_serial'].$PRs['shopcode'].sprintf ("%01.2f", $array['total']).$checkno);
		return "
		<form name=\"form_hzpay\" id=\"form_hzpay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\">
		  <input type=\"hidden\" name=\"e_no\" value=\"" . $PRs['shopcode'] . "\">
		  <input type=\"hidden\" name=\"e_Currency_Type\" value=\"NTD\">
		  <input type=\"hidden\" name=\"e_Gateway_Type\" value=\"" . $PRs['payno'] . "\">
		  <input type=\"hidden\" name=\"e_oderno\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"e_Lang\" value=\"BIG5\">
		  <input type=\"hidden\" name=\"e_money\" value=\"" . sprintf ("%01.2f", $array['total']) . "\">
		  <input type=\"hidden\" name=\"str_check\" value=\"" . $str_check . "\">
		  <input type=\"hidden\" name=\"e_storename\" value=\"" . $INFO['company_name'] . "\">
		  <input type=\"hidden\" name=\"e_url\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\">
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉到逸揚國際線上刷卡付款頁面');
			setTimeout(\"document.all.form_hzpay.submit()\",2000);
		</script>
		";
	}
	
	/**
	Iepay
	**/
	function IEPay($PRs,$array){
		global $INFO,$DB;
		$f1 = explode("|",$PRs['f1']);
		$password = $f1[1];
		return "
		<form name=\"form_iepay\" id=\"form_iepay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\">
		  <input type=\"hidden\" name=\"storeid\" value=\"" . $PRs['shopcode'] . "\">
		  <input type=\"hidden\" name=\"password\" value=\"" . $password . "\">
		  <input type=\"hidden\" name=\"paytype\" value=\"" . $PRs['payno'] . "\">
		  <input type=\"hidden\" name=\"paytypeid\" value=\"" . $PRs['payno'] . "\">
		  <input type=\"hidden\" name=\"orderid\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"charset\" value=\"utf-8\">
		  <input type=\"hidden\" name=\"account\" value=\"" . intval($array['total']) . "\">
		  <input type=\"hidden\" name=\"remark\" value=\"\">
		  <input type=\"hidden\" name=\"customer\" value=\"" . $array['receiver_name'] . "\">
		  <input type=\"hidden\" name=\"cellphone\" value=\"" . $array['receiver_mobile'] . "\">
		  <input type=\"hidden\" name=\"email\" value=\"" . $array['receiver_email'] . "\">
		  <input type=\"hidden\" name=\"param\" value=\"\">
		  <input type=\"hidden\" name=\"invoiceflag\" value=\"0\">
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉到iePay金流付款頁面');
			setTimeout(\"document.all.form_iepay.submit()\",2000);
		</script>
		";
	}
	
	/*
	超商取货
	*/
	function EZPay($PRs,$array,$ifphone=0){
	//	print_r($array);
		global $INFO,$DB;
		$f1 = explode("|",$PRs['f1']);
		$checkno = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$webtemp = $f2[1];
		$PRs['returnurl'] = "/shopping/return_ez.php";
		if($ifphone=="1"){
			$PRs['returnurl'] = "/mobile/return_ez.php";
		}
		$return = "
		<form name=\"form_ezpay\" id=\"form_ezpay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\">
		 
		  <input type=\"hidden\" name=\"su_id\" value=\"" . $PRs['shopcode'] . "\">
		   <input type=\"hidden\" name=\"order_id\" value=\"" . $array['order_serial'] . "\"> 
		    <input type=\"hidden\" name=\"rtn_url\" value=\"" .($INFO['site_url'] . $PRs['returnurl']) . "\">
			<input type=\"hidden\" name=\"rv_name\" value=\"" . ($array['receiver_name']) . "\">
			<input type=\"hidden\" name=\"rv_email\" value=\"" . $array['receiver_email'] . "\"> 
			<input type=\"hidden\" name=\"rv_mobile\" value=\"" . trim($array['receiver_mobile']) . "\">
			<input type=\"hidden\" name=\"web_para\" value=\"" . $webtemp . "\">
			<input type=\"hidden\" name=\"order_status\" value=\"A02\">
			<input type=\"hidden\" name=\"order_type\" value=\"" . $array['order_type'] . "\">
			<input type=\"hidden\" name=\"order_amount\" value=\"" . $array['total'] . "\">
			<input type=\"hidden\" name=\"st_code\" value=\"" . $array['st_code'] . "\">
		   <input type=hidden value='選擇取貨超商'>
		</form>
		
		<script language=\"javascript\">
			alert('請稍後，轉到超商取貨系統頁面');
			//setTimeout(\"document.all.form_ezpay.submit()\",2000);
			document.getElementById('form_ezpay').submit();
			
		</script>
		";
		$Creatfile ="log/".date("Ymd")."-" . time() . "-25";
		$fh = fopen( $Creatfile.'.txt', 'w+' );
		@chmod ($Creatfile.'.txt',0777);
		fputs ($fh, $return, strlen($return) );
		fclose($fh);
		return $return;
	}
	
	/*
	超商取货
		function ECPay($PRs,$array){
		global $INFO,$DB,$FUNCTIONS;
		$f1 = explode("|",$PRs['f1']);
		$cvsname = $f1[1];
		return "
		<form name=\"form_ecpay\" id=\"form_ecpay\" method=\"get\" ACTION=\"" . $PRs['payurl'] . "\" >
		  <input type=\"hidden\" name=\"cvsname\" value=\"" . $INFO['site_url'] . "\">
		  <input type=\"hidden\" name=\"userip\" value=\"" . $FUNCTIONS->getip() . "\">
		  <input type=\"hidden\" name=\"cvsid\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"cvstemp\" value=\"" .$array['key'] . "\">
		  <input type=\"hidden\" name=\"url\" value=\"" .($INFO['site_url'] . $PRs['returnurl']) . "\">
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉到超商取貨系統頁面');
			setTimeout(\"document.all.form_ecpay.submit()\",2000);
		</script>
		  ";
		
	}*/
	function ECPay($PRs,$array){
		global $INFO,$DB,$FUNCTIONS;
		$f1 = explode("|",$PRs['f1']);
		$cvsname = $f1[1];
		return "
		<form name=\"form_ecpay\" id=\"form_ecpay\" method=\"post\" action=\"" . $PRs['payurl'] . "\">
		<input type=\"hidden\" name=\"uid\" value=\"" . $PRs['shopcode'] . "\" /><!-- 請帶入：829 -->
		<input type=\"hidden\" name=\"eshopid\" value=\"" . $cvsname . "\" /><!-- 請帶入：208 -->
		<input type=\"hidden\" name=\"servicetype\" value=\"" . $array['order_type'] . "\" /><!-- 目前支援：1.取貨付款　3.取貨不付款 -->
		<input type=\"hidden\" name=\"url\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\" /><!-- 請修改成貴公司電子地圖的資料回傳接收網頁 -->
		<input type=\"hidden\" name=\"tempvar\" value=\"" . $array['order_serial'] . "\" /><!-- 貴公司可自行運用，例：帶入消費者的訂單編號 -->
		<input type=\"hidden\" name=\"storeid\" value=\"" . $array['st_code'] . "\" /><!-- 若消費者先前已有選擇過的門市代號，可協助消費者預先帶入，方便消費者快速選擇收貨門市 -->
		<input type=\"hidden\" name=\"display\" value=\"page\" /><!-- 目前支援電腦版：page，及手機版：touch -->
		<input type=\"hidden\" name=\"charset\" value=\"utf-8\" /><!-- 目前僅支援 big5 和 utf-8 -->
		</form>

		<script language=\"javascript\">
			alert('請稍後，轉到超商取貨系統頁面');
			setTimeout(\"document.all.form_ecpay.submit()\",2000);
		</script>
		  ";
		
	}
	/**
	國泰世華
	**/
	function GTPay($PRs,$array){
		global $INFO,$DB;
		$f1 = explode("|",$PRs['f1']);
		$MerchantID = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$TerminalID = $f2[1];
		return "
		<form name=\"form_gtpay\" id=\"form_gtpay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\">
		  <input type=\"hidden\" name=\"storeid\" value=\"" . $PRs['shopcode'] . "\">
		  <input type=\"hidden\" name=\"ordernumber\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"amount\" value=\"" . $array['total'] . "\">
		</form>
		<script language=\"javascript\">
			alert(\"請先閱讀 1, 2  後再按 '確定'\\r\\n1. 信用卡付費, 請先按右方確定後等 10  15秒\\r\\n2. 進入刷卡頁面, 填資料才算完成付款\");
			setTimeout(\"document.all.form_gtpay.submit()\",2000);
		</script>
		";
	}
	/*
	超商取货
	*/
	function GTWPay($PRs,$array){
		global $INFO,$DB,$FUNCTIONS;
		$f1 = explode("|",$PRs['f1']);
		$MCCode = $f1[1];
		return "
		<form name=\"form_gtwpay\" id=\"form_gtwpay\" method=\"post\" ACTION=\"guotai_webatm_submit.php\" >
			<input type=\"hidden\" name=\"url\" value=\"" . $PRs['payurl'] . "\">
		  <input type=\"hidden\" name=\"CompanyID\" value=\"" . $MCCode . "\">
		  <input type=\"hidden\" name=\"orderNoGenDate\" value=\"" . date("Y/m/d",time()) . "\">
		  <input type=\"hidden\" name=\"PtrAcno\" value=\"" . $array['atm'] . "\">
		  <input type=\"hidden\" name=\"ItemNo\" value=\"" .($array['order_serial']) . "\">
		  <input type=\"hidden\" name=\"PurQuantity\" value=\"1\">
		  <input type=\"hidden\" name=\"amount\" value=\"" . $array['total'] . "\">
		  <input type=\"hidden\" name=\"MerchantKey\" value=\"" .($array['order_serial']) . "\">
		</form>
		<script language=\"javascript\">
			//alert('請稍後，轉國泰世華系統頁面');
			setTimeout(\"document.getElementById('form_gtwpay').submit()\",2000);
		</script>
		  ";
		
	}
	/**
	歐付寶
	**/
	function AFBPay($PRs,$array){
		//echo "aaaa";exit;
		global $INFO,$DB,$FUNCTIONS;
		$f1 = explode("|",$PRs['f1']);
		$HashKey = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$HashIV = $f2[1];
		
		if($PRs['payno']=="Alipay"){
			$alipay_str = "<input type=\"hidden\" name=\"AlipayItemName\" value=\"" . $array['order_serial'] . "\">
			<input type=\"hidden\" name=\"AlipayItemCounts\" value=\"1\">
			<input type=\"hidden\" name=\"AlipayItemPrice\" value=\"" . $array['total'] . "\">
			<input type=\"hidden\" name=\"Email\" value=\"" . $array['receiver_email'] . "\">
			<input type=\"hidden\" name=\"PhoneNo\" value=\"" . $array['receiver_tele'] ."/". $array['receiver_mobile']."\">
			<input type=\"hidden\" name=\"UserName\" value=\"" . $array['receiver_name'] . "\">
			";
			$check = (md5(strtolower(urlencode("HashKey=" . $HashKey . "&AlipayItemCounts=1&AlipayItemName=" . $array['order_serial'] . "&AlipayItemPrice=" . $array['total'] . "&ChoosePayment=" . $PRs['payno'] . "&ClientBackURL=" . $INFO['site_url'] . "/shopping/showorder.php?orderno=" . substr($array['order_serial'],0,13) . "&Email=" . $array['receiver_email'] . "&ItemName=" . $array['goods_name'] . "&MerchantID=" . $PRs['shopcode'] . "&MerchantTradeDate=" . date("Y/m/d H:i:s",$array['date']) . "&MerchantTradeNo=" . $array['order_serial'] . "&OrderResultURL=" . $INFO['site_url'] . $PRs['returnurl'] . "&PaymentType=aio&PhoneNo=" . $array['receiver_tele'] ."/". $array['receiver_mobile']."&ReturnURL=" . $INFO['site_url'] . $PRs['returnurl2'] . "&TotalAmount=" . $array['total'] . "&TradeDesc=" . $array['order_serial'] . "&UserName=" . $array['receiver_name'] . "&HashIV=" . $HashIV))));	
	
		}elseif($PRs['payno']=="ATM"){
			$alipay_str = "<input type=\"hidden\" name=\"ExpireDate\" value=\"3\">";
			$alipay_str .= "<input type=\"hidden\" name=\"PaymentInfoURL\" value=\"" .$INFO['site_url'] . $PRs['returnurl'] . "\">";
			$check = (md5(strtolower(urlencode("HashKey=" . $HashKey . "&ChoosePayment=" . $PRs['payno'] . "&ClientBackURL=" . $INFO['site_url'] . "/shopping/showorder.php?orderno=" . substr($array['order_serial'],0,13) . "&ExpireDate=3&ItemName=" . $array['goods_name'] . "&MerchantID=" . $PRs['shopcode'] . "&MerchantTradeDate=" . date("Y/m/d H:i:s",$array['date']) . "&MerchantTradeNo=" . $array['order_serial'] . "&OrderResultURL=" . $INFO['site_url'] . $PRs['returnurl'] . "&PaymentInfoURL=" .$INFO['site_url'] . $PRs['returnurl'] . "&PaymentType=aio&ReturnURL=" . $INFO['site_url'] . $PRs['returnurl2'] . "&TotalAmount=" . $array['total'] . "&TradeDesc=" . $array['order_serial'] . "&HashIV=" . $HashIV))));	
	
		}elseif($PRs['payno']=="Tenpay"){
			$alipay_str = "<input type=\"hidden\" name=\"ExpireTime\" value=\"" . date("Y/m/d H:i:s",$array['date']+3*24*60*60) . "\">";
			$check = (md5(strtolower(urlencode("HashKey=" . $HashKey . "&ChoosePayment=" . $PRs['payno'] . "&ClientBackURL=" . $INFO['site_url'] . "/shopping/showorder.php?orderno=" . substr($array['order_serial'],0,13) . "&ExpireTime=" . date("Y/m/d H:i:s",$array['date']+3*24*60*60) . "&ItemName=" . $array['goods_name'] . "&MerchantID=" . $PRs['shopcode'] . "&MerchantTradeDate=" . date("Y/m/d H:i:s",$array['date']) . "&MerchantTradeNo=" . $array['order_serial'] . "&OrderResultURL=" . $INFO['site_url'] . $PRs['returnurl'] . "&PaymentType=aio&ReturnURL=" . $INFO['site_url'] . $PRs['returnurl2'] . "&TotalAmount=" . $array['total'] . "&TradeDesc=" . $array['order_serial'] . "&HashIV=" . $HashIV))));	
	
		}elseif($PRs['payno']=="CVS"||$PRs['payno']=="BARCODE"){
			$alipay_str = "<input type=\"hidden\" name=\"Desc_1\" value=\"\">";
			$alipay_str .= "<input type=\"hidden\" name=\"Desc_2\" value=\"\">";
			$alipay_str .= "<input type=\"hidden\" name=\"Desc_3\" value=\"\">";
			$alipay_str .= "<input type=\"hidden\" name=\"Desc_4\" value=\"\">";
			$alipay_str .= "<input type=\"hidden\" name=\"PaymentInfoURL\" value=\"" .$INFO['site_url'] . $PRs['returnurl'] . "\">";
			$check = (md5(strtolower(urlencode("HashKey=" . $HashKey . "&ChoosePayment=" . $PRs['payno'] . "&ClientBackURL=" . $INFO['site_url'] . "/shopping/showorder.php?orderno=" . substr($array['order_serial'],0,13) . "&Desc_1=&Desc_2=&Desc_3=&Desc_4=&ItemName=" . $array['goods_name'] . "&MerchantID=" . $PRs['shopcode'] . "&MerchantTradeDate=" . date("Y/m/d H:i:s",$array['date']) . "&MerchantTradeNo=" . $array['order_serial'] . "&OrderResultURL=" . $INFO['site_url'] . $PRs['returnurl'] . "&PaymentInfoURL=" .$INFO['site_url'] . $PRs['returnurl'] . "&PaymentType=aio&ReturnURL=" . $INFO['site_url'] . $PRs['returnurl2'] . "&TotalAmount=" . $array['total'] . "&TradeDesc=" . $array['order_serial'] . "&HashIV=" . $HashIV))));
			//echo "HashKey=" . $HashKey . "&ChoosePayment=" . $PRs['payno'] . "&ClientBackURL=" . $INFO['site_url'] . "/shopping/showorder.php?orderno=" . $array['order_serial'] . "&Desc_1=&Desc_2=&Desc_3=&Desc_4=&ItemName=" . $array['goods_name'] . "&MerchantID=" . $PRs['shopcode'] . "&MerchantTradeDate=" . date("Y/m/d H:i:s",$array['date']) . "&MerchantTradeNo=" . $array['order_serial'] . "&OrderResultURL=" . $INFO['site_url'] . $PRs['returnurl'] . "&PaymentInfoURL=" .$INFO['site_url'] . $PRs['returnurl'] . "&PaymentType=aio&ReturnURL=" . $INFO['site_url'] . $PRs['returnurl2'] . "&TotalAmount=" . $array['total'] . "&TradeDesc=" . $array['order_serial'] . "&HashIV=" . $HashIV;
	
		}else{
			$alipay_str = "<input type=\"hidden\" name=\"CreditInstallment\" value=\"" . intval($PRs['pgmonth']) . "\">";
			$check = (md5(strtolower(urlencode("HashKey=" . $HashKey . "&ChoosePayment=" . $PRs['payno'] . "&ClientBackURL=" . $INFO['site_url'] . "/shopping/showorder.php?orderno=" . substr($array['order_serial'],0,13) . "&CreditInstallment=" . intval($PRs['pgmonth']) . "&ItemName=" . $array['goods_name'] . "&MerchantID=" . $PRs['shopcode'] . "&MerchantTradeDate=" . date("Y/m/d H:i:s",$array['date']) . "&MerchantTradeNo=" . $array['order_serial'] . "&OrderResultURL=" . $INFO['site_url'] . $PRs['returnurl'] . "&PaymentType=aio&ReturnURL=" . $INFO['site_url'] . $PRs['returnurl2'] . "&TotalAmount=" . $array['total'] . "&TradeDesc=" . $array['order_serial'] . "&HashIV=" . $HashIV))));	
		}
		//echo date("Y/m/d H:i:s",$array['date']);
		//exit;
		return "
		<form name=\"form_afbpay\" id=\"form_afbpay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\" >
		<input type=\"hidden\" name=\"ItemName\" value=\"" . $array['goods_name'] . "\">
		<input type=\"hidden\" name=\"ChoosePayment\" value=\"" . $PRs['payno'] . "\">
		<input type=\"hidden\" name=\"ClientBackURL\" value=\"" .$INFO['site_url'] . "/shopping/showorder.php?orderno=" . substr($array['order_serial'],0,13) . "\">
		<input type=\"hidden\" name=\"MerchantID\" value=\"" . $PRs['shopcode'] . "\">
		  <input type=\"hidden\" name=\"MerchantTradeDate\" value=\"" . date("Y/m/d H:i:s",$array['date']) . "\">
		  <input type=\"hidden\" name=\"MerchantTradeNo\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"PaymentType\" value=\"aio\">
		  <input type=\"hidden\" name=\"ReturnURL\" value=\"" .$INFO['site_url'] . $PRs['returnurl2'] . "\">
		  <input type=\"hidden\" name=\"OrderResultURL\" value=\"" .$INFO['site_url'] . $PRs['returnurl'] . "\">
		  <input type=\"hidden\" name=\"TotalAmount\" value=\"" .($array['total']) . "\">
		  <input type=\"hidden\" name=\"TradeDesc\" value=\"" . $array['order_serial'] . "\">
		   <input type=\"hidden\" name=\"CheckMacValue\" value=\"" . $check . "\">
		   " . $alipay_str . "
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉歐付寶系統頁面');
			setTimeout(\"document.getElementById('form_afbpay').submit()\",2000);
		</script>
		  ";
		
	}
	/*
	玉山
	*/
	function YSPay($PRs,$array){
		global $INFO,$DB,$FUNCTIONS;
		$f1 = explode("|",$PRs['f1']);
		$cvsname = $f1[1];
		$ttime = date("YmdHis",$array['Time']);
		$pcode = md5($PRs['shopcode'] . $array['order_serial'] . $array['total'] . $INFO['site_url'] . $PRs['returnurl'] . $ttime);
		return "
		<form name=\"form_yspay\" id=\"form_yspay\" method=\"get\" ACTION=\"" . $PRs['payurl'] . "\" >
		<input type=\"hidden\" name=\"action\" value=\"order\">
		  <input type=\"hidden\" name=\"seller_id\" value=\"" . $PRs['shopcode'] . "\">
		  <input type=\"hidden\" name=\"channel_id\" value=\"" . $PRs['payno'] . "\">
		  <input type=\"hidden\" name=\"bank_type\" value=\"\">
		  <input type=\"hidden\" name=\"pno\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"ntd\" value=\"" . $array['total'] . "\">
		  <input type=\"hidden\" name=\"return_url\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\">
		  <input type=\"hidden\" name=\"pcode\" value=\"" . $pcode . "\">
		  <input type=\"hidden\" name=\"ttime\" value=\"" . $ttime . "\">
		  <input type=\"hidden\" name=\"count\" value=\"1\">
		  <input type=\"hidden\" name=\"pid0\" value=\"100001888888\">
		  <input type=\"hidden\" name=\"qty0\" value=\"1\">
		  <input type=\"hidden\" name=\"pid1\" value=\"\">
		  <input type=\"hidden\" name=\"qty1\" value=\"0\">
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉玉山支付系統頁面');
			setTimeout(\"document.getElementById('form_yspay').submit()\",2000);
		</script>
		  ";
		
	}
	function YSAtmPay($PRs,$array){
		global $INFO,$DB,$FUNCTIONS;
		$f1 = explode("|",$PRs['f1']);
		$IcpNo = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$hashkey = $f2[1];
		$TransIdentifyNo  = strtoupper(SHA1( $IcpNo . $array['atm'] . $INFO['site_url'] . $PRs['returnurl'] . $array['order_serial'] . $array['total'] . $hashkey));
		return "
		<form name=\"form_yspay\" id=\"form_yspay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\" >
		  <input type=\"hidden\" name=\"IcpNo\" value=\"" . $IcpNo . "\">
		  <input type=\"hidden\" name=\"VAccNo\" value=\"" . $array['atm'] . "\">
		  <input type=\"hidden\" name=\"TransNo\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"TransAmt\" value=\"" . $array['total'] . "\">
		   <input type=\"hidden\" name=\"StoreName\" value=\"WebATM\">
		  <input type=\"hidden\" name=\"IcpConfirmTransURL\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\">
		  <input type=\"hidden\" name=\"TransDesc\" value=\"" . $array['order_serial'] . "\">
		   <input type=\"hidden\" name=\"TransIdentifyNo\" value=\"" . $TransIdentifyNo . "\">
		   <input type=\"hidden\" name=\"Echo\" value=\"WebATM\">
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉玉山支付系統頁面');
			setTimeout(\"document.getElementById('form_yspay').submit()\",2000);
		</script>
		  ";
		
	}
	/**
	華南
	**/
	function HNAtmPay($PRs,$array){
		global $INFO,$DB;
		$f1 = explode("|",$PRs['f1']);
		$TerminalID = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$merID = $f2[1];
		$str = iconv("utf-8","big5",'StoreID='.$PRs['shopcode'].'&StoreAcnt='.$array['atm'].'&StoreTxDate='.date("Y-m-d",time()).'&OrderNumber='.$array['order_serial'].'&Amount='.$array['total'].'&StoreName=bookrep&StoreURL='.$INFO['site_url'].'&SuccessURL='.$INFO['site_url'] . $PRs['returnurl'].'&FailURL='.$INFO['site_url'] . $PRs['returnurl2']);
		$str1 =strtoupper(sha1($str)); 

		$str2 =base64_encode($str);
		return "
		<form name=\"form_hnpay\" id=\"form_hnpay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\">
		  <input type=\"hidden\" name=\"trx\" value=\"com.eatm.wibc.trx.web.EAtmWShopping\">
		  <input type=\"hidden\" name=\"state\" value=\"prompt\">
		  <input type=\"hidden\" name=\"val\" value=\"" . $str2 . "\">
		  <input type=\"hidden\" name=\"mac\" value=\"" . $str1 . "\">
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉到華南ATM付款頁面');
			//setTimeout(\"document.all.form_hnpay.submit()\",2000);
			setTimeout(\"document.getElementById('form_hnpay').submit()\",2000);
		</script>
		";
	}
	function YSYlPay($PRs,$array,$ifphone=0){
		global $INFO,$DB,$FUNCTIONS;
		$f1 = explode("|",$PRs['f1']);
		$key = $f1[1];
		if($ifphone==1){
			$PRs['returnurl'] = "mobile/receivePay_YsYl.php";
		}
		if($PRs['pgmonth']==0){
			$TID = "EC000001";
			$pcode = md5($PRs['shopcode'] ."&&" . $TID . "&". $array['order_serial'] ."&". $array['total'] ."&" . $INFO['site_url'] . "" . $PRs['returnurl'] ."&". $key);
		}else{
			$TID = "EC000002";
			$monthstr = "<input type=\"hidden\" name=\"IC\" value=\"" . $PRs['payno'] . "\">";
			$pcode = md5($PRs['shopcode'] ."&&" . $TID . "&". $array['order_serial'] ."&". $array['total'] ."&" . $INFO['site_url'] . "" . $PRs['returnurl'] ."&" . $PRs['payno'] . "&". $key);
		}
		
		//$pcode = md5($PRs['shopcode'] ."&&" . $TID . "&". $array['order_serial'] ."&". $array['total'] ."&" . $INFO['site_url'] . "" . $PRs['returnurl'] ."&". $key);
		
		return "
		<form name=\"form_yspay\" id=\"form_yspay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\" >
		  <input type=\"hidden\" name=\"MID\" value=\"" . $PRs['shopcode'] . "\">
		  <input type=\"hidden\" name=\"CID\" value=\"\">
		 <input type=\"hidden\" name=\"TID\" value=\"" . $TID . "\">
		  <input type=\"hidden\" name=\"ONO\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"TA\" value=\"" . $array['total'] . "\">
		  <input type=\"hidden\" name=\"U\" value=\"" . $INFO['site_url'] . "" . $PRs['returnurl'] . "\">
		  <input type=\"hidden\" name=\"M\" value=\"" . $pcode . "\">
		  " . $monthstr . "
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉玉山支付系統頁面');
			setTimeout(\"document.getElementById('form_yspay').submit()\",2000);
		</script>
		  ";
	}
	function YSYlsPay($PRs,$array,$ifphone=0){
		global $INFO,$DB,$FUNCTIONS;
		$f1 = explode("|",$PRs['f1']);
		$key = $f1[1];
		if($ifphone==1){
			$PRs['returnurl'] = "mobile/receivePay_YsYls.php";
		}
		$pcode = md5($PRs['shopcode'] ."&&". $array['order_serial'] ."&". $array['total'] ."&01&" . $INFO['site_url'] . "" . $PRs['returnurl'] ."&&". $key);
		
		return "
		<form name=\"form_yspay\" id=\"form_yspay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\" >
		  <input type=\"hidden\" name=\"MID\" value=\"" . $PRs['shopcode'] . "\">
		  <input type=\"hidden\" name=\"CID\" value=\"\">
		 
		  <input type=\"hidden\" name=\"ONO\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"TA\" value=\"" . $array['total'] . "\">
		  <input type=\"hidden\" name=\"TT\" value=\"01\">
		  <input type=\"hidden\" name=\"U\" value=\"" . $INFO['site_url'] . "" . $PRs['returnurl'] . "\">
		  <input type=\"hidden\" name=\"TXNNO\" value=\"\">
		  <input type=\"hidden\" name=\"M\" value=\"" . $pcode . "\">
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉玉山支付系統頁面');
			setTimeout(\"document.getElementById('form_yspay').submit()\",2000);
		</script>
		  ";
	}
	/**
	智付寶
	**/
	function ZfbPay($PRs,$array,$ifphone){
		global $INFO,$DB,$FUNCTIONS;
		$backurl = "/shopping/payorder.php?orderno=" . $array['order_serial'];
		if($ifphone==1){
			$PRs['returnurl'] = "/mobile/receivePay.php?pid=54";
			$backurl = "/mobile/payorder.php?orderno=" . $array['order_serial'];
		}
		if($PRs['payno']=="CREDIT")
			$CREDIT = 1;
		else
			$CREDIT = 0;
		if($PRs['payno']=="UNIONPAY")
			$UNIONPAY = 1;
		else
			$UNIONPAY = 0;	
		if($PRs['payno']=="WEBATM")
			$WEBATM = 1;
		else
			$WEBATM = 0;	
		if($PRs['payno']=="VACC")
			$VACC = 1;
		else
			$VACC = 0;	
		if($PRs['payno']=="CVS")
			$CVS = 1;
		else
			$CVS = 0;
		if($PRs['payno']=="BARCODE")
			$BARCODE = 1;
		else
			$BARCODE = 0;
		if($PRs['payno']=="TENPAY")
			$TENPAY = 1;
		else
			$TENPAY = 0;
		if($PRs['payno']=="ALIPAY")
			$ALIPAY = 1;
		else
			$ALIPAY = 0;
		if($PRs['payno']=="CUSTOM")
			$CUSTOM = 1;
		else
			$CUSTOM = 0;
		$TimeStamp = time();
		$f1 = explode("|",$PRs['f1']);
		$HashKey = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$HashIV = $f2[1];
		$CheckValue_str = "HashKey=" . $HashKey . "&Amt=" . $array['total'] ."&MerchantID=" . $PRs['shopcode'] . "&MerchantOrderNo=" . $array['order_serial'] . "&TimeStamp=" . $TimeStamp . "&Version=1.2&HashIV=" . $HashIV;
		$CheckValue = strtoupper(hash("sha256", $CheckValue_str));
		return "
		<form name=\"form_zfbnpay\" id=\"form_zfbnpay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\">
		  <input type=\"hidden\" name=\"MerchantID\" value=\"" . $PRs['shopcode'] . "\">
		  <input type=\"hidden\" name=\"RespondType\" value=\"JSON\">
		  <input type=\"hidden\" name=\"CheckValue\" value=\"" . $CheckValue  . "\">
		  <input type=\"hidden\" name=\"TimeStamp\" value=\"" . $TimeStamp . "\">
		  <input type=\"hidden\" name=\"Version\" value=\"1.2\">
		  <input type=\"hidden\" name=\"LangType\" value=\"zh-tw\">
		  <input type=\"hidden\" name=\"MerchantOrderNo\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"Amt\" value=\"" . $array['total'] . "\">
		  <input type=\"hidden\" name=\"ItemDesc\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"TradeLimit\" value=\"300\">
		  <input type=\"hidden\" name=\"ExpireDate\" value=\"" . date('Ymd',time()+60*60*24*3)  . "\">
		  <input type=\"hidden\" name=\"ReturnURL\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\">
		  <input type=\"hidden\" name=\"NotifyURL\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\">
		  <input type=\"hidden\" name=\"CustomerURL\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\">
		  <input type=\"hidden\" name=\"ClientBackURL\" value=\"\">
		  <input type=\"hidden\" name=\"Email\" value=\"" . $array['receiver_email'] . "\">
		  <input type=\"hidden\" name=\"EmailModify\" value=\"1\">
		  <input type=\"hidden\" name=\"LoginType\" value=\"0\">
		  <input type=\"hidden\" name=\"OrderComment\" value=\"" . $INFO['site_name'] . "\">
		  <input type=\"hidden\" name=\"CREDIT\" value=\"" . $CREDIT . "\">
		  <input type=\"hidden\" name=\"CreditRed\" value=\"0\">
		  <input type=\"hidden\" name=\"InstFlag\" value=\"" . $PRs['pgmonth'] . "\">
		  <input type=\"hidden\" name=\"UNIONPAY\" value=\"" . $UNIONPAY . "\">
		  <input type=\"hidden\" name=\"WEBATM\" value=\"" . $WEBATM . "\">
		  <input type=\"hidden\" name=\"VACC\" value=\"" . $VACC . "\">
		  <input type=\"hidden\" name=\"CVS\" value=\"" . $CVS . "\">
		  <input type=\"hidden\" name=\"BARCODE\" value=\"" . $BARCODE . "\">
		  <input type=\"hidden\" name=\"ALIPAY\" value=\"" . $ALIPAY . "\">
		  <input type=\"hidden\" name=\"TENPAY\" value=\"" . $TENPAY . "\">
		  <input type=\"hidden\" name=\"CUSTOM\" value=\"" . $CUSTOM . "\">
		  <input type=\"hidden\" name=\"Count\" value=\"1\">
		  <input type=\"hidden\" name=\"Pid1\" value=\"001\">
		  <input type=\"hidden\" name=\"Title1\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"Desc1\" value=\"" . $array['order_serial'] . "\">
		    <input type=\"hidden\" name=\"Price1\" value=\"" . $array['total'] . "\">
			<input type=\"hidden\" name=\"Qty1\" value=\"1\">
		<input type=\"hidden\" name=\"Receiver\" value=\"" . $array['receiver_name'] . "\">	
		<input type=\"hidden\" name=\"Tel1\" value=\"" . $array['receiver_tele'] . "\">	
		<input type=\"hidden\" name=\"Tel2\" value=\"" . $array['receiver_mobile'] . "\">	
		  
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉到智付寶付款頁面');
			//setTimeout(\"document.all.form_hnpay.submit()\",2000);
			setTimeout(\"document.getElementById('form_zfbnpay').submit()\",2000);
		</script>
		";
		
	}
	/**
	彰化
	**/
	function ZHPay($PRs,$array){
		global $INFO,$DB;
		$f1 = explode("|",$PRs['f1']);
		$TerminalID = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$merID = $f2[1];
		$f3 = explode("|",$PRs['f3']);
		$key = $f3[1];
		$LocalDate = date("Ymd");
		$LocalTime = date("his");
		$reqToken=sha1($array['order_serial']."&".$array['total']."&".$key."&".$PRs['shopcode']."&".$TerminalID."&".$LocalDate.$LocalTime);
		$reqToken=strtoupper($reqToken); 
		if ($PRs['pgmonth']>0){
			$return = "<input type=\"hidden\" name=\"PeriodNum\" value=\"" . $PRs['pgmonth'] . "\">";
			$return = "<input type=\"hidden\" name=\"PayType\" value=\"1\">";
		}else{
			$return = "<input type=\"hidden\" name=\"PayType\" value=\"0\">";	
		}
		$returnstring = "
		<form name=\"form_hnpay\" id=\"form_zhpay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\">
		  <input type=\"hidden\" name=\"merID\" value=\"" . $merID . "\">
		  <input type=\"hidden\" name=\"MerchantID\" value=\"" . $PRs['shopcode'] . "\">
		  <input type=\"hidden\" name=\"TerminalID\" value=\"" . $TerminalID . "\">
		  <input type=\"hidden\" name=\"MerchantName\" value=\"" . $INFO['company_name'] . "\">
		  <input type=\"hidden\" name=\"lidm\" value=\"" . $array['order_serial'] . "\">
		 
		  <input type=\"hidden\" name=\"purchAmt\" value=\"" . $array['total'] . "\">
		  " . $return . "
		  <input type=\"hidden\" name=\"AutoCap\" value=\"1\">
		  <input type=\"hidden\" name=\"AuthResURL\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\">
		  <input type=\"hidden\" name=\"LocalDate\" value=\"" . $LocalDate . "\">
		   <input type=\"hidden\" name=\"LocalTime\" value=\"" . $LocalTime . "\">
		 
		  
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉到彰化線上刷卡付款頁面');
			setTimeout(\"document.getElementById('form_zhpay').submit()\",2000);
		</script>
		";	
		$Creatfile ="log/".date("Ymd")."-" . time() . "zh";
		$fh = fopen( $Creatfile.'.txt', 'w+' );
		@chmod ($Creatfile.'.txt',0777);
		fputs ($fh, $returnstring, strlen($returnstring) );
		fclose($fh);
		return $returnstring;
		// <input type=\"hidden\" name=\"reqToken\" value=\"" . $reqToken . "\">
	}
	/**
	第一银行
	**/
	function DyPay($PRs,$array){
		global $INFO,$DB;
		$f1 = explode("|",$PRs['f1']);
		$MerchantID = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$TerminalID = $f2[1];
		$return = "
		<form name=\"form_hzpay\" id=\"form_hzpay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\">
		  <input type=\"hidden\" name=\"merID\" value=\"" . $PRs['shopcode'] . "\">
		  <input type=\"hidden\" name=\"MerchantID\" value=\"" . $MerchantID . "\">
		  <input type=\"hidden\" name=\"MerchantName\" value=\"" . $INFO['company_name'] . "\">
		  <input type=\"hidden\" name=\"lidm\" value=\"" . $array['order_serial'] . "\">
		  <input type=\"hidden\" name=\"TerminalID\" value=\"" . $TerminalID . "\">
		  <input type=\"hidden\" name=\"purchAmt\" value=\"" . $array['total'] . "\">
		  <input type=\"hidden\" name=\"AutoCap\" value=\"1\">
		  <input type=\"hidden\" name=\"enCodeType\" value=\"UTF-8\">
		  <input type=\"hidden\" name=\"AuthResURL\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\">
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉到第一銀行線上刷卡付款頁面');
			setTimeout(\"document.getElementById('form_hzpay').submit()\",2000);
		</script>
		";
		$Creatfile ="log/".date("Ymd")."-" . time();
		$fh = fopen( $Creatfile.'.txt', 'w+' );
		@chmod ($Creatfile.'.txt',0777);
		fputs ($fh, $return, strlen($return) );
		fclose($fh);
		return $return;
	}
	/**
	* convert simplexml object to array sets
	* $array_tags 表示需要转为数组的 xml 标签。例：array('item', '')
	* 出错返回False
	*
	* @param object $simplexml_obj
	* @param array $array_tags
	* @param int $strip_white 是否清除左右空格
	* @return mixed
	*/
	function simplexml_to_array($simplexml_obj, $array_tags=array(), $strip_white=1)
	{    
		if( $simplexml_obj )
		{
			if( count($simplexml_obj)==0 )
				return $strip_white?trim((string)$simplexml_obj):(string)$simplexml_obj;
	
			$attr = array();
			foreach ($simplexml_obj as $k=>$val) {
				if( !empty($array_tags) && in_array($k, $array_tags) ) {
					$attr[] = simplexml_to_array($val, $array_tags, $strip_white);
				}else{
					$attr[$k] = simplexml_to_array($val, $array_tags, $strip_white);
				}
			}
			return $attr;
		}
		
		return FALSE;
	}
	function addPkcs7Padding($string, $blocksize = 16) {
		$len = strlen($string); //取得字符串长度
		$pad = $blocksize - ($len % $blocksize); //取得补码的长度
		$string .= str_repeat(chr($pad), $pad); //用ASCII码为补码长度的字符， 补足最后一段
		return $string;
	}
	function aes128cbcEncrypt($str, $iv, $key ) {  
   	 return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $this->addPkcs7Padding($str) , MCRYPT_MODE_CBC, $iv));
	}
	function stripPkcs7Padding($string){
		$slast = ord(substr($string, -1));
		$slastc = chr($slast);
		$pcheck = substr($string, -$slast);
		if(preg_match("/$slastc{".$slast."}/", $string)){
			$string = substr($string, 0, strlen($string)-$slast);
			return $string;
		} else {
			return false;
		}
	}
	function aes128cbcDecrypt($encryptedText, $iv, $key) {
		$encryptedText =base64_decode($encryptedText);
		return $this->stripPkcs7Padding(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encryptedText, MCRYPT_MODE_CBC, $iv));
	}
	
	/**
	接收參數
	**/
	function returnPay($array){
		//print_r($array);
		global $INFO,$DB;
		if ($_GET['pid']==1)
			$return =  $this->returnTW($array);
		if ($_GET['pid']==3)
			$return =  $this->returnLH($array);
		if ($_GET['pid']==4 || $array['pid']==5 || $array['pid']==6)
			$return =  $this->returnZX($array);
		if ($_GET['pid']==7)
			$return =  $this->returnZY($array);
		if ($_GET['pid']==8)
			$return =  $this->returnHN($array);
		if ($_GET['pid']==9)
			$return =  $this->returnTX($array);
		if ($_GET['pid']==10)
			$return =  $this->returnLJ($array);
		if (($_GET['pid']==11 || $_GET['pid']==12) && $_GET['type']=="receive")
			$return = $this->returnLX($array);
		if (($_GET['pid']==11 || $_GET['pid']==12) && $_GET['type']=="feedback")
			$return = $this->returnLX1($array);
		if ($_GET['pid']==13 && $array['type']=="")
			$return =  $this->returnLX2($array);
		if ($array['pid']==13 && $array['type']=="feedback")
			$return =  $this->returnLX2_f($array);	
		if ($_GET['pid']==14)
			$return =  $this->returnPaypal($array);
		if ($_GET['pid']==15)
			$return =  $this->returnAlipay($array);
		if ($_GET['pid']==19)
			$return =  $this->returnMap($array);
		if ($_GET['pid']==21||$_GET['pid']==22)
			$return =  $this->returnYY($array);
		if ($_GET['pid']==24)
			$return =  $this->returnIE($array);
		if (intval($array['webPara'])==25){
			$return =  $this->returnEZ($array);
		}
		if (intval($array['pid'])==26){
			$return =  $this->returnEC($array);
		}
		if ($array['pid']==29)
			$return =  $this->returnGT($array);
		if (intval($array['pid'])==30){
			$return =  $this->returnGTW($array);
		}
		if (intval($array['pid'])==33){
			$return =  $this->returnAFB($array);
		}
		if ($_GET['pid']==39)
			$return =  $this->returnYS($array);
		if ($_GET['pid']==38)
			$return =  $this->returnYSAtm($array);	
		if ($array['pid']==27)
			$return =  $this->returnHNAtm($array);
		if (trim($array['hy'])=="hy"){
			$return =  $this->returnHY($array);
		}
		if ($_GET['pid']==42)
			$return =  $this->returnYSYl($array);	
		if ($_GET['pid']==41)
			$return =  $this->returnYSYls($array);	
		if ($array['pid']==54)
			$return =  $this->returnZfb($array);
		if ($array['pid']==60)
			$return =  $this->returnZH($array);
		if ($array['pid']==62)
			$return =  $this->returnDy($array);
		return $return;
	}
	
	/**
	台灣里
	**/
	function returnTW($array){
		$return_array = array();
		//訂單編號
		$return_array['order_serial'] = $array['txid'];
		//付款狀態0：未支付；1：成功支付；2：失败；3：等待付款
		$return_array['paystate'] = $array['status'];
		//付款種類
		$return_array['pay_type'] = $array['pay_type'];
		$return_array['error_code'] = $array['error_code'];
		$return_array['error_desc'] = $array['error_desc'];
		//虛擬付款帳號
		$return_array['account_no'] = $array['account_no'];
		//信用卡授權碼
		$return_array['auth_code'] = $array['auth_code'];
		return $return_array;
	}
	
	/**
	聯合
	**/
	function returnLH($array){
		$return_array = array();
		$return_array['order_serial'] = $array['OrderID'];
		//信用卡授權碼
		$return_array['auth_code'] = $array['ApproveCode'];
		//卡號
		$return_array['account_no'] = $array['PAN'];
		$return_array['pay_type'] = $array['TransMode'];
		if ($array['ResponseCode']=="00" || $array['ResponseCode']=="08" || $array['ResponseCode']=="11"){
			$return_array['paystate'] = 1;	
		}else{
			$return_array['paystate'] = 2;	
			$return_array['error_code'] = $array['ResponseCode'];
			$return_array['error_desc'] = iconv("BIG5","UTF-8",$array['ResponseMsg']);
		}
		return $return_array;
	}
	
	/**
	中國信託
	**/
	function returnZX($array){
		global $INFO,$DB;
		include 'auth_mpi_mac.php';
		$Psql = "select pg.* from `{$INFO[DBPrefix]}paymanager` as pg where pg.pid='" . $array['pid'] . "'";
		$PQuery    = $DB->query($Psql);
		$PRs=$DB->fetch_array($PQuery);
		$f1 = explode("|",$PRs['f1']);
		$MerchantID = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$TerminalID = $f2[1];
		$f3 = explode("|",$PRs['f3']);
		$Key = $f3[1];
		$array = gendecrypt($array['URLResEnc'],$Key,1);
		//print_r($array);
		
		
		$return_array = array();
		$return_array['order_serial'] = $array['lidm'];
		$return_array['paystate'] = $array['status'];
		if ($array['status']==0)
			$return_array['paystate'] = 1;
		else
			$return_array['paystate'] = 2;
		$return_array['auth_code'] = $array['authCode'];
		$return_array['error_desc'] = $array['errDesc'];
		$return_array['error_code'] = $array['errcode'];
		$return_array['account_no'] = $array['Last4digitPAN'];
		return $return_array;
	}
	
	/**
	章銀
	**/
	function returnZY($array){
		global $INFO,$DB;
		$Psql = "select * from `{$INFO[DBPrefix]}paymanager` as pg where pg.pid='7'";
		$PQuery    = $DB->query($Psql);
		$PRs=$DB->fetch_array($PQuery);
		$f1 = explode("|",$PRs['f1']);
		$cKey = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$wKey = $f2[1];
		
		$posterip	=	$_SERVER['REMOTE_ADDR'];		
		$FromTrustServer = false;		
		$TrustHost_Prod = gethostbyname ("59.125.96.122");
		$TrustHost_Prod_IPs = gethostbynamel($TrustHost_Prod);
		foreach($TrustHost_Prod_IPs as $key=>$value){ 
			if ($posterip == $value) {
				$FromTrustServer=true;
				$message .= " TrustLink!";
			}
		}		
		
		//2 60.248.7.23
		$TrustHost_Prod = gethostbyname ("60.248.7.23");
		$TrustHost_Prod_IPs = gethostbynamel($TrustHost_Prod);
		foreach($TrustHost_Prod_IPs as $key=>$value){ 
			if ($posterip == $value) {
				$FromTrustServer=true;
				$message .= " TrustLink!";
			}
		}		
		
		//3 59.124.227.218
		$TrustHost_Prod = gethostbyname ("59.124.227.218");
		$TrustHost_Prod_IPs = gethostbynamel($TrustHost_Prod);
		foreach($TrustHost_Prod_IPs as $key=>$value){ 
			if ($posterip == $value) {
				$FromTrustServer=true;
				$message .= " TrustLink!";
			}
		}		
		
		//4 210.65.204.100
		$TrustHost_Prod = gethostbyname ("210.65.204.100");
		$TrustHost_Prod_IPs = gethostbynamel($TrustHost_Prod);
		foreach($TrustHost_Prod_IPs as $key=>$value){ 
			if ($posterip == $value) {
				$FromTrustServer=true;
				$message .= " TrustLink!";
			}
		}	
		
		//5 61.219.56.3
		$TrustHost_Prod = gethostbyname ("61.219.56.3");
		$TrustHost_Prod_IPs = gethostbynamel($TrustHost_Prod);
		foreach($TrustHost_Prod_IPs as $key=>$value){ 
			if ($posterip == $value) {
				$FromTrustServer=true;
				$message .= " TrustLink!";
			}
		}	

		if ($FromTrustServer==true) {
			$r_OrderNum= $_POST['OrderNum'];
			$return_array['order_serial'] = "2" . substr($r_OrderNum,4);
			$return_array['paystate'] = 1;
		}else{
			$return_array['error_desc'] = "付款失敗";	
			$return_array['paystate'] = 2;
		}
		return $return_array;
	}
	
	/**
	華南
	**/
	function returnHN($array){
		$return_array = array();
		$return_array['order_serial'] = $array['lidm'];
		$return_array['paystate'] = $array['status'];
		if ($array['status']==0)
			$return_array['paystate'] = 1;
		else
			$return_array['paystate'] = 2;
		$return_array['auth_code'] = $array['authCode'];
		$return_array['error_desc'] = $array['errDesc'];
		$return_array['error_code'] = $array['errcode'];
		$return_array['account_no'] = $array['Last4digitPAN'];
		return $return_array;
	}
	
	/**
	臺新
	**/
	function returnTX($array){
		$return_array = array();
		$return_array['order_serial'] = $array['ordernumber'];
		$return_array['auth_code'] = $array['authCode'];
		if ($array['retcode']=="00")
			$return_array['paystate'] = 1;
		else
			$return_array['paystate'] = 2;
		return $return_array;
	}
	
	/**
	綠界
	**/
	function returnLJ($array){
		$return_array = array();
		$return_array['order_serial'] = $array['od_sob'];
		if ($array['succ']=="1")
			$return_array['paystate'] = 1;
		else
			$return_array['paystate'] = 2;
		$return_array['error_desc'] = $array['response_msg'];
		return $return_array;
	}
	
	/**
	藍新
	**/
	function returnLX($array){
		global $INFO,$DB;
		$return_array = array();
		if ($array['final_result']=="1" && $array['final_return_PRC']=="0" && $array['final_return_SRC']=="0" ){
			$return_array['paystate'] = 1;
			$return_array['timepaid'] = date("Ymd",time());
		}
		else
			$return_array['paystate'] = 2;
		$return_array['order_id'] = $array['P_OrderNumber'];
		$return_array['account_no'] = $array['final_return_ApproveCode'];
		$Sql = "select ot.order_serial from  `{$INFO[DBPrefix]}order_table` ot where ot.order_id='".trim($array[P_OrderNumber])."' ";
		$Query = $DB->query($Sql);
		$Result    = $DB->fetch_array($Query);
		$return_array['order_serial'] = $Result['order_serial'];
		
		return $return_array;
	}
	/**
	藍新
	**/
	function returnLX1($array){
		global $INFO,$DB;
		$return_array = array();
		if ($array['PRC']=="0" && $array['SRC']=="0" ){
			$return_array['paystate'] = 1;
			$return_array['timepaid'] = date("Ymd",time());
		}else
			$return_array['paystate'] = 2;
		$return_array['order_id'] = $array['OrderNumber'];
		$return_array['account_no'] = $array['ApprovalCode'];
		$Sql = "select ot.order_serial from  `{$INFO[DBPrefix]}order_table` ot where ot.order_id='".trim($array[P_OrderNumber])."' ";
		$Query = $DB->query($Sql);
		$Result    = $DB->fetch_array($Query);
		$return_array['order_serial'] = $Result['order_serial'];
		return $return_array;
	}
	/**
	藍新
	**/
	function returnLX2($array){
		$return_array = array();
		if ($array['rc']=="0"){
			if ($return_array['status']==1)
				$return_array['paystate'] = 1;
			else
				$return_array['paystate'] = 3;
		}else
			$return_array['paystate'] = 2;
		$return_array['order_serial'] = $array['ordernumber'];
		if ($array['virtualaccount']!="")
			$return_array['atm'] = $array['virtualaccount'];
		if ($array['paycode']!="")
			$return_array['paycode'] = $array['paycode'];
		$return_array['account_no'] = $array['bankid'];
		$return_array['duedate'] = $array['duedate'];
		$return_array['timepaid'] = $array['timepaid'];
		return $return_array;
	}
	/**
	藍新
	$code               = "abcd1234";
     $merchantnumber     = $HTTP_POST_VARS['merchantnumber'];
     $ordernumber        = $HTTP_POST_VARS['ordernumber'];
     $amount             = $HTTP_POST_VARS['amount'];
     $paymenttype        = $HTTP_POST_VARS['paymenttype'];

     $serialnumber       = $HTTP_POST_VARS['serialnumber'];
     $writeoffnumber     = $HTTP_POST_VARS['writeoffnumber'];
     $timepaid           = $HTTP_POST_VARS['timepaid'];
     $tel                = $HTTP_POST_VARS['tel'];
     $hash               = $HTTP_POST_VARS['hash'];
	**/
	function returnLX2_f($array){
		global $INFO,$DB;
		$Psql = "select * from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.pid='13' order by p.mid";
		$PQuery    = $DB->query($Psql);
		$PRs=$DB->fetch_array($PQuery);
		$f1 = explode("|",$PRs['f1']);
		$code = $f1[1];
		
		
		$return_array = array();
		$return_array['order_serial'] = $array['ordernumber'];
		$return_array['auth_code'] = $array['writeoffnumber'];
		$merchantnumber     = $array['merchantnumber'];
		 $ordernumber        = $array['ordernumber'];
		 $amount             = $array['amount'];
		 $paymenttype        = $array['paymenttype'];
	
		 $serialnumber       = $array['serialnumber'];
		 $writeoffnumber     = $array['writeoffnumber'];
		 $return_array['timepaid'] = $timepaid           = $array['timepaid'];
		 $tel                = $array['tel'];
		 $hash               = $array['hash'];
		$verify = md5("merchantnumber=".$merchantnumber.
                   "&ordernumber=".$ordernumber.
                   "&serialnumber=".$serialnumber.
                   "&writeoffnumber=".$writeoffnumber.
                   "&timepaid=".$timepaid.
                   "&paymenttype=".$paymenttype.
                   "&amount=".$amount.
                   "&tel=".$tel.
                   $code);
		if($hash!=$verify){
			$return_array['paystate'] = 2;
			  //-- 驗證碼錯誤，資料可能遭到竄改，或是資料不是由ezPay簡單付發送
			  //print "驗證碼錯誤!".
					"\nhash=".hash.
					"\nmerchantnumber=".merchantnumber.
					"\nordernumber=".ordernumber.
					"\nserialnumber=".serialnumber.
					"\nwriteoffnumber=".writeoffnumber.
					"\ntimepaid=".timepaid.
					"\npaymenttype=".paymenttype.
					"\namount=".amount.
					"\ntel=".tel;
		 }else{
			 $return_array['paystate'] = 1;
			  
		 }
		return $return_array;
	}
	/**
	PAYPAL
	**/
	function returnPaypal($array){
		include_once('paypal/paypal.class.php');
		$p = new paypal_class;             // initiate an instance of the class
		$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		$return_array = array();
		$return_array['order_serial'] = $array['txn_id'];
		if ( $_GET['action'] == "success"){
			$return_array['paystate'] = 1;
		}
		if ( $_GET['action'] == "cancel"){
			$return_array['paystate'] = 2;
		}
		if ( $_GET['action'] == "ipn"){
			if ($p->validate_ipn()) {
				$return_array['paystate'] = 1;
			}
		}
		return $return_array;
	}
	/**
	Alipay
	**/
	function returnAlipay($array){
		include_once("alipay_notify.php");
		include_once("alipay_config.php");
		global $INFO,$DB;
		$Psql = "select * from `{$INFO[DBPrefix]}paymanager` as pg where pg.pid='15'";
		$PQuery    = $DB->query($Psql);
		$PRs=$DB->fetch_array($PQuery);
		//print_r($PRs);
		$f1 = explode("|",$PRs['f1']);
		$security_code = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$seller_email = $f2[1];
		$partner = $PRs['shopcode'];
		//echo $partner;
		$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);
		
		$verify_result = $alipay->notify_verify();
		if($verify_result) {
			$return_array['paystate'] = 1;
		}else{
			$return_array['paystate'] = 2;	
		}
		$return_array['order_serial'] = $array['out_trade_no'];
		//print_r($return_array);
		return $return_array;
	}
	/**
	合作金庫
	**/
	function returnHZ($array){
		$return_array = array();
		$return_array['order_serial'] = $array['lidm'];
		$return_array['paystate'] = $array['status'];
		if ($array['status']==0)
			$return_array['paystate'] = 1;
		else
			$return_array['paystate'] = 2;
		$return_array['auth_code'] = $array['authCode'];
		$return_array['error_desc'] = $array['errDesc'];
		$return_array['error_code'] = $array['errcode'];
		$return_array['account_no'] = $array['lastPan4'];
		return $return_array;
	}
	
	/**
	逸揚國際
	**/
	function returnYY($array){
		$return_array = array();
		$return_array['order_serial'] = $array['e_oderno'];
		if ($array['str_ok']==1)
			$return_array['paystate'] = 1;
		else
			$return_array['paystate'] = 2;
		$return_array['auth_code'] = $array['str_no'];
		$return_array['error_desc'] = $array['str_msg'];
		return $return_array;
	}
	
	/**
	Iepay
	**/
	function returnIE($array){
		$return_array['order_serial'] = $array['orderid'];	
		if ($array['status']==1)
			$return_array['paystate'] = 1;
		else
			$return_array['paystate'] = 2;
		$return_array['auth_code'] = $array['authcode'];
		return $return_array;
	}
	/*
	超商取货
	*/
	
	function returnEZ($array){
		global $INFO,$DB;
		$return_array = array();
		$return_array['order_serial'] = $array['order_id'];
		if ($array['order_status']!="S01"){
			$return_array['paystate'] = 3;
			/*
			$db_string = $DB->compile_db_update_string( array (
				'storeid'               => trim($array['st_code']),
				'storename'               => trim(iconv("big5","UTF-8",$array['st_name'])),
			));
			$Sql = "UPDATE `{$INFO[DBPrefix]}order_table` SET $db_string WHERE order_serial='".trim($return_array['order_serial'])."'";
			$Result = $DB->query($Sql);
			*/
	    }else{
			$return_array['paystate'] = 0;	
		}
		return $return_array;
	}
	
	/*
	超商取货
	
	
	function returnEC($array){
		global $INFO,$DB;
		$return_array = array();
		$return_array['order_serial'] = $array['cvsid'];
		if ($array['Pkey']!="" && $array['cvsid']!=""){
			$return_array['paystate'] = 3;
			$db_string = $DB->compile_db_update_string( array (
				'cvsnum'               => trim($array['cvsspot']),
				'cvsname'               => trim(iconv("big5","UTF-8",$array['name'])),
			));
			$Sql = "UPDATE `{$INFO[DBPrefix]}order_table` SET $db_string WHERE order_serial='".trim($return_array['order_serial'])."'";
			$Result = $DB->query($Sql);
	    }else{
			$return_array['paystate'] = 0;	
		}
		return $return_array;
	}*/
	function returnEC($array){
		global $INFO,$DB;
		$return_array = array();
		
		$return_array['order_serial'] = $array['tempvar'];
		
			$return_array['paystate'] = 3;
			$db_string = $DB->compile_db_update_string( array (
				'storeid'               => trim($array['storeid']),
				'storename'               => trim($array['storename']),
			));
			$Sql = "UPDATE `{$INFO[DBPrefix]}order_table` SET $db_string WHERE order_serial='".trim($return_array['order_serial'])."'";
			$Result = $DB->query($Sql);
	    
		
		return $return_array;
	}
	
	/**
	國泰
	**/
	function returnGT($array){
		$return_array = array();
		$return_array['order_serial'] = $array['ordernumber'];
		//$return_array['paystate'] = $array['status'];
		if ($array['JPOSresponsecode']=="00")
			$return_array['paystate'] = 1;
		else
			$return_array['paystate'] = 2;
		$return_array['auth_code'] = $array['JPOSapprovecode'];
		return $return_array;
	}
	function returnGTW(){
		global $INFO,$DB;
		$return_array = array();
		$return_array['order_serial'] = $array['MerchantKey'];
		if($array['TrsCode']=="0000"){
			$return_array['paystate'] = 1;
		}else{
			$return_array['paystate'] = 2;	
		}
			
		
	}
	function returnAFB($array){
		global $INFO,$DB;
		$return_array = array();
		$return_array['order_serial'] = $array['MerchantTradeNo'];
		if($array['RtnCode']==1){
			$return_array['paystate'] = 1;
		}elseif($array['RtnCode']==2){
			$return_array['paystate'] = 3;
		}elseif($array['RtnCode']==10100073){
			$return_array['paystate'] = 3;
		}else{
			$return_array['paystate'] = 2;	
		}
		$return_array['TradeNo'] = $array['JPOSapprovecode'];
		$return_array['atm'] = "[" . $array['BankCode'] . "]" . $array['vAccount'];
		if($array['PaymentNo']!="")
			$return_array['paycode'] = $array['PaymentNo'];
		elseif($array['Barcode1']!="" || $array['Barcode2']!="" || $array['Barcode3']!="")
			$return_array['paycode'] = $array['Barcode1'].$array['Barcode2'].$array['Barcode3'];
		return $return_array;
		/*
		$Psql = "select pg.* from  `{$INFO[DBPrefix]}paymanager` as pg  where pg.pid='31'";
		$PQuery    = $DB->query($Psql);
		$PRs=$DB->fetch_array($PQuery);
		$f1 = explode("|",$PRs['f1']);
		$HashKey = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$HashIV = $f2[1];
		$sql = "select * from `{$INFO[DBPrefix]}order_detail` where order_id='" . $return_array['order_serial'] . "'";
		$Query    = $DB->query($sql);
		while($Rs=$DB->fetch_array($Query)){
			$goods_name .= $Rs['goodsname'] . "#";
		}
		$sql = "select * from `{$INFO[DBPrefix]}order_table` where order_id='" . $return_array['order_serial'] . "'";
		$Query    = $DB->query($sql);
		$tRs=$DB->fetch_array($Query);
		$check = md5(strtolower(urlencode("HashKey=" . $HashKey . "&ItemName=" . $array['goods_name'] . "&MerchantID=" . $array['MerchantID'] . "&MerchantTradeDate=" . date("Y/m/d H:i:s",$tRs['createtime']) . "&MerchantTradeNo=" . $return_array['order_serial'] . "&PaymentType=" . $array['PaymentType'] . "&ReturnURL=" . $INFO['site_url'] . $PRs['returnurl'] . "&TotalAmount=" . $array['TradeAmt'] . "&TradeDesc=" . $return_array['order_serial'] . "&HashIV=" . $HashIV)));
		if($check!=$array['CheckMacValue'])
			$return_array['paystate'] = 2;	
		*/
		
	}
	function returnYS($array){
		$return_array = array();
		$return_array['order_serial'] = $array['pno'];
		if ($array['status']=="OK"){
			$return_array['paystate'] = 1;
		}else
			$return_array['paystate'] = 2;
		$return_array['auth_code'] = $array['channel_order_no'];
		return $return_array;
	}
	function returnYSAtm($array){
		global $INFO,$DB;
		$return_array = array();
		$IcpNo=$array["IcpNo"];
		$TransNo=$array["TransNo"];
		$TransAmt=$array["TransAmt"];
		$atmTradeNo=$array["atmTradeNo"];
		$atmTradeDate=$array["atmTradeDate"];
		$atmTradeState=$array["atmTradeState"];
		$atmErrNo=$array["atmErrNo"];
		$atmErrDesc=$array["atmErrDesc"];
		$atmIdentifyNo_New=$array["atmIdentifyNo_New"];
		$Echo=$array["Echo"];
		$Psql = "select p.*,pg.*,p.month as pgmonth from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.pid='28' order by p.mid";
		$PQuery    = $DB->query($Psql);
		$PRs=$DB->fetch_array($PQuery);
		 $f2 = explode("|",$PRs['f2']);
		$hashkey = $f2[1];
		$checkatmIdentifyNo_New  = strtoupper(SHA1( $IcpNo . $TransNo . $TransAmt . $atmTradeNo . $atmTradeDate . $hashkey . $atmTradeState));
		$return_array['order_serial'] = $array['TransNo'];
		$return_array['auth_code'] = $array['atmTradeNo'];
		//if($checkatmIdentifyNo_New!=$atmIdentifyNo_New)
		//	$return_array['paystate'] = 2;
		//else{
			
			if ($array['atmTradeState']=="S"){
				$return_array['paystate'] = 1;
			}else
				$return_array['paystate'] = 3;
			
		//}
		return $return_array;
	}
	function returnHNAtm($array){
		if($array['fail']==1 || $array['ErrorCode']!=""){
			$return_array['paystate'] = 2;
			$return_array['error_desc'] = $array['ErrorCode'];
		}else{
			$return_array['paystate'] = 1;
			 $result = base64_decode($array['val']);
			$result_array = explode("&",$result);
			//print_r($result_array);
			foreach($result_array as $k=>$v){
				$r = explode("=",$v);	
				//print_r($r);
				switch($r[0]){
					case "OrderNumber":
						$return_array['order_serial'] = $r[1];
						break;
					case "StoreTxDate":
						$return_array['order_date'] = $r[1];
						break;
					
				}
			}
		}
		//print_r($return_array);
		return $return_array;
	}
	function returnHY($array){
		$return_array = array();
		$return_array['order_serial'] = $array['Td'];
		$return_array['paycode'] = $array['paycode'];
		if($array['errcode']=="00"){
			$return_array['paystate'] = 1;
		}else{
			$return_array['paystate'] = 2;	
			if($array['PayType']>=1 && $array['PayType']<=4)
				$return_array['paystate'] = 3;	
		}
		return $return_array;	
	}
	function returnYSYl($array){
		global $INFO,$DB,$FUNCTIONS;
		$f1 = explode("|",$PRs['f1']);
		$key = $f1[1];
		$return_array = array();
		$return_array['order_serial'] = $array['ONO'];
		//$m = md5($array['RC'] . "&" . $array['MID'] . "&" . $array['ONO'] . "&" . $array['LTD'] . "&" . $array['LTT'] . "&" . $array['RRN'] . "&" . $array['AIR'] . "&" . $array['AN'] . "&" . $key);
		if ($array['RC']=="00"){
			$return_array['paystate'] = 1;
		}else
			$return_array['paystate'] = 2;
		$return_array['auth_code'] = $array['AN'];
		
		return $return_array;	
	}
	function returnYSYls($array){
		global $INFO,$DB,$FUNCTIONS;
		$f1 = explode("|",$PRs['f1']);
		$key = $f1[1];
		$return_array = array();
		$return_array['order_serial'] = $array['ONO'];
		//$m = md5($array['RC'] . "&" . $array['MID'] . "&" . $array['ONO'] . "&" . $array['LTD'] . "&" . $array['LTT'] . "&" . $array['RRN'] . "&" . $array['AIR'] . "&" . $array['AN'] . "&" . $key);
		if ($array['RC']=="00"){
			$return_array['paystate'] = 1;
		}else
			$return_array['paystate'] = 2;
		$return_array['auth_code'] = $array['AN'];
		
		return $return_array;	
	}
	function returnZfb($array){
		global $INFO,$DB;
		$return_array = array();
		$json_array = (array)json_decode($array['JSONData']);
		
		if($json_array['Status']=="SUCCESS"){
			$result = (array)json_decode($json_array['Result']);
			$return_array['order_serial'] = $result['MerchantOrderNo'];
			 $Sql = "select ot.paymentid from  `{$INFO[DBPrefix]}order_table` ot where ot.order_serial='".$return_array['order_serial']."' ";
			$Query = $DB->query($Sql);
			$Results    = $DB->fetch_array($Query);
			 $paymentid = $Results['paymentid'];
			if($paymentid>=154 && $paymentid<=158)
				$return_array['paystate'] = 1;
			else
				$return_array['paystate'] = 3;
			if($result['Auth']!="")
				$return_array['paycode'] = $result['Auth'];
			if($result['PayBankCode']!="")
				$return_array['atm'] = $result['PayBankCode'] . $result['PayerAccount5Code'];
			if($result['CodeNo']!="")
				$return_array['paycode'] = $result['BankCode'].$result['CodeNo'];
			if($result['Barcode_1']!="")
				$return_array['paycode'] = $result['Barcode_1'] . $result['Barcode_2']. $result['Barcode_3'];
		}else{
			$return_array['paystate'] = 2;		
		}
		return $return_array;	
	}
	function returnZH($array){
		$return_array = array();
		$return_array['order_serial'] = $array['lidm'];
		$return_array['paystate'] = $array['status'];
		if ($array['status']==0 && $array['authCode']!=""&& $array['xid']!="")
			$return_array['paystate'] = 1;
		else
			$return_array['paystate'] = 2;
		$return_array['auth_code'] = $array['authCode'];
		$return_array['error_desc'] = $array['errDesc'];
		$return_array['error_code'] = $array['errcode'];
		$return_array['account_no'] = $array['Last4digitPAN'];
		return $return_array;
	}
	/**
	第一銀行
	**/
	function returnDy($array){
		$return_array = array();
		$return_array['order_serial'] = $array['lidm'];
		$return_array['paystate'] = $array['status'];
		if ($array['status']==0)
			$return_array['paystate'] = 1;
		else
			$return_array['paystate'] = 2;
		$return_array['auth_code'] = $array['authCode'];
		$return_array['error_desc'] = $array['errDesc'];
		$return_array['error_code'] = $array['errcode'];
		$return_array['account_no'] = $array['lastPan4'];
		return $return_array;
	}
}
?>
