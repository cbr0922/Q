<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include("global.php");
include_once 'crypt.class.php';
include_once "PayFunction.php";
include_once "orderClass.php";
$orderClass = new orderClass;
//
//$_POST['P_OrderNumber'] = "21";
$req = 'cmd=_notify-validate';
if(is_array($_POST)){
foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}}
if(is_array($_GET)){
foreach ($_GET as $key => $value) {
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}
}
//建议在此将接受到的信息记录到日志文件中以确认是否收到 IPN 信息
$Creatfile ="log/".date("Ymd")."-" . time() . "-25";
$fh = fopen( $Creatfile.'.txt', 'w+' );
@chmod ($Creatfile.'.txt',0777);
fputs ($fh, $req, strlen($req) );
fclose($fh);

if (isset($_POST)||isset($_GET)){
	$array = array_merge($_POST,$_GET);
	$array['pid'] = 25;
	//$array['type'] = $_GET['type'];
	$payFunction = new PayFunction;
	$return_array = $payFunction->returnPay($array);
	//print_r($array);print_r($return_array);exit;
	$ip = $FUNCTIONS->getip();
	/*
	if (!($_SESSION['shopping_ip'] == $ip && ($_SESSION['shopping_orderserial'] == trim($return_array[order_serial]) || $_SESSION['shopping_orderid'] == trim($return_array[order_id])))){
		$FUNCTIONS->header_location("../index.php");	
		exit;
	}
	*/
	
	if ($return_array['order_serial'] != ""){
		$Sql = "select ot.*,od.goodsname from  `{$INFO[DBPrefix]}order_table` ot inner join  `{$INFO[DBPrefix]}order_detail` od on (ot.order_id=od.order_id) where ot.order_serial='".trim($return_array[order_serial])."' ";
		$Query = $DB->query($Sql);
		$Num =   $DB->num_rows($Query);
		if ($Num>0){
			$Rs    = $DB->fetch_array($Query);
			$paymentid  = $Rs['paymentid'];
			$deliveryid  = $Rs['deliveryid'];
			$Order_id  = $Rs['order_id'];
			$ifsendreceiver  = $Rs['ifsendreceiver'];
			$receiver_email  = $Rs['receiver_email'];
			//if(($array['web_para']==25 && $paymentid==69) || (intval($array['pid'])==26 && $paymentid==70) || ($array['web_para']!=25 && intval($_GET['cvstemp'])!=26)){
				if (intval($return_array['paystate'])==1)
					$subsql = ",order_state=1";
				$db_string = $DB->compile_db_update_string( array (
				'online_paytype'          => intval($return_array['pay_type']),
				'pay_state'               => intval($return_array['paystate']),
				'onlinepay'               => 2,
				'online_cardnum'          => $return_array['account_no'],
				//'atm'          => $return_array['atm'],
				'paycode'          => $return_array['paycode'],
				));
				if (intval($return_array['paystate'])==1)
					$orderClass->setOrderState(1,2,$Order_id,"線上支付",0,0);
				$Sql = "UPDATE `{$INFO[DBPrefix]}order_table` SET $db_string" . $subsql . " WHERE order_serial='".($return_array['order_serial'])."'";
				$Result = $DB->query($Sql);
				if (intval($return_array['paystate'])==1)
					$subsql = ",detail_order_state=1";
				$Sql = "UPDATE `{$INFO[DBPrefix]}order_detail` set detail_pay_state=" . intval($return_array['paystate']) . "" . $subsql . " WHERE order_id='".($Rs['order_id'])."'";
				$Result = $DB->query($Sql);
				if(intval($return_array['paystate'])==0){
					$State = 0;	
				}
				if(intval($return_array['paystate'])==1){
					$State = 1;	
				}
				if(intval($return_array['paystate'])==2){
					$State = 2;	
				}
				
		//	}
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

if ($return_array['paystate'] ==2 &&  intval($array['web_para'])!=25){
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
	if ($paymentid!=69 && $Cart_discount_totalPrices+$Cart_transmoney-$Cart_buypoint>0 && $paytype==0){
		$Query_detail = $DB->query(" select g.*,gd.bn from `{$INFO[DBPrefix]}order_detail` as g inner join `{$INFO[DBPrefix]}goods` as gd on g.gid=gd.gid where order_id=".intval($Order_id)." and packgid=0 order by order_detail_id asc ");
		$i = 0 ;
		while ($Rs_detail = $DB->fetch_array($Query_detail)){
			$goods_bn .= $Rs_detail['gid'] . "||";
			$goods_name .= $flag.$Rs_detail['goodsname'];
			$goods_price .= $flag.$Rs_detail['price'];
			$goods_count .= $flag.$Rs_detail['goodscount'];
			$oe .= $item['oeid'] . "||";
			$flag = "#";
		}
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
				  "truename"   => $truename,
				  "useremail"   => $useremail,
				  "other_tel"   => $other_tel,
				  "st_code"   => $cart->cvscate . $cart->cvsnum,
				  "goods_name"   => $goods_name,
				  "goods_price"   => $goods_price,
				  "goods_count"   => $goods_count,
				  "date"   => $Time
				  );
		print_r($pay_array);exit;
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
		$other_tel = MD5Crypt::Decrypt ($Rs['other_tel'], $INFO['mcrypt']);
		
			$Array =  array("Order_id"=>$Order_id,"receiver_name"=>trim($receiver_name),"truename"=>trim($receiver_name),"orderid"=>trim($Order_serial));
			
			//include "SMTP.Class.inc.php";
			
			//include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
			//$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
			//echo $_POST['ifsendreceiver'];echo "d";
			if($ifsendreceiver==1)
					$useremail .= "," . $receiver_email;
			
			$SMTP->MailForsmartshop($useremail,$cmail,6,$Array);
		include_once "sms2.inc.php";
				include_once "sendmsg.php";
				$sendmsg = new SendMsg;
				$sendmsg->send($other_tel,$Array,6);
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


