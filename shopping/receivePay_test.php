<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("global.php");
include_once 'crypt.class.php';
include_once "PayFunction.php";
//$_POST['P_OrderNumber'] = "21";
$req = 'cmd=_notify-validate';
foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}
foreach ($_GET as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}

//建议在此将接受到的信息记录到日志文件中以确认是否收到 IPN 信息
$Creatfile ="log/".date("Ymd")."-" . time();
$fh = fopen( $Creatfile.'.txt', 'w+' );
@chmod ($Creatfile.'.txt',0777);
fputs ($fh, $req, strlen($req) );
fclose($fh);
//print_r($_GET);exit;
if (isset($_POST)||isset($_GET)){
	$array = array_merge($_POST,$_GET);
	//$array['pid'] = $_GET['pid'];
	//$array['type'] = $_GET['type'];
	//$payFunction = new PayFunction;
	//$return_array = $payFunction->returnPay($array);
	//print_r($array);print_r($return_array);exit;
	$ip = $FUNCTIONS->getip();
	/*
	if (!($_SESSION['shopping_ip'] == $ip && ($_SESSION['shopping_orderserial'] == trim($return_array[order_serial]) || $_SESSION['shopping_orderid'] == trim($return_array[order_id])))){
		$FUNCTIONS->header_location("../index.php");	
		exit;
	}
	*/
	$return_array['order_serial'] = "2014052300049";
	if ($return_array['order_serial'] != ""){
		$Sql = "select ot.*,od.goodsname from  `{$INFO[DBPrefix]}order_table` ot inner join  `{$INFO[DBPrefix]}order_detail` od on (ot.order_id=od.order_id) where ot.order_serial='".trim($return_array[order_serial])."' ";
		$Query = $DB->query($Sql);
		$Num =   $DB->num_rows($Query);
		if ($Num>0){
			$Rs    = $DB->fetch_array($Query);
			$paymentid  = $Rs['paymentid'];
			$deliveryid  = $Rs['deliveryid'];
			if (!($_GET['webtemp']==25 || intval($_GET['cvstemp'])==26) || $deliveryid==69 || $paymentid==70){
				if (intval($return_array['paystate'])==1)
					$subsql = ",order_state=1";
				$db_string = $DB->compile_db_update_string( array (
				'online_paytype'          => intval($return_array['pay_type']),
				'pay_state'               => intval($return_array['paystate']),
				'onlinepay'               => 2,
				'online_cardnum'          => $return_array['account_no'],
				'atm'          => $return_array['atm'],
				'paycode'          => $return_array['paycode'],
				));
				$Sql = "UPDATE `{$INFO[DBPrefix]}order_table` SET $db_string" . $subsql . " WHERE order_serial='".($return_array['order_serial'])."'";
				//$Result = $DB->query($Sql);
				if (intval($return_array['paystate'])==1)
					$subsql = ",detail_order_state=1";
				$Sql = "UPDATE `{$INFO[DBPrefix]}order_detail` set detail_pay_state=" . intval($return_array['paystate']) . "" . $subsql . " WHERE order_id='".($Rs['order_id'])."'";
				//$Result = $DB->query($Sql);
				if(intval($return_array['paystate'])==0){
					$State = 0;	
				}
				if(intval($return_array['paystate'])==1){
					$State = 1;	
				}
				if(intval($return_array['paystate'])==2){
					$State = 2;	
				}
				
			}
			$Order_id  = $Rs['order_id'];
			$user_id  = $Rs['user_id'];
			$Order_serial = $order_serial  = $Rs['order_serial'];
			$Cart_discount_totalPrices = $discount_totalPrices  = $Rs['discount_totalPrices'];
			$totalprice  = $Rs['totalprice'];
			$Cart_transmoney = $transport_price  = $Rs['transport_price'];
			$deliveryname  = $Rs['deliveryname'];
			$receiver_name = $Rs['receiver_name'];
			$receiver_address = $Rs['receiver_address'];
			$receiver_email = $Rs['receiver_email'];
			$receiver_post = $Rs['receiver_post'];
			$receiver_tele = $Rs['receiver_tele'];
			$receiver_mobile = $Rs['receiver_mobile'];
			$receiver_memo = $Rs['receiver_memo'];
			$paymentid  = $Rs['paymentid'];
			$deliveryid  = $Rs['deliveryid'];
			$Cart_buypoint  = $Rs['buyPoint'];
			$MallgicOrderId = $Rs['MallgicOrderId'];
			$token="d96ef98d76a64c2c9d7e5a0d819efacd";
			$fb_url = "http://os.iqbi.com/utvfb/method.aspx";
			if ($MallgicOrderId!="")
				$result_fb = file_get_contents($fb_url . "?op=updateorderstatus&moid=" . $MallgicOrderId . "&utvorderid=" . $Order_serial . "&orderstatus=" . $State . "&token=" . $token);
		}
	}
}

