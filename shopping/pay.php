<?php
session_start();
include("../configs.inc.php");
include("global.php");
include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";

@header("Content-type: text/html; charset=utf-8");

$orderid = intval($_POST['orderid']);
$pay_id = intval($_POST['pay_id']);
/**
金流
**/
$paySql = "select *,p.content as pcontent from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pm on p.pid=pm.pid where p.ifopen=1 and p.mid='" . intval($_POST['pay_id']) . "' order by pm.paytype desc,p.mid";
$payQuery    = $DB->query($paySql);
$payNum      = $DB->num_rows($payQuery);
if ($payNum>0){
	$payRs = $DB->fetch_array($payQuery);
	$paymentname = $payRs['methodname'];
	$paytype = $payRs['paytype'];
	$pcontent = $payRs['pcontent'];
	if ($payRs['payno']=="ATM"){
		$The_End_Num = getCheckNo(trim($INFO["ATM"]).$ATM_Num,$INFO["ATM_SECTION"]);
		$ATM_STRING = trim($INFO["ATM"]).$ATM_Num.$The_End_Num;
	}else{
		$ATM_STRING = $Basic_Command['NullDate'];
	}
}
$tpl->assign("paymentname",     $paymentname);   //ATM
function getCheckNo($cardNo,$ATM_SECTION){
	$M = 0;
	$StrcardNo=trim($cardNo);
	$Section_Array = explode("|",trim($ATM_SECTION));
	$Section_Num   = count($Section_Array);

	for ($i=0;$i<$Section_Num;$i++){
		$temp_String=substr($StrcardNo,$i,1);
		$M=$M+intval($temp_String)*$Section_Array[$i];
	}
	$Y=intval($M/11);
	$R=$M % 11;
	if ( $R == 0 ){
		$X=10;
	}elseif ( $R == 1 ) {
		$X=11;
	}else{
		$X = $R;
	}
	$getCheckNo=11-intval($X);
	return $getCheckNo;
}
//虛擬帳號
function ZXAcc($ordernum,$shopno,$total){
	global $INFO;
	if(strlen($ordernum)<8)
		$ordernum = str_repeat("0",8-strlen($ordernum)) . $ordernum;
	if(strlen($total)<8)
		$total = str_repeat("0",8-strlen($total)) . $total;
	$a = "371371371371371";
	$b = "37137137";
	$len1 = strlen($shopno.$ordernum);
	$quan1 = substr($a,13-$len1);
	$len2 = strlen($total);
	$quan2 = substr($b,8-$len2);
	$zong1=0;
	$zong2=0;
	$string1 = $shopno.$ordernum;
	for($i=0;$i<$len1;$i++){
		$zong1+=(substr($string1,$i,1)*substr($quan1,$i,1))%10;
	}
	for($i=0;$i<$len2;$i++){
		$zong2+=(substr($total,$i,1)*substr($quan2,$i,1))%10;
	}
	$checkno = ($zong1%10+$zong2%10)%10;
	$checkno = 10-$checkno;
	if ($checkno==10)
		$checkno = 0;
	$xn = $shopno.$ordernum . $checkno;
	return $xn;
}
function ZHXNAcc($ordernum,$shopno,$total){
	$sunday = 0;
	if(strlen($ordernum)<4)
		$ordernum = str_repeat("0",4-strlen($ordernum)) . $ordernum;
	if(strlen($total)<8)
		$total = str_repeat("0",8-strlen($total)) . $total;
	$times = time()+6*60*60*24;
	for($i=1;$i<date("m",$times);$i++){
		$sunday += getDays($i,date("Y",$times))	;
	}
	$sunday = $sunday+date("d",$times);
	if(strlen($sunday)<3)
		$sunday = str_repeat("0",3-strlen($sunday)) . $sunday;
	$sunday = date("y",$times)%10 . $sunday;
	$a = "7317317317317";
	$b = "31731731";
	$len1 = strlen($shopno.$sunday.$ordernum);
	$quan1 = substr($a,13-$len1);
	$len2 = strlen($total);
	$quan2 = substr($b,8-$len2);
	$zong1=0;
	$zong2=0;
	$string1 = $shopno.$sunday.$ordernum;
	for($i=0;$i<$len1;$i++){
		$zong1+=substr($string1,$i,1)*substr($quan1,$i,1);
	}
	for($i=0;$i<$len2;$i++){
		$zong2+=substr($total,$i,1)*substr($quan2,$i,1);
	}
	$zong3 = ($zong1+$zong2);
	$checkno = substr($zong3,strlen($zong3)-1);
	if($checkno>0)
		$checkno = 10-$checkno;
	$xn = $shopno.$sunday . $ordernum . $checkno;
	return $xn;
}
/**
大眾銀行虛擬賬號
**/
function DZXNAcc($ordernum,$shopno,$total){
	$shopnos = substr($shopno,0,4);
	if(strlen($ordernum)<8)
		$ordernum = str_repeat("0",8-strlen($ordernum)) . $ordernum;
	$ordernum = substr($shopno,4) . $ordernum;
	$len1 = strlen($ordernum);
	for($i=0;$i<$len1;$i++){
		$zong1+=substr($ordernum,$i,1)*($i+1);
	}
	
	$len2 = strlen($total);
	for($i=0;$i<$len2;$i++){
		$zong2+=substr($total,$i,1);
	}
	$zong = $shopnos + $zong1 + $zong2;
	$mod = substr($zong,strlen($zong)-1,1);
	if($mod>0)
		$mod = 10-$mod;
	return $shopnos . $ordernum . $mod;
}
/**
第一銀行虛擬賬號
**/
function DYXNAcc($ordernum,$shopno,$total){
	$sunday = 0;
	if(strlen($ordernum)<7)
		$ordernum = str_repeat("0",7-strlen($ordernum)) . $ordernum;

	$a = "37137137137137";
	$b = "37137137";
	$string = $shopno.$ordernum;
	if(strlen($string)<14)
		$string = str_repeat("0",14-strlen($string)) . $string;
	 $len1 = strlen($string);
	
	if(strlen($total)<8)
		$total = str_repeat("0",8-strlen($total)) . $total;
	$len2 = strlen($total);
	
	$zong1=0;
	$zong2=0;
	$string1 = $shopno.$ordernum;
	for($i=0;$i<$len1;$i++){
		$cheng1=substr($string,$i,1)*substr($a,$i,1);
		$zong1+=substr($cheng1,strlen($cheng1)-1,1);
	}
	
	for($i=0;$i<$len2;$i++){
		$cheng2=substr($total,$i,1)*substr($b,$i,1);
		$zong2+=substr($cheng2,strlen($cheng2)-1,1);
	}
	$checkno1 = substr($zong1,strlen($zong1)-1);
	$checkno2 = substr($zong2,strlen($zong2)-1);
	$zong3 = ($checkno1+$checkno2);
	$checkno0 = 10-substr($zong3,strlen($zong3)-1);
	if($checkno0==10)
		$checkno0=0;
	$checkno00 = 10-$checkno2;
	if($checkno00==10)
		$checkno00=0;
	$xn = $shopno . $ordernum . $checkno0 . $checkno00;
	return $xn;
}
function GTXNAcc($ordernum,$shopno,$total){
	//$shopno = "2172";
	if(strlen($ordernum)<5)
		$ordernum = str_repeat("0",5-strlen($ordernum)) . $ordernum;
	$code = $shopno . date("mdH",time()+60*60*24*3) . $ordernum;
	$quan = "456789123456789";
	$zong = 0;
	for($i=0;$i<15;$i++){
		$cheng=substr($code,$i,1)*substr($quan,$i,1);
		//echo substr($cheng,strlen($cheng)-1,1) . "$";
		$zong+=substr($cheng,strlen($cheng)-1,1);
	}
	//echo $zong;
	$ge = substr($zong,strlen($zong)-1,1);

	$quan1 = "87654321";
	if(strlen($total)<8)
		$total = str_repeat("0",8-strlen($total)) . $total;
	$zong1 = 0;
	for($i=0;$i<8;$i++){
		$cheng=substr($total,$i,1)*substr($quan1,$i,1);
		$zong1+=substr($cheng,strlen($cheng)-1,1);
	}
	//echo "|";
	 $ge1 = substr($zong1,strlen($zong1)-1,1);

	$check2 = $ge+$ge1;
	if($check2>=10)
		$check2 = substr($check2,strlen($check2)-1,1);
	if($check2 != 0)
		$check2 = 10-$check2;
	//echo "|";
	 $xn = $shopno . date("mdH",time()+60*60*24*3) . $ordernum . $check2;
	//exit;
	return $xn;
}
function getDays($month, $year)
{
    switch($month)
    {
        case 4:
        case 6:
        case 9:
        case 11:
            $days = 30;
            break;
        case 2:
            if ($year%4==0)
            {
                if ($year%100==0)
                {
                    $days = $year%400==0 ? 29 : 28;
                }
                else
                {
                    $days =29;
                }
            }
            else
            {
                $days = 28;
            }
            break;
        default:
            $days = 31;
            break;
    }
    return $days;
}
//$tpl->assign("ATM",     $ATM_STRING);   //ATM


