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

$updatesql = "update  `{$INFO[DBPrefix]}order_table` set paymentname = '" . $paymentname . "' where order_id='".trim($orderid)."'";
$DB->query($updatesql);

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
$user_id = $Result['user_id'];

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

//消費人信息
$Sql = "SELECT  * FROM `{$INFO[DBPrefix]}user`  where user_id='".intval($user_id)."' limit 0,1";
$Query  = $DB->query($Sql);
$Rs=$DB->fetch_array($Query);
$caddress = str_replace("請選擇","",$Rs[Country].$Rs[canton].$Rs[city]);//地址
$ctel = $Rs[tel].",".$Rs[other_tel];//电话
$ctrue_name = $Rs[true_name];

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
				  "order_serial"    =>   $Order_serial,
				  "total"    =>   round($Cart_discount_totalPrices+$Cart_transmoney,0),
				  "receiver_name"   => $receiver_name,
				  "receiver_address"   => $receiver_address,
				  "receiver_tele"   => trim($receiver_tele),
				  "receiver_mobile"   => trim($receiver_mobile),
				  "receiver_email"   => trim($receiver_email),
				  "receiver_post"   => trim($receiver_post),
				  "order_id"   => $Order_id,
				  "userno"   => $uno,
				  "atm"   => $Atm,
				  );
if ($paytype==0){
	echo $payform = $payFunction->CreatePay(intval($_POST['pay_id']),$pay_array);
}//else{
//	if (intval($deliveryid)==13)
//		$payform = $payFunction->CreatePay(65,$pay_array);	
//}
if (trim($payform) == ""){
?>
<script language="javascript">
   location.href = "showorder.php?orderno=<?php echo $Order_serial?>";
</script>
<?php	
}
?>
