<?php
class PayFunction{
	function CreatePay($paytype,$array){
		global $INFO,$DB;
		$Psql = "select p.*,pg.*,p.month as pgmonth from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.mid='" . $paytype . "' order by p.mid";
		$PQuery    = $DB->query($Psql);
		$PRs=$DB->fetch_array($PQuery);
		//print_r($PRs);
		//echo $paytype;
		if ($PRs['pid']==1)
			$return =  $this->TWPay($PRs,$array);
		if ($PRs['pid']==3)
			$return =  $this->LHPay($PRs,$array);
		if ($PRs['pid']==4 || $PRs['pid']==5 || $PRs['pid']==6)
			$return =  $this->ZXPay($PRs,$array);
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
		if ($PRs['pid']==17 || $PRs['pid']==18)
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
	function LHPay($PRs,$array){
		global $INFO,$DB;
		$f1 = explode("|",$PRs['f1']);
		$TerminalID = $f1[1];
		return "
		<form name=\"form_lhpay\" id=\"form_lhpay\" method=\"post\" ACTION=\"" . $INFO['site_url'] . "/shopping/cart_paylast.php\">
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
	}
	
	/**
	藍新
	**/
	function LXPay($PRs,$array){
		global $INFO,$DB;
		$lanxin_orderno = $array['order_id'];
		$f1 = explode("|",$PRs['f1']);
		$rcode = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$code = $f2[1];
		if($PRs['pid']==11 || $PRs['pid']==12){
			$checkcode = md5($PRs['shopcode'].$lanxin_orderno.$rcode.number_format($array['total']*1.00,2,".",""));
			$Amount = number_format($array['total']*1.00,2,".","");
		}
		if($PRs['pid']==13){
			$checkcode = md5($PRs['shopcode'].$rcode.intval($array['total']) . $array['order_serial']);
			$Amount = intval($array['total']);
		}
		$return = "
		<form action=\"" . $PRs['payurl'] . "\" method=post name=\"form_lxpay\" id=\"form_lxpay\">
			<input type=hidden name=\"MerchantNumber\" value=\"" . $PRs['shopcode'] . "\">
                    
			<input type=hidden name=\"Amount\"         value=\"" . $Amount . "\">
			<input type=hidden name=\"ApproveFlag\"    value=\"1\">
			<input type=hidden name=\"DepositFlag\"    value=\"1\">
			<input type=hidden name=\"Englishmode\"    value=\"0\">   ";    
                                      
			if($PRs['pid']==11 || $PRs['pid']==12){
			$return .= "<input type=hidden name=\"checksum\"       value=\"" . $checkcode . "\">
			<input type=hidden name=\"OrderNumber\"    value=\"" . $lanxin_orderno . "\">
			<input type=hidden name=\"OrgOrderNumber\" value=\"" . $array['order_serial'] . "\">
			<input type=hidden name=\"op\"             value=\"AcceptPayment\">
			<input type=hidden name=\"OrderURL\"       value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "&type=feedback\">
			<input type=hidden name=\"ReturnURL\"      value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "&type=receive\">";
			}else{
			 $return .= "<input type=hidden name=\"bankid\"    value=\"004\">
			<input type=hidden name=\"ordernumber\"    value=\"" . $array['order_serial'] . "\">
			<input type=\"hidden\" name=\"returnvalue\" value=\"0\">
			<input type=\"hidden\" name=\"paytitle\" value=\"" . $array['order_serial'] . "\">
			<input type=hidden name=\"paymenttype\" value=\"" . $PRs['payno'] . "\">
			<input type=hidden name=\"payname\" value=\"" . $array['receiver_name'] . "\">
			<input type=hidden name=\"payphone\" value=\"" . $array['receiver_mobile'] . "\">
			<input type=\"hidden\" name=\"hash\" value=\"" . $checkcode . "\">
			<input type=\"hidden\" name=\"nexturl\" value=\"" . $INFO['site_url'] . $PRs['returnurl'] . "\">";
			
			}
			if($PRs['pid']==12){
			$return .= "<input type=hidden name=\"Period\"         value=\"" . intval($PRs['pgmonth']) . "\">";
			}
		$return .= "</form>
		<script language=\"javascript\">
			alert('請稍後，轉到藍新科技付款頁面');
			setTimeout(\"document.all.form_lxpay.submit()\",2000);
		</script>
		";
		return $return;
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
		<input type=\"hidden\" name=\"currency_code\" value=\"USD\">
		<input type=\"hidden\" name=\"lc\" value=\"US\">
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
		$form_i = 1;
		$Query_detail = $DB->query(" select gid,goodsname,market_price,price,goodscount from `{$INFO[DBPrefix]}order_detail` where order_id=".intval($array['order_id'])." order by order_detail_id desc ");
		while ($Rs_detail = $DB->fetch_array($Query_detail)){
			$form_hy .= "<input type=hidden name=ProductName" . $form_i . " value='" . $Rs_detail['goodsname'] . "' > <!-- 購買的第一項產品 --><input type=hidden name=ProductPrice" . $form_i . " value='" . $Rs_detail[price] . "' > <!-- 購買的第一項產品價格 --><input type=hidden name=ProductQuantity" . $form_i . " value='" . $Rs_detail[goodscount] . "' >  <!-- 購買的第一項產品數量 -->";
			$form_i++;
		}

		$DueDate = date("Ymd",time());
		$return .="
		<form method=\"post\" name=\"payform_seven5\" action=\"" . $PRs['payurl'] . "\" >
		<input type=\"hidden\" name=\"web\" value=\"" . $PRs['shopcode'] . "\"> <!--商店代號 maxlength=\"10\" -->
		";
		if ($array['pid']==18)
			$return .= "<input type=hidden name=DueDate value=" . $DueDate . " > <!-- 繳費期限 -->
			" . $form_hy . "
			<input type=hidden name=UserNo value=" . $array['userno'] . " > <!-- 自訂給予下訂單的用戶之用戶編號 -->
			<input type=hidden name=BillDate value=" . $DueDate . " >
			";
		$return .="
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
		</form>
		";
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
	function EZPay($PRs,$array){
	//	print_r($array);
		global $INFO,$DB;
		$f1 = explode("|",$PRs['f1']);
		$checkno = $f1[1];
		$f2 = explode("|",$PRs['f2']);
		$webtemp = $f2[1];
		return "
		<form name=\"form_ezpay\" id=\"form_ezpay\" method=\"post\" ACTION=\"" . $PRs['payurl'] . "\">
		<!-input type=\"hidden\" name=\"payurl\" value=\"" . $PRs['payurl'] . "\"-->
		  <input type=\"hidden\" name=\"su_id\" value=\"" . $PRs['shopcode'] . "\">
		   <input type=\"hidden\" name=\"order_id\" value=\"" . $array['order_serial'] . "\"> 
		    <input type=\"hidden\" name=\"rturl\" value=\"" .($INFO['site_url'] . $PRs['returnurl']) . "\">
			<input type=\"hidden\" name=\"rv_name\" value=\"" . ($array['receiver_name']) . "\">
			<input type=\"hidden\" name=\"rv_email\" value=\"" . $array['receiver_email'] . "\"> 
			<input type=\"hidden\" name=\"rv_mobil\" value=\"" . $array['receiver_mobile'] . "\">
			<input type=\"hidden\" name=\"rv_amount\" value=\"" . $array['total'] . "\"> 
			<input type=\"hidden\" name=\"webtemp\" value=\"" . $webtemp . "\"> 
		   <input type=hidden value='選擇取貨超商'>
		</form>
		
		<script language=\"javascript\">
			alert('請稍後，轉到超商取貨系統頁面');
			setTimeout(\"document.all.form_ezpay.submit()\",2000);
			//window.open('http://www.ezship.com.tw/emap/rv_request_web.jsp?su_id=" . $PRs['shopcode'] . "&order_id=" . $array['order_serial'] . "&rturl=" . $INFO['site_url'] . $PRs['returnurl'] . "&rv_name=" . $array['receiver_name'] . "&rv_email=" . $array['receiver_email'] . "&rv_mobil=" . $array['receiver_mobile'] . "&rv_amount=" . $array['total'] . "&webtemp=');
		</script>
		";	
	}
	
	/*
	超商取货
	*/
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
		if ($_GET['pid']==13)
			$return =  $this->returnLX2($array);
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
		if (intval($_GET['webtemp'])==25){
			$return =  $this->returnEZ($array);
		}
		if (intval($_GET['cvstemp'])==26){
			$return =  $this->returnEC($array);
		}
		if ($array['pid']==29)
			$return =  $this->returnGT($array);
		if (intval($array['pid'])==30){
			$return =  $this->returnGTW($array);
		}
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
		if ($array['final_result']=="1")
			$return_array['paystate'] = 1;
		else
			$return_array['paystate'] = 2;
		$return_array['order_id'] = $array['P_OrderNumber'];
		$return_array['auth_code'] = $array['final_return_ApproveCode'];
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
		if ($array['PRC']=="0" && $array['SRC']=="0")
			$return_array['paystate'] = 1;
		else
			$return_array['paystate'] = 2;
		$return_array['order_id'] = $array['OrderNumber'];
		$return_array['auth_code'] = $array['ApprovalCode'];
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
		if ($array['rc']=="0")
			$return_array['paystate'] = 1;
		else
			$return_array['paystate'] = 3;
		$return_array['order_serial'] = $array['ordernumber'];
		$return_array['account_no'] = $array['virtualaccount'];
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
		if ($array['st_code']!="" && $array['sn_id']!="00000000"){
			$return_array['paystate'] = 3;
			$db_string = $DB->compile_db_update_string( array (
				'storeid'               => trim($array['st_code']),
				'storename'               => trim(iconv("big5","UTF-8",$array['st_name'])),
			));
			$Sql = "UPDATE `{$INFO[DBPrefix]}order_table` SET $db_string WHERE order_serial='".trim($return_array['order_serial'])."'";
			$Result = $DB->query($Sql);
	    }else{
			$return_array['paystate'] = 0;	
		}
		return $return_array;
	}
	
	/*
	超商取货
	*/
	
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
}
?>