$Query = $DB->query(" select ot.*,ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_id='".trim($orderid)."' ");
$Num       = $DB->num_rows($Query);

if ( $Num <= 0 ){
	$Reprot ="error";
}

$Result    = $DB->fetch_array($Query);


$Order_id  = $Result['order_id'];
$deliveryid  = $Rs['deliveryid'];
$Order_serial = $order_serial  = $Result['order_serial'];
$Cart_discount_totalPrices = $discount_totalPrices  = $Result['discount_totalPrices'];
$totalprice  = $Result['totalprice'];
$Cart_transmoney = $transport_price  = $Result['transport_price'];
$deliveryname  = $Result['deliveryname'];
$receiver_name = $Result['receiver_name'];
$receiver_address = $Result['receiver_address'];
$receiver_email = $Result['receiver_email'];
$receiver_post = $Result['receiver_post'];
$receiver_tele = $Result['receiver_tele'];
$receiver_mobile = $Result['receiver_mobile'];
$receiver_memo = $Result['receiver_memo'];
$storename = $Result['storename'];
$storeid = $Result['storeid'];
$user_id = $Result['user_id'];
$pay_serial = $Result['pay_serial'];
//生成付款編號
$pay_serial_new = substr($pay_serial,0,13) . (substr($pay_serial,13)+1);
$updatesql = "update  `{$INFO[DBPrefix]}order_table` set paymentname = '" . $paymentname . "',paymentid = '" . $pay_id . "',pay_state=0,pay_serial='" . $pay_serial_new  . "',paycontent='" . $pcontent . "' where order_id='".trim($orderid)."'";
$DB->query($updatesql);
$updatesql = "update  `{$INFO[DBPrefix]}order_detail` set detail_pay_state=0 where order_id='".trim($orderid)."'";
$DB->query($updatesql);

