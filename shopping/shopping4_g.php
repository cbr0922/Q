<?php
error_reporting(7);
session_start();
include("../configs.inc.php");
require_once RootDocument.'/Classes/cart_group.class.php';
include("global.php");
include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";
require "check_member.php";
@header("Content-type: text/html; charset=utf-8");
$Time =  time();
/**
 cart_LOGO的尺寸
*/
$tpl->assign("cart_logo_width",  $INFO["cart_logo_width"]);
$tpl->assign("cart_logo_height", $INFO["cart_logo_height"]);
if(!isset($_SESSION['cart_group'])) {
	echo "<script>location.href='shopping_g.php';</script>";
	exit;
}
$cart =&$_SESSION['cart_group'];

if ($_POST['key']==""){
	$_POST['key']=$cart->get_key;
}
if ($_POST['receiver_email']==""){
	$FUNCTIONS->sorry_back("back","請填寫Email");
}
if ($_POST['receiver_tele']==""){
	$FUNCTIONS->sorry_back("back","請填寫電話");
}
if ($_POST['receiver_name']==""){
	$FUNCTIONS->sorry_back("back","請填寫收件人");
}
if ($_POST['receiver_mobile']==""){
	$FUNCTIONS->sorry_back("back","請填寫移動電話");
}

$cart->transname_area = $_POST['county'];
$cart->transname_area = $_POST['province'];

//購物車中商品
$items_array = $cart->getCartGroup($_POST['key']);
if (!is_array($items_array) || count($items_array)<=0){
	echo "<script>location.href='shopping_g.php';</script>";
	exit;
}
$Cart_item = array();
$i = 0;
$ds_array = array();
foreach($items_array as $k => $v){
	$Cart_item[$i] = $v;
	$Cart_item[$i]['price']       = $cart->setSaleoff($_POST['key'],$v['gkey']);
	if ($v['buytype']==0)
		$Cart_item[$i]['total'] = $v['count'] * $Cart_item[$i]['price'];
	if ($v['buytype']==1){
		$Cart_item[$i]['total'] = $v['count'] * $v['memberprice'];
		$Cart_item[$i]['totalpoint'] = $v['count'] * $Cart_item[$i]['grouppoint'];
	}
	$i++;
}
$ds_array = array_filter(array_unique($ds_array));
$cart->setTotal($_POST['key']);
$Cart_totalPrices = $cart->totalPrices;//商品網絡總計
$Cart_totalGrouppoint = $cart->totalGrouppoinit;
$Cart_sys_trans_type = $cart->sys_trans_type;//自定義配送方式，配送公式
$Cart_sys_trans = $cart->sys_trans;//配送信息
$Cart_transmoney = $cart->transmoney;//配送配用
$Cart_buypoint = $cart->totalBuypoint;

$Cart_nomal_trans_type = $cart->nomal_trans_type;//一般配送方式所選擇的配送方式
$Cart_special_trans_name = $cart->transname;
//echo $Cart_special_trans_name;
//echo $cart->transname_area;
//消費人信息
$Sql = "SELECT  * FROM `{$INFO[DBPrefix]}user`  where user_id='".intval($_SESSION['user_id'])."' limit 0,1";
$Query  = $DB->query($Sql);
$Rs=$DB->fetch_array($Query);
$caddress = str_replace("請選擇","",$Rs[Country].$Rs[canton].$Rs[city]);//地址
$ctel = $Rs[tel].",".$Rs[other_tel];//电话
$recommendno = $Rs['recommendno'];
$companyid = $Rs['companyid'];
/**
 * 这里是根据上边传输过来的资料来确定支付的名字以及是内部设定的付款方式还是线上金流
 */