if ($return_array['paystate'] ==2 &&  intval($_GET['webtemp'])!=25){
?>
<script language="javascript">
   location.href = "payorder.php?orderno=<?php echo trim($return_array[order_serial])?>";
</script>
<?php

}else{
	//if ($_GET['webtemp']==25 || intval($_GET['cvstemp'])==26 || $paymentid==69 || $deliveryid==13){
	/**
	金流
	**/
	$paySql = "select *,p.content as pcontent from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pm on p.pid=pm.pid where p.ifopen=1 and p.mid='" . intval($paymentid) . "' order by pm.paytype desc,p.mid";
	$payQuery    = $DB->query($paySql);
	$payNum      = $DB->num_rows($payQuery);
	if ($payNum>0){
		$payRs = $DB->fetch_array($payQuery);
		$paymentname = $payRs['methodname'];	
		$paytype = intval($payRs['paytype']);
	}
	if ($paymentid!=69 && $deliveryid==19 && $paytype==0 &&  intval($_GET['webtemp'])==25 && $Cart_discount_totalPrices+$Cart_transmoney-$Cart_buypoint>0){
		$pay_array = array(
				  "order_serial"    =>   $Order_serial,
				  "total"    =>   round($Cart_discount_totalPrices+$Cart_transmoney-$Cart_buypoint,0),
				  "receiver_name"   => $receiver_name,
				  "receiver_address"   => $receiver_address,
				  "receiver_tele"   => trim(MD5Crypt::Decrypt ($receiver_tele, $INFO['tcrypt'] )),
				  "receiver_mobile"   => trim(MD5Crypt::Decrypt ($receiver_mobile, $INFO['mcrypt'] )),
				  "receiver_email"   => trim($receiver_email),
				  "receiver_post"   => trim($receiver_post),
				  "order_id"   => $Order_id,
				  "userno"   => $uno,
				  "pay_id"   => $paymentid,
				  );
		//print_r($pay_array);exit;
		$payFunction = new PayFunction;
		echo $payform = $payFunction->CreatePay($paymentid,$pay_array);

	}else{
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}sysmail` where id=1");
		$Result= $DB->fetch_array($Query);
		if ($Result['mail']!=""){
			$cmail = $Result['mail'];	
		}
		//if($Order_id>0){
			$Sql = "SELECT  * FROM `{$INFO[DBPrefix]}user`  where user_id='".intval($user_id)."' limit 0,1";
			$Query  = $DB->query($Sql);
			$Rs=$DB->fetch_array($Query);
			$caddress = str_replace("請選擇","",$Rs[Country].$Rs[canton].$Rs[city]);//地址
			$ctel = $Rs[tel].",".$Rs['other_tel'];//电话
			$recommendno = $Rs['recommendno'];
			$companyid = $Rs['companyid'];
			$truename = $Rs['true_name'];
			$useremail = $Rs['email'];
		
			$Array =  array("Order_id"=>$Order_id,"receiver_name"=>trim($receiver_name),"truename"=>trim($receiver_name),"orderid"=>trim($Order_serial));
			
			include "SMTP.Class.inc.php";
			include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
			$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
			//echo $_POST['ifsendreceiver'];echo "d";
			if($_POST['ifsendreceiver']==1)
				$useremail .= "," . $_POST['receiver_email'];
			$SMTP->MailForsmartshop($useremail.",".$receiver_email . ",pigangel_yang@aliyun.com",$cmail,6,$Array);
			echo "<script language=\"javascript\">
		   location.href = \"showorder.php?orderno=" . trim($return_array[order_serial]) . "\";
		   </script>";
		//}else{
		//	$FUNCTIONS->header_location("../index.php");	
		//	exit;	
		//}
	}
?>
<script language="javascript">
 //  location.href = "showorder.php?orderno=<?php echo trim($return_array[order_serial])?>";
</script>
<?php
/*
}else{
		$pay_array = array(
				  "order_serial"    =>   $Order_serial,
				  "total"    =>   round($Cart_discount_totalPrices+Cart_transmoney,0),
				  "receiver_name"   => $receiver_name,
				  "receiver_address"   => $receiver_address,
				  "receiver_tele"   => trim($receiver_tele),
				  "receiver_mobile"   => trim($receiver_mobile),
				  "receiver_email"   => trim($receiver_email),
				  "receiver_post"   => trim($receiver_post),
				  "order_id"   => $Order_id,
				  "userno"   => $uno,
				  );
		$payFunction = new PayFunction;
		echo $payform = $payFunction->CreatePay(69,$pay_array);
	}
	*/
}

?>