if($pay_id==96||$pay_id==97){
				$Psql = "select * from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.mid='" . $pay_id . "' order by p.mid";
				$PQuery    = $DB->query($Psql);
				$PRs=$DB->fetch_array($PQuery);
				if ($PRs['mid']>0){
					$Atm = GTXNAcc($Order_id,$PRs['shopcode'],intval($Cart_discount_totalPrices+$Cart_transmoney));
					//$tpl->assign("ATM",$Atm);
					$Update_sql = "update `{$INFO[DBPrefix]}order_table` set atm='" . $Atm . "' where order_id=".intval($Order_id);
					$DB->query($Update_sql);
				}
			}
			if($pay_id==166){
				$Psql = "select * from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.mid='" . $pay_id . "' order by p.mid";
				$PQuery    = $DB->query($Psql);
				$PRs=$DB->fetch_array($PQuery);
				if ($PRs['mid']>0){
					$InsertId_Sql = "select count(*) as todayno from `{$INFO[DBPrefix]}order_table`  where order_year='".date("Y",time())."' and order_month='".date("m",time())."' and order_day='".date("d",time())."' and order_id<='" . $Order_id . "' limit 0,1";
					$InsertId_Query = $DB->query($InsertId_Sql);
					$InsertId_Result= $DB->fetch_array($InsertId_Query);
					$todayno  = $InsertId_Result[todayno];
					$Atm = ZXAcc($todayno,$PRs['shopcode'],intval($Cart_discount_totalPrices+$Cart_transmoney));
					$tpl->assign("ATM",$Atm);
					 $Update_sql = "update `{$INFO[DBPrefix]}order_table` set atm='" . $Atm . "' where order_id=".intval($Order_id);
					$DB->query($Update_sql);
				}
			}
			if($pay_id==168){
				$Psql = "select * from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.mid='" . $pay_id . "' order by p.mid";
				$PQuery    = $DB->query($Psql);
				$PRs=$DB->fetch_array($PQuery);
				if ($PRs['mid']>0){
					$Atm = DZXNAcc($Order_id,$PRs['shopcode'],intval($Cart_discount_totalPrices-$Cart_buypoint+$Cart_transmoney));
					$tpl->assign("ATM",$Atm);
					$Update_sql = "update `{$INFO[DBPrefix]}order_table` set atm='" . $Atm . "' where order_id=".intval($Order_id);
					$DB->query($Update_sql);
				}
			}
			if($pay_id==171){
				$Psql = "select * from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.mid='" . $pay_id . "' order by p.mid";
				$PQuery    = $DB->query($Psql);
				$PRs=$DB->fetch_array($PQuery);
				if ($PRs['mid']>0){
					$InsertId_Sql = "select count(*) as todayno from `{$INFO[DBPrefix]}order_table`  where order_year='".date("Y",time())."' and order_month='".date("m",time())."' and order_day='".date("d",time())."' and order_id<='" . $Order_id . "' limit 0,1";
					$InsertId_Query = $DB->query($InsertId_Sql);
					$InsertId_Result= $DB->fetch_array($InsertId_Query);
					$todayno  = $InsertId_Result[todayno];
					$Atm = ZHXNAcc($todayno,$PRs['shopcode'],intval($Cart_discount_totalPrices+$Cart_transmoney));
					$tpl->assign("ATM",$Atm);
					$Update_sql = "update `{$INFO[DBPrefix]}order_table` set atm='" . $Atm . "' where order_id=".intval($Order_id);
					$DB->query($Update_sql);
				}
			}
			if($pay_id==174){
				$InsertId_Sql = "select count(*) as todayno from `{$INFO[DBPrefix]}order_table`  where order_year='".date("Y",time())."' and order_month='".date("m",time())."' and order_day='".date("d",time())."' and order_id<='" . $Order_id . "' limit 0,1";
				$InsertId_Query = $DB->query($InsertId_Sql);
				$InsertId_Result= $DB->fetch_array($InsertId_Query);
				$todayno  = $InsertId_Result[todayno];
				if(strlen($todayno)<3)
					$todayno = str_repeat("0",3-strlen($todayno)) . $todayno;
				$todayno = date("md",time()).$todayno;
				$Psql = "select * from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.mid='" . $pay_id . "' order by p.mid";
				$PQuery    = $DB->query($Psql);
				$PRs=$DB->fetch_array($PQuery);
				if ($PRs['mid']>0){
					$Atm = DYXNAcc($Order_id,$PRs['shopcode'],intval($Cart_discount_totalPrices-$Cart_buypoint+$Cart_transmoney));
					$tpl->assign("ATM",$Atm);
					$Update_sql = "update `{$INFO[DBPrefix]}order_table` set atm='" . $Atm . "' where order_id=".intval($Order_id);
					$DB->query($Update_sql);
				}
			}

