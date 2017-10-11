<?php

error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("global.php");

include_once "PayFunction.php";
//$_POST['P_OrderNumber'] = "21";
if (isset($_POST)||isset($_GET)){
	$array = array_merge($_POST,$_GET);
	$array['pid'] = 29;
	$_GET['pid'] = 29;
	$payFunction = new PayFunction;
	$return_array = $payFunction->returnPay($array);
	
	//print_r($return_array);
	$ip = $FUNCTIONS->getip();
	//if (!($_SESSION['shopping_ip'] == $ip && ($_SESSION['shopping_orderserial'] == trim($return_array[order_serial]) || $_SESSION['shopping_orderid'] == trim($return_array[order_id])))){
	//	$FUNCTIONS->header_location($INFO['site_url'] . "/index.php");	
	//	exit;
	//}
	
	if ($return_array['order_serial'] != ""){
		$Sql = "select ot.order_serial,ot.order_id,od.goodsname from  `{$INFO[DBPrefix]}order_table` ot inner join  `{$INFO[DBPrefix]}order_detail` od on (ot.order_id=od.order_id) where ot.order_serial='".trim($return_array[order_serial])."' ";
		$Query = $DB->query($Sql);
		$Num =   $DB->num_rows($Query);
		if ($Num>0){
			$Rs    = $DB->fetch_array($Query);
			$db_string = $DB->compile_db_update_string( array (
			'online_paytype'          => intval($return_array['pay_type']),
			'pay_state'               => intval($return_array['paystate']),
			'onlinepay'               => 2,
			'online_cardnum'          => $return_array['account_no'],
			));
			$Sql = "UPDATE `{$INFO[DBPrefix]}order_table` SET $db_string WHERE order_serial='".($return_array['order_serial'])."'";
			$Result = $DB->query($Sql);
			$Sql = "UPDATE `{$INFO[DBPrefix]}order_detail` set detail_pay_state=" . intval($return_array['paystate']) . " WHERE order_id='".($Rs['order_id'])."'";
			$Result = $DB->query($Sql);
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
		}
	}
}

?>
<html>
<head></head>
<body>
	<form name="Form1" method="post" action="<?php echo $INFO['site_url'];?>">
		<input type="text" name="storeid" value="<?php echo $_GET['storeid'];?>" />
        <input type="text" name="ordernumber" value="<?php echo $_GET['ordernumber'];?>" />
        <input type="text" name="amount" value="<?php echo $_GET['amount'];?>" />
        <input type="text" name="JPOSauthamount" value="<?php echo $_GET['JPOSauthamount'];?>" />
        <input type="text" name="JPOSresponsecode" value="<?php echo $_GET['JPOSresponsecode'];?>" />
        <input type="text" name="JPOSauthstatus" value="<?php echo $_GET['JPOSauthstatus'];?>" />
        <input type="text" name="JPOSapprovecode" value="<?php echo $_GET['JPOSapprovecode'];?>" />
        <input type="text" name="JPOStransdate" value="<?php echo $_GET['JPOStransdate'];?>" />
        <input type="text" name="JPOStranstime" value="<?php echo $_GET['JPOStranstime'];?>" />
	</form>
</body>
<?php
if ($return_array['paystate'] !=1 ){
?>
<script language="javascript">
   location.href = "<?php echo $INFO['site_url'];?>/shopping/payorder.php?orderno=<?php echo trim($return_array[order_serial])?>";
</script>
<?php

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
			$SMTP =  new smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
			//echo $_POST['ifsendreceiver'];echo "d";
			if($_POST['ifsendreceiver']==1)
				$useremail .= "," . $_POST['receiver_email'];
				//echo $useremail.",".$receiver_email;
			$SMTP->MailForsmartshop($useremail.",".$receiver_email . "," . "pigangel_yang@aliyun.com",$cmail,6,$Array);
			echo "<script language=\"javascript\">
		   location.href = \"showorder.php?orderno=" . trim($return_array[order_serial]) . "\";
		   </script>";
	
}

?>
</html>