//發票處理
if  (intval($INFO['Need_invoice'])==1){
	if($_POST['ifinvoice'] == 0 )
		$yesifinvoice = $Cart[Two_piao];
	elseif($_POST['ifinvoice'] == 1 )
		$yesifinvoice = $Cart[Two_piao];
	elseif($_POST['ifinvoice'] == 3 )
		$yesifinvoice = "捐贈華民國全球元母大慈協會";
	$ifinvoice = intval($_POST['ifinvoice']);
}else{
	$yesifinvoice = $Cart[DontNeed_piao]; //不需要发票的处理
	$ifinvoice = 2;
}
$tpl->assign("yesifinvoice",        $yesifinvoice);
$paySql = "select *,p.content as pcontent,p.month as pmonth from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pm on p.pid=pm.pid where p.ifopen=1 and p.mid='" . $_POST['pay_id'] . "' order by pm.paytype desc,p.mid";
$payQuery    = $DB->query($paySql);
$payNum      = $DB->num_rows($payQuery);
$payRs = $DB->fetch_array($payQuery);
$payment = $payRs['pcontent'];
$paytype = $payRs['paytype'];

/**
 * 这里是台湾的ATM 管理中的一个处理。来计算出ATM码！
 */
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
function HZXNAcc($ordernum,$shopno,$total){
	$sunday = 0;
	if(strlen($ordernum)<5)
		$ordernum = str_repeat("0",5-strlen($ordernum)) . $ordernum;
	for($i=1;$i<date("m",time());$i++){
		$sunday += getDays($i,date("Y",time()))	;
	}
	$sunday = date("y",time())%10 . ($sunday+date("d",time()));
	$a = "987654321987654321";
	$b = "21987654321";
	$len1 = strlen($shopno.$ordernum);
	$quan1 = substr($a,18-$len1);
	$len2 = strlen($total);
	$quan2 = substr($b,11-$len2);
	$zong1=0;
	$zong2=0;
	$string1 = $shopno.$ordernum;
	for($i=0;$i<$len1;$i++){
		$zong1+=substr($string1,$i,1)*substr($quan1,$i,1);
	}
	for($i=0;$i<$len2;$i++){
		$zong2+=substr($total,$i,1)*substr($quan2,$i,1);
	}
	$checkno = ($zong1+zong2)%11;
	if ($checkno==10)
		$checkno = 0;
	$xn = $shopno . $sunday . $ordernum . $checkno;
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
/**
金流
**/
$Sql_order   = "select order_serial as max_num from `{$INFO[DBPrefix]}order_table`  where order_year='".date("Y",time())."' and order_month='".date("m",time())."' and order_day='".date("d",time())."' and order_serial like 'TG%' order by order_serial desc limit 0,1";
	$Query_order = $DB->query($Sql_order);
	$Rs_order = $DB->fetch_array($Query_order);
	if ($Rs_order['max_num']!=""){
		//$Next_order_serial = $Rs_order['max_num']+1;
		$m = intval(substr($Rs_order['max_num'],10,3))+1;
		$Next_order_serial = date("Ymd",time()) . str_repeat("0",3-strlen($m)) . $m  . rand(0,9);
		$ATM_Num = substr($Next_order_serial,-9,strlen(trim($Next_order_serial)));
	}else{
		$Next_order_serial = date("Ymd",time())."001" . rand(0,9);
		$ATM_Num =  intval(date("ymd",time()))."001";
	}
	$Order_serial = "TG" . $Next_order_serial;
$paySql = "select *,p.content as pcontent from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pm on p.pid=pm.pid where p.ifopen=1 and p.mid='" . intval($_POST['pay_id']) . "' order by pm.paytype desc,p.mid";
$payQuery    = $DB->query($paySql);
$payNum      = $DB->num_rows($payQuery);
if ($payNum>0){
	$payRs = $DB->fetch_array($payQuery);
	$paymentname = $payRs['methodname'];	
	if ($payRs['payno']=="ATM"){
		$The_End_Num = getCheckNo(trim($INFO["ATM"]).$ATM_Num,$INFO["ATM_SECTION"]);
		$ATM_STRING = trim($INFO["ATM"]).$ATM_Num.$The_End_Num;	
	}else{
		$ATM_STRING = $Basic_Command['NullDate'];	
	}
}
if($paymentname=="" && $Cart_buypoint>=$Cart_discount_totalPrices){
	$paymentname = "帳上餘額";	
	$pay_state   =1 ; //已经交付
	$order_state =1 ; //已经确定
}else{
	$pay_state   =0 ; //已经交付
	$order_state =0 ; //已经确定	
}
$tpl->assign("paymentname",     $paymentname);   //ATM
$tpl->assign("ATM",     $ATM_STRING);   //ATM
//如果是非会员,将把非会员信息插入数据库
if ($_SESSION['user_id']==""){
	$Receiver_name = trim($_POST['receiver_name']).$Cart[NO_member]; //非会员
}else{
	$Receiver_name = trim($_POST['receiver_name']);
}
if ($_POST['Action']=="view" || $_POST['Action']==""){
	$_POST['Action']="view";
	$Array_HomeTimeType      = array();
	$Array_HomeTimeType      =  explode("(&)",$_POST[HomeTimeType]);
	$transtime_id            =  $Array_HomeTimeType[0];
	$transtime64encode_name  =  $Array_HomeTimeType[1];
	$transtime64decode_name  =  base64_decode($transtime64encode_name);
	
	$tpl->assign("yesifinvoice",        $yesifinvoice);
}
if($Cart_totalPrices-$Cart_buypoint-$Cart_transmoney==0){
	$paytype = 1;	
}
if ($_POST['Action']=="insert" || $paytype == 0){
	if ($paytype == 0)
		$_POST['receiver_memo'] .= $cart->saleoffinfo;	
	

	if ($_POST[HomeTimeType]!=""){
		$Array_HomeTimeType      =  explode("(&)",$_POST[HomeTimeType]);
		$transtime_id            =  $Array_HomeTimeType[0];
		$transtime64encode_name  =  $Array_HomeTimeType[1];
		$transtime64decode_name  =  base64_decode($Array_HomeTimeType[1]);
	}
	
	if ($_POST['addr']==""){
		$FUNCTIONS->sorry_back("back","請填寫收件地址");
	}
	//检查库存
	if(is_array($items_array)){
		foreach($items_array as $item){
			$storage = checkStorage(intval($item['gid']),$item['goodslist']);
			if ($storage<$item['count']){
				$alerts .= $item['goodsname'] . "庫存不足，最多只能買" . $storage . "件；";
			}
		}
		if ($alerts != ""){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('" . $alerts . "');location.href='shopping.php';</script>";exit;		
		}
	}
	
	//團購金判断
	$member_point = $FUNCTIONS->Grouppoint(intval($_SESSION['user_id']));
	if (intval($_SESSION['user_id'])>0 && intval($Cart_totalGrouppoint)>0){
		if ($member_point<intval($$Cart_totalGrouppoint)){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您最多可以使用" . $member_point . "點團購金');location.href='shopping_g.php';</script>";exit;	
		}
	}else if (intval($_SESSION['user_id'])==0 && intval($Cart_totalGrouppoint)>0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您最多可以使用0點團購金');location.href='shoppin_g.php';</script>";exit;	
	}
	$group_array = array();
	$i = 0 ;
	foreach($items_array as $k => $v){
		if($v['subject']>0){
			$group_array[$v['subject']] = intval($group_array[$v['subject']]) + $v['price']*$v['count'];	
		}
		$i++;
	}
	foreach($group_array as $k=>$v){
		$Sql_sub   = " select * from `{$INFO[DBPrefix]}groupsubject` where gsid='" . $k . "'";
		$Query_sub = $DB->query($Sql_sub);	
		if($Rs_sub['min_money']>0){
			$total_Grouppoing  += intval($v/$Rs_sub['min_money'])*$Rs_sub['grouppoint'];
		}
	}
	$db_string = $DB->compile_db_insert_string( array (
	'user_id'                      => intval($_SESSION['user_id']),
	'receiver_name'                => $Receiver_name,
	'pay_state'                    => $pay_state,
	'onlinepay'                    => intval($PayPay),
	'order_state'                  => intval($order_state),
	'receiver_address'             => $cart->transname_area.$cart->transname_area2.$_POST['city'].trim($_POST['addr']),
	'receiver_email'               => trim($_POST['receiver_email']),
	'receiver_post'                => trim($_POST['othercity']), //  receiver_post
	'receiver_tele'                => trim($_POST['receiver_tele']),
	'receiver_mobile'              => trim($_POST['receiver_mobile']),
	'receiver_memo'                => trim($_POST['receiver_memo']),
	'invoiceform'                  => trim($_POST['invoiceform']),
	'invoice_num'                  => intval($_POST['invoice_num']),
	'ifinvoice'                    => $ifinvoice,
	'totalprice'                   => intval($Cart_totalPrices),//$total_member_price,
	'paymentid'                    => intval($_POST['pay_id']), //$KEY_pay_id,
	'deliveryid'                   => intval($cart->transname_id),
	'paymentname'                  => trim($paymentname),
	'paycontent'                   => trim($payment),
	'transport_content'            => trim($transport_content),
	'deliveryname'                 => trim($Cart_special_trans_name),
	'transtime_id'                 => intval($transtime_id),
	'transport_price'              => $Cart_transmoney,
	'createtime'                   => $Time,
	'order_year'                   => date("Y",time()),
	'order_month'                  => date("m",time()),
	'order_day'                    => date("d",time()),
	'order_serial'                 => $Order_serial,
	'atm'	                       => trim($_POST['ATM']),//$ATM_STRING,
	'discount_totalPrices'                     => intval($Cart_totalPrices-$Cart_buypoint),
	'totalGrouppoint'                     => intval($Cart_totalGrouppoint),
	//'saler'              => trim($_SESSION['saler']),
	//'rid'              => trim($_COOKIE['RID'])==""?$companyid:trim($_COOKIE['RID']),
	//'recommendno'              => trim($_COOKIE['u_recommendno'])==""?$recommendno:trim($_COOKIE['u_recommendno']),
	'transport_state'                =>0,
	'ifgroup'                =>1,
	'giveGroup'                =>$total_Grouppoing,
	'buyPoint'                =>intval($Cart_buypoint),
	)      );
	$LOCK = $DB->query("LOCK TABLES `{$INFO[DBPrefix]}order_table` WRITE");
	$Sql="INSERT INTO `{$INFO[DBPrefix]}order_table` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result = $DB->query($Sql);
	$UNLOCK = $DB->query("UNLOCK TABLES");
	if ($Result){

		$InsertId_Sql = "select order_id from `{$INFO[DBPrefix]}order_table`  where createtime='".$Time."' and order_serial='".$Order_serial."' limit 0,1";
		$InsertId_Query = $DB->query($InsertId_Sql);
		$InsertId_Result= $DB->fetch_array($InsertId_Query);
		$Insert_id  = $InsertId_Result[order_id];
		foreach($items_array as $item){
			$DB->query("LOCK TABLES `{$INFO[DBPrefix]}order_group` WRITE");
			$Sql_group = "insert into `{$INFO[DBPrefix]}order_group` (gdid,order_id,groupname,count,groupprice,grouppoint,groupbn,goodslist,subject,subjectcontent,cost,provider_id) values ('".intval($item['gid'])."','".$Insert_id."','".$item['goodsname']."','".intval($item['count'])."','".($item['buytype']==0?intval($item['price']):intval($item['memberprice']))."','".($item['buytype']==1?intval($item['grouppoint']):0)."','".$item['bn']."','".$item['goodslist']."','".intval($item['subject'])."','".$item['subjectcontent']."','".$item['cost']."','".$item['provider_id']."')";
			$Result_group = $DB->query($Sql_group);
			$DB->query("UNLOCK TABLES");
			$group_id_Sql = "select order_group_id from `{$INFO[DBPrefix]}order_group`  where order_id='".$Insert_id."' and gdid='".intval($item['gid'])."' limit 0,1";
			$group_id_Query = $DB->query($group_id_Sql);
			$group_id_Result= $DB->fetch_array($group_id_Query);
			$order_group_id  = $group_id_Result['order_group_id'];
			
			$goods_d_array  = explode(",",$item['goodslist']);
			$i = 0;
			foreach($goods_d_array as $k=>$v){
				$Query_d = $DB->query("select * from `{$INFO[DBPrefix]}goods` where bn=".trim($v)."  limit 0,1");
				$Num_d   = $DB->num_rows($Query_d);
				if ($Num_d>0){
					$Result_d= $DB->fetch_array($Query_d);
					$DB->query("LOCK TABLES `{$INFO[DBPrefix]}order_detail` WRITE");
					$Sql_Ok = "insert into `{$INFO[DBPrefix]}order_detail` (gid,goodsname,unit,goodscount,market_price,price,order_id,provider_id,bn,iftogether,order_group_id) values('".$Result_d['gid']."','".$Result_d['goodsname']."','".$Result_d['unit']."','".$item['count']."','".intval($Result_d['price'])."','".$Result_d['pricedesc']."','".$Insert_id."','".$Result_d['provider_id']."','" . $Result_d['bn'] . "','".$Result_d['iftogether']."','" . $order_group_id . "')";
					$Result_ok = $DB->query($Sql_Ok);
					$DB->query("UNLOCK TABLES");
					$FUNCTIONS->setStorage(intval($item['count']),"1",intval($Result_d['gid']),0,'','',"",1,$Insert_id);
				}
			}
			/*
			$goods_bn .= $item['gid'] . "||";
			$goods_name .= $item['goodsname'] . "||";
			$goods_price .= $MemberPice . "||";
			$goods_count .= $item['count'] . "||";
			$oe .= $item['oeid'] . "||";
			*/
			//$Result_ok = $DB->query("insert into `{$INFO[DBPrefix]}order_detail` (gid,goodsname,unit,good_color,good_size,goodscount,market_price,price,order_id,provider_id) values('".$item['gid']."','".$item['goodsname']."','".$item['unit']."','".$item['good_color']."','".$item['good_size']."','".$item['count']."','".$item['temp_price']."','".$MemberPice."','".$Insert_id."','".$item['provider_id']."')");
		}
		
		if ($Cart_totalGrouppoint > 0){
			$FUNCTIONS->BuyGrouppoint(intval($_SESSION['user_id']),$Cart_totalGrouppoint,"訂單" . $Order_serial . "使用團購點",$Insert_id);
		}
		if ($Cart_buypoint > 0){
			$FUNCTIONS->AddBuypoint(intval($_SESSION['user_id']),intval($Cart_buypoint),1,"訂單" . $Order_serial . "使用帳上餘額",$Insert_id,0,0,1);	
		}
		
			//虛擬帳號
			if($pay_id==53){
				$Psql = "select * from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.mid='" . $pay_id . "' order by p.mid";
				$PQuery    = $DB->query($Psql);
				$PRs=$DB->fetch_array($PQuery);
				if ($PRs['mid']>0){
					$Atm = HZXNAcc($Insert_id,$PRs['shopcode'],intval($Cart_discount_totalPrices));
					$tpl->assign("ATM",$Atm);
					$Update_sql = "update `{$INFO[DBPrefix]}order_table` set atm='" . $Atm . "' where order_id=".intval($Insert_id);
					$DB->query($Update_sql);
				}
			}
			
			//require "oeya_cps.php";
			//oeyaapi(intval($_SESSION['user_id']),$Receiver_name,$Order_serial,$goods_bn,$goods_name,$goods_price,$goods_count,$Cart_discount_totalPrices,$oe,trim($_POST['receiver_email']),trim($_POST['receiver_tele']),$other);
			
			$cart->clearGoods(($_POST['key']));
		
	}
}else{
	$_POST['receiver_memo'] .= $cart->saleoffinfo;	
}
function checkStorage($gid,$goodslist){
	global $DB,$INFO;
	if ($gid>0){
		//庫存
		$goods_d_array  = explode(",",$goodslist);
		$storage_array = array();
		$i = 0;
		foreach($goods_d_array as $kk=>$v){
			$Query_d = $DB->query("select * from `{$INFO[DBPrefix]}goods` where bn=".trim($v)."  limit 0,1");
			$Num_d   = $DB->num_rows($Query_d);
			if ($Num_d>0){
				$Result_d= $DB->fetch_array($Query_d);
				if ($Result_d['storage']>0){
					$storage_array[$i] = $Result_d['storage'];
				}else{
					return 0;
				}
			}else{
				return 0;
			}
			$i++;
		}
		if (is_array($storage_array)){
			sort($storage_array,SORT_NUMERIC);
			return $storage_array[0];
		}
	}
}
/**
生成金流支付表單
**/
include_once "PayFunction.php";
if ($Cart_buypoint < $Cart_totalPrices+$Cart_transmoney){
	if (intval($_SESSION['user_id'])>0)
		$uno = intval($_SESSION['user_id']);
	else
		$uno = "NO" . rand(50000,9999999);
		
	$pay_array = array(
					  "order_serial"    =>   $Order_serial,
					  "total"    =>   round($Cart_totalPrices+$Cart_transmoney-$Cart_buypoint,0),
					  "receiver_name"   => $Receiver_name,
					  "receiver_address"   => $cart->transname_area.$cart->transname_area2.$_POST['city'].trim($_POST['addr']),
					  "receiver_tele"   => trim($_POST['receiver_tele']),
					  "receiver_mobile"   => trim($_POST['receiver_mobile']),
					  "receiver_email"   => trim($_POST['receiver_email']),
					  "receiver_post"   => trim($_POST['othercity']),
					  "order_id"   => $Insert_id,
					  "userno"   => $uno,
					  );
	$payFunction = new PayFunction;
	if ($paytype==0){
		$payform = $payFunction->CreatePay(intval($_POST['pay_id']),$pay_array);
	}else{
		if ($cart->transname_id==13  )
			$payform = $payFunction->CreatePay(65,$pay_array);	
	}
	$tpl->assign("payform",                   $payform);
}
/**
  发送产品购买定单邮件
 */
if ($Result_ok){
	$token="d96ef98d76a64c2c9d7e5a0d819efacd";
	$fb_url = "http://os.iqbi.com/utvfb/method.aspx";
	if ($MallgicOrderId!="")
		$result_fb = file_get_contents($fb_url . "?op=updateorderstatus&moid=" . $MallgicOrderId . "&utvorderid=" . $Order_serial . "&orderstatus=0&token=" . $token);
				
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}sysmail` where id=1");
	$Result= $DB->fetch_array($Query);
	if ($Result['mail']!=""){
		$cmail = $Result['mail'];	
	}
	$_SESSION['ticket'] = "";
	$Array =  array("Order_id"=>$Insert_id,"ATM"=>trim($ATM_STRING),"pay_deliver"=>trim($deliveryname),"pay_name"=>trim($paymentname),"pay_content"=>trim($KEY_pay_content),"receiver_name"=>trim($Receiver_name),"truename"=>trim($truename),"orderid"=>trim($Order_serial),"orderamount"=>($Cart_totalPrices+$Cart_transmoney)."[折價後價格:" . ($Cart_discount_totalPrices+$Cart_transmoney) . "]","receiver_address"=>$cart->transname_area.$cart->transname_area2.$_POST['city'].trim($_POST['addr']));
	//print_r($Array);exit;
	include "SMTP.Class.inc.php";
	include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
	$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
	$SMTP->MailForsmartshop($_POST['receiver_email'],$cmail,6,$Array);
	//echo "aaaaaaaaa";
	include_once "sms2.inc.php";
	include_once "sendmsg.php";
			
	$sendmsg = new SendMsg;
	$sendmsg->send(trim($_POST['receiver_mobile']),$Array,6);
	$_SESSION['shopping_ip'] = $FUNCTIONS->getip();
	$_SESSION['shopping_orderserial'] = $Order_serial;
	$_SESSION['shopping_orderid'] = $Insert_id;
}


/**
 * 以下是宅配時間资料推到前台的变量
 */
$tpl->assign("Action",                   trim($_POST['Action']));
$tpl->assign("key",                   trim($_POST['key']));
$tpl->assign("pay_id",                   trim($_POST['pay_id']));
$tpl->assign("transport_id",             intval($cart->transname_id));
$tpl->assign("province",                 trim($_POST['province']));
$tpl->assign("city",                     trim($_POST['city']));
$tpl->assign("Country",                     trim($_POST['country']));
$tpl->assign("addr",                     trim($_POST['addr']));
$tpl->assign("transtime64decode_name",  $transtime64decode_name);
$tpl->assign("transtime64encode_name",  $transtime64encode_name);
$tpl->assign("transtime_id",            $transtime_id);

$tpl->assign("Order_serial",        $Order_serial); //订单编号
$tpl->assign("Need_invoice",          intval($INFO['Need_invoice']));
$tpl->assign("receiver_email",        $_POST['receiver_email']);
$tpl->assign("receiver_tele",         $_POST['receiver_tele']);
$tpl->assign("othercity",             $_POST['othercity']);   //  receiver_post
$tpl->assign("receiver_address",      str_replace("請選擇","",$cart->transname_area.$cart->transname_area2.$_POST['city'].trim($_POST['addr'])));
$tpl->assign("receiver_name",         $_POST['receiver_name']);
$tpl->assign("receiver_mobile",       $_POST['receiver_mobile']);
$tpl->assign("receiver_post",         $_POST['othercity']);
$tpl->assign("receiver_memo",         $_POST['receiver_memo']);
$tpl->assign("email",         $_POST['email']);
$tpl->assign("true_name",       $_POST['true_name']);
$tpl->assign("post",         $_POST['othercity2']);
$tpl->assign("county2",         $_POST['county2']);
$tpl->assign("province2",         $_POST['province2']);
$tpl->assign("city2",         $_POST['city2']);
$tpl->assign("addr2",         $_POST['addr2']);
$tpl->assign("tel",         $_POST['tel']);
$tpl->assign("other_tel",         $_POST['other_tel']);
$tpl->assign("paymentname",             $paymentname);   //付款方式
$tpl->assign("invoice_num",      trim($_POST['invoice_num']));  //统一编号
$tpl->assign("ifinvoice",        intval($_POST['ifinvoice']));   //发票类型
$tpl->assign("invoiceform",      trim($_POST['invoiceform']));   //发票抬头
$tpl->assign("PayPay",                 $PayPay);
$tpl->assign("prefer_paymethod",       $prefer_paymethod);
$tpl->assign("payment",                $payment);
$tpl->assign("paytype",          $paytype);
$tpl->assign("Cart_item",          $Cart_item);
$tpl->assign("transport_content",          $cart->transname_content);
$tpl->assign("Cart_totalPrices",   $Cart_totalPrices);
$tpl->assign("Cart_sys_trans_type",         $Cart_sys_trans_type);
$tpl->assign("Cart_sys_trans",         $Cart_sys_trans);
$tpl->assign("Cart_discount_totalPrices", $Cart_discount_totalPrices);
$tpl->assign("Cart_special_trans_name", $Cart_special_trans_name);
$tpl->assign("Cart_special_trans_type", $Cart_special_trans_type);
$tpl->assign("Cart_transmoney", $Cart_transmoney);
$tpl->assign("Cart_nomal_trans_type", $Cart_nomal_trans_type);
$tpl->assign("SendType",  $SendType);
$tpl->assign("Gpicpath",         $INFO['good_pic_path']);
$tpl->assign("IfNeed_invoice",       intval($INFO['Need_invoice']));  //系统中指定的是否需要发
$tpl->assign("Cart_totalGrouppoint",   $Cart_totalGrouppoint);
$tpl->assign("Cart_buypoint",   intval($Cart_buypoint));
$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Basic_Command);

$tpl->display("shopping4_g.html");
?>