//消費人信息
$Sql = "SELECT  * FROM `{$INFO[DBPrefix]}user`  where user_id='".intval($user_id)."' limit 0,1";
$Query  = $DB->query($Sql);
$Rs=$DB->fetch_array($Query);
$caddress = str_replace("請選擇","",$Rs[Country].$Rs[canton].$Rs[city]);//地址
$ctel = $Rs[tel].",".$Rs[other_tel];//电话
$ctrue_name = $Rs[true_name];

$Sql = "select * from `{$INFO[DBPrefix]}order_detail` where order_id='" . $Order_id . "'";
		$Query    = $DB->query($Sql);
		while($Rs    = $DB->fetch_array($Query)){
			$goods_bn .= $Rs['gid'] . "||";
			$goods_name .= $flag.$Rs['goodsname'];
			$goods_price .= $flag.$Rs['price'];
			$goods_count .= $flag.$Rs['count'];
			$product_number .= $flag.$Rs['bn'];
			$product_name .= $flag.$Rs['goodsname'];
			$quantity .= $flag.$Rs['count'];
			$unit .= $flag.$Rs['unit'];
			$product_price .= $flag.$Rs['price'];
			$product_color .= $flag.$Rs['good_color'];
			$product_size .= $flag.$Rs['good_size'];
			$product_standard .= $flag.$Rs['detail_name'];
			$flag = "#";
		}

/**
生成金流支付表單
**/
include_once "PayFunction.php";
$payFunction = new PayFunction;
if (intval($user_id)>0)
	$uno = intval($user_id);
else
	$uno = "NO" . rand(50000,9999999);
$pay_array = array(
				  "order_serial"    =>   $pay_serial_new,
				  "total"    =>   round($Cart_discount_totalPrices+$Cart_transmoney,0),
				  "receiver_name"   => $receiver_name,
				  "receiver_address"   => $receiver_address,
				  "receiver_tele"   => trim($receiver_tele),
				  "receiver_mobile"   => trim($receiver_mobile),
				  "receiver_email"   => trim($receiver_email),
				  "receiver_post"   => trim($receiver_post),
				  "order_id"   => $Order_id,
				  "userno"   => $uno,
				  "goods_name"   => $goods_name,
				  "goods_price"   => $goods_price,
				  "goods_count"   => $goods_count,
				  "date"   => $Time,
				  "atm"   => $Atm,
				  "st_code"   => $storeid,
				  );
if ($paytype==0){
	echo $payform = $payFunction->CreatePay(intval($_POST['pay_id']),$pay_array);
}//else{
//	if (intval($deliveryid)==13)
//		$payform = $payFunction->CreatePay(65,$pay_array);
//}
if (trim($payform) == ""){
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}sysmail` where id=1");
	$Result= $DB->fetch_array($Query);
	if ($Result['mail']!=""){
		$cmail = $Result['mail'];
	}

	$Array =  array("Order_id"=>$Order_id,"receiver_name"=>trim($truename),"truename"=>trim($truename),"orderid"=>trim($Order_serial));

	include_once "SMTP.Class.inc.php";
	include_once RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
	$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
	//echo $_POST['ifsendreceiver'];echo "d";
	if($_POST['ifsendreceiver']==1)
		$useremail .= "," . $_POST['receiver_email'];
	$SMTP->MailForsmartshop($useremail,$cmail,6,$Array);
	//print_r($Array);exit;
	//echo "aaaaaaaaa";
	//include_once "sms2.inc.php";
	//include_once "sendmsg.php";

	//$sendmsg = new SendMsg;
	//$sendmsg->send(trim($_POST['receiver_mobile']),$Array,6);

	echo "<script>location.href='showorder.php?orderno=" . $Order_serial . "';</script>";
	exit;
}
?>
