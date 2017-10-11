<?php
error_reporting(7);
require_once('../Classes/cart.class.php' );
session_start();
include("../configs.inc.php");
include("global.php");
include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";
require "check_member.php";
include_once 'crypt.class.php';
@header("Content-type: text/html; charset=utf-8");
$Time =  time();
/**
 cart_LOGO的尺寸
*/
$tpl->assign("cart_logo_width",  $INFO["cart_logo_width"]);
$tpl->assign("cart_logo_height", $INFO["cart_logo_height"]);
if(!isset($_SESSION['cart'])) {
	echo "<script>location.href='shopping.php';</script>";
	exit;
}
$cart =&$_SESSION['cart'];
//print_r($_POST);exit;
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
	echo "<script>location.href='shopping.php';</script>";
	exit;
}
$Cart_item = array();
$i = 0;
$ds_array = array();
if (substr($_POST['key'],0,2)=="FB"){		
	$MallgicOrderId = substr($_POST['key'],3,strlen($_POST['key'])-3);
}
foreach($items_array as $k => $v){
	if ($v['ifds']==1)
		$ds_array[$i] = $v['dsid'];
	$Cart_item[$i] = $v;
	//$Cart_item[$i]['memberorprice'] = $_SESSION['cartc'][$_POST['key']][$v['gkey']] == 2?2:1;
	//$cart->changeItems($_POST['key'],$v['gkey'],"memberorprice",$_SESSION['cartc'][$_POST['key']][$v['gkey']]);
	if ($v['ifbonus']==0 && $v['ifpresent']==0 && $v['Js_price']==0  && $v['ifchange']==0 && $v['nosaleoff']==0 && $v['ifadd']==0 && $v['ifds']==0 && $v['rebate']==0){
		//$MemberPiceReturn = $FUNCTIONS->MemberLevelPrice($_SESSION['user_level'],$v['gid'],$v['detail_id']);
		$Cart_item[$i]['price']       = $cart->setPrice($_POST['key'],$k);
		$cart->changeItems($_POST['key'],$v['gkey'],"price",intval($Cart_item[$i]['price']));
	}
	if ($Cart_item[$i]['memberorprice'] == 2){
		$Cart_item[$i]['total'] = $v['count'] * $v['memberprice'];
		$Cart_item[$i]['totalcombi'] = $v['count'] * $v['combipoint'];
	}else{
	//	$Cart_item[$i]['price']       = $cart->setSaleoff($_POST['key'],$v['gkey']);
		$Cart_item[$i]['total'] = $v['count'] * $Cart_item[$i]['price'];
	}
	$provider_id = $v['provider_id'];
	$i++;
}
$ds_array = array_filter(array_unique($ds_array));
$cart->setTotal($_POST['key']);
$totalbonuspoint = $cart->setGroupbonuspoint($_POST['key']);
$cart->setinvoice($INFO['Need_invoice'],$INFO['invoice']);//發票稅率
$Cart_totalPrices = $cart->totalPrices;//商品網絡總計
$cart->getdiscount($_POST['key']);
$Cart_discount_totalPrices = $cart->discount_totalPrices;//優惠金額
$Cart_combipoint = $cart->combipoint;//優惠金額
$Cart_tickets = $cart->tickets;//優惠卷
$Cart_bonus = $cart->bonus;//紅利
$Cart_sys_trans_type = $cart->sys_trans_type;//自定義配送方式，配送公式
$Cart_sys_trans = $cart->sys_trans;//配送信息
$Cart_transmoney = $cart->transmoney;//配送配用
$Cart_buypoint = $cart->totalBuypoint;

$Cart_special_trans_type = $cart->special_trans_type;//是否是特殊配送方式
$Cart_nomal_trans_type = $cart->nomal_trans_type;//一般配送方式所選擇的配送方式
$Cart_special_trans_name = $cart->transname;
//echo $Cart_special_trans_name;

//消費人信息
$Sql = "SELECT  * FROM `{$INFO[DBPrefix]}user`  where user_id='".intval($_SESSION['user_id'])."' limit 0,1";
$Query  = $DB->query($Sql);
$Rs=$DB->fetch_array($Query);
$caddress = str_replace("請選擇","",$Rs[Country].$Rs[canton].$Rs[city]);//地址
$ctel = $Rs[tel].",".$Rs['other_tel'];//电话
$recommendno = $Rs['recommendno'];
$companyid = $Rs['companyid'];
$truename = $Rs['true_name'];
$useremail = $Rs['email'];
/**
 * 这里是根据上边传输过来的资料来确定支付的名字以及是内部设定的付款方式还是线上金流
 */

//發票處理
if  (intval($INFO['Need_invoice'])==1){
	if($_POST['ifinvoice'] == 0 )
		$yesifinvoice = $Cart[Two_piao];
	elseif($_POST['ifinvoice'] == 1 )
		$yesifinvoice = $Cart[Three_piao];
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
//虛擬帳號
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
/**
金流
**/

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
if($Cart_buypoint>=$Cart_discount_totalPrices){
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
//if ($_POST['Action']=="insert" || $paytype == 0){
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
			$storage = checkStorage(intval($item['gid']),intval($item['detail_id']),$item['good_color'],$item['good_size']);
			if ($storage<$item['count'] && $item['ifalarm']==1){
				$alerts .= $item['goodsname'] . "庫存不足，最多只能買" . $storage . "件；";
			}
		}
		if ($alerts != ""){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('" . $alerts . "');location.href='shopping.php';</script>";exit;		
		}
	}
	//红利判断
	$member_point = $FUNCTIONS->Userpoint(intval($_SESSION['user_id']),1);
	if (intval($_SESSION['user_id'])>0 && intval($Cart_bonus['point']+$totalbonuspoint+$Cart_combipoint)>0){
		if ($member_point<intval($Cart_bonus['point']+$totalbonuspoint)){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您最多可以使用" . $member_point . "點積分');location.href='shopping2.php?key=" . $_POST['key'] . "';</script>";exit;	
		}
	}else if (intval($_SESSION['user_id'])==0 && intval($Cart_bonus['point'])>0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您最多可以使用0點積分');location.href='shopping2.php?key=" . $_POST['key'] . "';</script>";exit;	
	}
	//計算促銷活動需要贈送紅利數
	if(count($ds_array)>0){
		$ds_sql = " and dsid in (" . implode(",",$ds_array) . ")";
		$d_sql = "select sum(point) as totalpoint  from  `{$INFO[DBPrefix]}discountsubject`  where  subject_open=1 " . $ds_sql . "";
		$Query = $DB->query($d_sql);
		$Rs    = $DB->fetch_array($Query);
		$total_point = $Rs['totalpoint'];
	}else{
		$total_point = 0;
	}
	
	
	$key_value = explode("P",$_POST['key']);
	$key_value_shop = explode("S",$_POST['key']);
	if($key_value[1]==0 && $key_value_shop[1]==0)
		$iftogether = 1;
	$db_string = $DB->compile_db_insert_string( array (
	'user_id'                      => intval($_SESSION['user_id']),
	'receiver_name'                => $Receiver_name,
	'pay_state'                    => $pay_state,
	'onlinepay'                    => intval($PayPay),
	'order_state'                  => intval($order_state),
	'receiver_address'             => $cart->transname_area.$cart->transname_area2.$_POST['city'].trim($_POST['addr']),
	'receiver_email'               => trim($_POST['receiver_email']),
	'receiver_post'                => trim($_POST['othercity']), //  receiver_post
	'receiver_tele'                => MD5Crypt::Encrypt (trim($_POST['receiver_tele']), $INFO['tcrypt']),
	'receiver_mobile'              => MD5Crypt::Encrypt (trim($_POST['receiver_mobile']), $INFO['mcrypt']),
	'receiver_memo'                => trim($_POST['receiver_memo']),
	'invoiceform'                  => trim($_POST['invoiceform']),
	'invoice_num'                  => intval($_POST['invoice_num']),
	'provider_id'                  => intval($key_value[1]),
	'iftogether'                  => $iftogether,
	'ifinvoice'                    => $ifinvoice,
	'totalprice'                   => $Cart_totalPrices,//$total_member_price,
	'paymentid'                    => intval($_POST['pay_id']), //$KEY_pay_id,
	'deliveryid'                   => intval($cart->transname_id),
	'paymentname'                  => trim($paymentname),
	'paycontent'                   => trim($payment),
	'transport_content'            => trim($cart->transname_content),
	'deliveryname'                 => trim($Cart_special_trans_name),
	'transtime_id'                 => intval($transtime_id),
	'transport_price'              => trim($Cart_transmoney),
	'createtime'                   => $Time,
	'order_year'                   => date("Y",time()),
	'order_month'                  => date("m",time()),
	'order_day'                    => date("d",time()),
	'atm'	                       => trim($_POST['ATM']),//$ATM_STRING,
	'ticketid'                     => intval($Cart_tickets['id']),
	'ticketmoney'                     => intval($Cart_tickets['money']),
	'ticketcode'                     => $Cart_tickets['ticketcode'],
	'bonuspoint'                     => intval($Cart_bonus['point'])+$Cart_combipoint,
	'ticket_discount_money'                     => intval($Cart_tickets['discount_money']),
	'discount_totalPrices'                     => intval($Cart_discount_totalPrices),
	'totalbonuspoint'                     => intval($totalbonuspoint),
	'saler'              => trim($_SESSION['saler']),
	'rid'              => trim($_COOKIE['RID'])==""?$companyid:trim($_COOKIE['RID']),
	'recommendno'              => trim($_COOKIE['u_recommendno'])==""?$recommendno:trim($_COOKIE['u_recommendno']),
	'transport_state'                =>0,
	'shopid'                =>$key_value_shop[1],
	'sendpoint'                 => intval($total_point),
	'MallgicOrderId'                 => trim($MallgicOrderId),
	'store_id'                 => intval($cart->store[$_POST['key']]['id']),
	'buyPoint'                =>intval($Cart_buypoint),
	'cvsname'              => trim($cart->cvsname),
	'cvsnum'              => trim($cart->cvsnum),
	)      );
	$Order_serial = setOrder();
	//$LOCK = $DB->query("LOCK TABLES `{$INFO[DBPrefix]}order_table` WRITE");
	//$Sql="INSERT INTO `{$INFO[DBPrefix]}order_table` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	//$Result = $DB->query($Sql);
	//$UNLOCK = $DB->query("UNLOCK TABLES");
	if ($Cart_tickets['id'] > 0 ){
		if ($Cart_tickets['type'] == 0 ){
			$sql_id = "select id from `{$INFO[DBPrefix]}userticket` where ticketid='" . intval($Cart_tickets['id']) . "' and userid='" . intval($_SESSION['user_id']) . "' and count>0 limit 0,1";
			$Id_Query = $DB->query($sql_id);
			$Id_Result= $DB->fetch_array($Id_Query);
			$recordid  = $Id_Result[id];
			$Sql = "update `{$INFO[DBPrefix]}userticket` set count=count-1 where id='" . $recordid . "'";
			$Result = $DB->query($Sql);
		}else{
			$sql = "update `{$INFO[DBPrefix]}ticketcode` set usetime='" . time() . "',userid='" . intval($_SESSION['user_id']) . "' where ticketid='" . intval($Cart_tickets['id']) . "' and ticketcode='" . $Cart_tickets['ticketcode'] . "'";
			$Result_t = $DB->query($sql);
		}
		
		$Sql = "insert into `{$INFO[DBPrefix]}use_ticket` (ticketid,userid,money,ordercode,usetime,moneytype,ticketcode) values ('" . intval($Cart_tickets['id']) . "','" . intval($_SESSION['user_id']) . "','" . intval($Cart_tickets['money']) . "','" . $Order_serial . "','" . time() . "','" . intval($Cart_tickets['moneytype']) . "','" . $Cart_tickets['ticketcode'] . "')";
		$Result_t = $DB->query($Sql);
	}
	/*
	if ($Cart_bonus['point']> 0){
		$Sql = "insert into `{$INFO[DBPrefix]}bonus_record` (ordercode,userid,point,rebatetime) values ('" . $Order_serial . "','" . intval($_SESSION['user_id']) . "','" . intval($Cart_bonus['point']) . "','" . time() . "')";
		$Result_t = $DB->query($Sql);
	}
	*/
	if ($Order_serial){

		$InsertId_Sql = "select order_id from `{$INFO[DBPrefix]}order_table`  where createtime='".$Time."' and order_serial='".$Order_serial."' limit 0,1";
		$InsertId_Query = $DB->query($InsertId_Sql);
		$InsertId_Result= $DB->fetch_array($InsertId_Query);
		$Insert_id  = $InsertId_Result[order_id];
		foreach($items_array as $item){
			$DB->query("LOCK TABLES `{$INFO[DBPrefix]}order_detail` WRITE");
			if($item['ifpresent'] == 1 ){
				$item['count'] = 1;
			}
			if ($item['ifxygoods'] == 1){
				if(is_array($item['xygoods'])){
					$xygoods = implode(",",$item['xygoods']);
				}
				if(is_array($item['xygoods_color'])){
					$xygoods_color = implode(",",$item['xygoods_color']);
				}
				if(is_array($item['xygoods_size'])){
					$xygoods_size = implode(",",$item['xygoods_size']);
				}
			}
			if ($item['Js_price']==0 && $item['iftimesale']==0 && $item['ifchange']==0 && $item['ifsale']==0 && $item['ifadd']==0&& $item['dsid']==0&& $item['ifmore']==0 ){
				$cost = $item['cost'];
				$salecontent = "";
			}else{
				$cost = $item['salecost'];
				$salecontent = "促銷成本價";
			}
			$Sql_Ok = "insert into `{$INFO[DBPrefix]}order_detail` (gid,goodsname,unit,good_color,good_size,goodscount,market_price,price,order_id,point,provider_id,month,detail_id,detail_bn,detail_name,detail_des,xygoods,ifxygoods,ifchange,xygoods_color,xygoods_size,xygoods_des,oeid,memberorprice,memberprice,combipoint,bonuspoint,ifbonus,bn,iftogether,cost,salecontent,detail_pay_state,detail_order_state) values('".$item['gid']."','".$item['goodsname'].$item['rebateinfo']."','".$item['unit']."','".$item['good_color']."','".$item['good_size']."','".$item['count']."','".intval($item['temp_price'])."','".$item['price']."','".$Insert_id."','".$item['point']."','".$item['provider_id']."','".intval($item['month'])."','".intval($item['detail_id'])."','".$item['detail_bn']."','".$item['detail_name']."','".$item['detail_des']."','" . $xygoods . "','" . intval($item['ifxygoods']) . "','" . intval($item['ifchange']) . "','" . $xygoods_color . "','" . $xygoods_size . "','" . $item['xygoods_des'] . "','" . $item['oeid'] . "','" . $item['memberorprice'] . "','" . $item['memberprice'] . "','" . $item['combipoint'] . "','" . $item['bonuspoint'] . "','" . $item['ifbonus'] . "','" . $item['bn'] . "','".$item['iftogether']."','" . $cost . "','" . $salecontent . "','" . intval($pay_state) . "','" . intval($order_state) . "')";
			$Result_ok = $DB->query($Sql_Ok);
			$DB->query("UNLOCK TABLES");
			
			$goods_bn .= $item['gid'] . "||";
			$goods_name .= $item['goodsname'] . "||";
			$goods_price .= $MemberPice . "||";
			$goods_count .= $item['count'] . "||";
			$oe .= $item['oeid'] . "||";
			//$Result_ok = $DB->query("insert into `{$INFO[DBPrefix]}order_detail` (gid,goodsname,unit,good_color,good_size,goodscount,market_price,price,order_id,provider_id) values('".$item['gid']."','".$item['goodsname']."','".$item['unit']."','".$item['good_color']."','".$item['good_size']."','".$item['count']."','".$item['temp_price']."','".$MemberPice."','".$Insert_id."','".$item['provider_id']."')");
			
			
			$FUNCTIONS->setStorage(intval($item['count']),"1",intval($item['gid']),intval($item['detail_id']),$item['good_size'],$item['good_color'],"",1,$Insert_id,$item['xygoods']);
			
		}
		
		if ($totalbonuspoint > 0){
			$FUNCTIONS->BuyBonuspoint(intval($_SESSION['user_id']),$totalbonuspoint,"訂單" . $Order_serial . "兌換紅利",$Insert_id);
		}
		if (intval($Cart_bonus['point']) > 0){
			$FUNCTIONS->BuyBonuspoint(intval($_SESSION['user_id']),intval($Cart_bonus['point']),"訂單" . $Order_serial . "抵扣紅利",$Insert_id);
			
		}
		if ($Cart_combipoint>0){
			$FUNCTIONS->BuyBonuspoint(intval($_SESSION['user_id']),intval($Cart_combipoint),"訂單" . $Order_serial . "使用會員積分價格",$Insert_id);
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
			if($pay_id==96||$pay_id==97){
				$Psql = "select * from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.mid='" . $pay_id . "' order by p.mid";
				$PQuery    = $DB->query($Psql);
				$PRs=$DB->fetch_array($PQuery);
				if ($PRs['mid']>0){
					$Atm = GTXNAcc($Insert_id,$PRs['shopcode'],intval($Cart_discount_totalPrices+$Cart_transmoney));
					$tpl->assign("ATM",$Atm);
					$Update_sql = "update `{$INFO[DBPrefix]}order_table` set atm='" . $Atm . "' where order_id=".intval($Insert_id);
					$DB->query($Update_sql);
				}
			}
			
			require "oeya_cps.php";
			//oeyaapi(intval($_SESSION['user_id']),$Receiver_name,$Order_serial,$goods_bn,$goods_name,$goods_price,$goods_count,$Cart_discount_totalPrices,$oe,trim($_POST['receiver_email']),trim($_POST['receiver_tele']),$other);
			
			$cart->clearGoods(($_POST['key']));
		
	}
	
	$tpl->assign("receiver_name",         $FUNCTIONS->getOrderUInfo($_POST['receiver_name'],1));
	$tpl->assign("receiver_mobile",       $FUNCTIONS->getOrderUInfo($_POST['receiver_mobile'],5));
	$tpl->assign("receiver_email",        $FUNCTIONS->getOrderUInfo($_POST['receiver_email'],5));
	$tpl->assign("receiver_tele",         $FUNCTIONS->getOrderUInfo($_POST['receiver_tele'],5));
	$tpl->assign("receiver_address",      $FUNCTIONS->getOrderUInfo($_POST['receiver_address'],10));	
	$tpl->assign("true_name",       $FUNCTIONS->getOrderUInfo($_POST['true_name'],1));
//}else{
//	$Order_serial = getOrderNo();
//	$_POST['receiver_memo'] .= $cart->saleoffinfo;	
//	$tpl->assign("receiver_email",        $_POST['receiver_email']);
//	$tpl->assign("receiver_tele",         $_POST['receiver_tele']);
//	$tpl->assign("receiver_address",      str_replace("請選擇","",$cart->transname_area.$cart->transname_area2.$_POST['city'].trim($_POST['addr'])));
//	$tpl->assign("receiver_name",         $_POST['receiver_name']);
//	$tpl->assign("receiver_mobile",       $_POST['receiver_mobile']);
//	$tpl->assign("true_name",       $_POST['true_name']);
//}
function getOrderNo(){
	global $INFO,$DB;
	$Sql_order   = "select order_serial as max_num from `{$INFO[DBPrefix]}order_table`  where order_year='".date("Y",time())."' and order_month='".date("m",time())."' and order_day='".date("d",time())."' order by order_id desc limit 0,1";
	$Query_order = $DB->query($Sql_order);
	$Rs_order = $DB->fetch_array($Query_order);
	if ($Rs_order['max_num']!=0){
		$m = intval(substr($Rs_order['max_num'],8,4))+1;
		$Next_order_serial = date("Ymd",time()) . str_repeat("0",4-strlen($m)) . $m  . rand(0,9);
		//$ATM_Num = substr($Next_order_serial,-8,strlen(trim($Next_order_serial)));
	}else{
		$Next_order_serial = date("Ymd",time())."0001"  . rand(0,9);
		//$ATM_Num =  intval(date("ymd",time()))."0001";
	}
	$Order_serial = $Next_order_serial;
	return $Order_serial;
}

function setOrder(){
	global $db_string,$INFO,$DB;
	 $order_serial = getOrderNo();
	//$order_serial = "201110280018";
	$LOCK = $DB->query("LOCK TABLES `{$INFO[DBPrefix]}order_table` WRITE");
	$Sql="INSERT INTO `{$INFO[DBPrefix]}order_table` (".$db_string['FIELD_NAMES'].",order_serial) VALUES (".$db_string['FIELD_VALUES'].",'" . $order_serial . "')";
	$Result = $DB->query($Sql,1);
	if ($Result == false){
		$UNLOCK = $DB->query("UNLOCK TABLES");
		//echo "ddddddddddddd";exit;
		setOrder();
	}else{
		$UNLOCK = $DB->query("UNLOCK TABLES");
		return $order_serial;
	}
		
}
function checkStorage($gid,$detail_id=0,$color="",$size=""){
	global $DB,$INFO;
	if ($gid>0){
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($gid)."  limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			if ($Result['storage']>0){
				if ($detail_id>0){
					$Sql_d = "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . $gid . "' and detail_id='" . $detail_id . "'";
					$Query_d    = $DB->query($Sql_d);
					$Nums_d      = $DB->num_rows($Query_d);	
					if ($Nums_d>0){
						$Rs_d=$DB->fetch_array($Query_d);
						return $Rs_d['storage'];	
					}else{
						return "0";	
					}
				}elseif ($size!="" || $color!=""){
					$Sql_s      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($gid) . " and size='" . $size . "' and color = '" . $color . "'";
					$Query_s    = $DB->query($Sql_s);
					$Nums_s      = $DB->num_rows($Query_s);	
					if ($Nums_s>0){
						$Rs_s=$DB->fetch_array($Query_s);
						return $Rs_s['storage'];	
					}else{
						return "0";	
					}
				}else{
					return $Result['storage'];	
				}
			}else{
				return "0";	
			}
		}else{
		return "0";
		}
		
	}	
	return "0";
}
/**
生成金流支付表單
**/
include_once "PayFunction.php";
if (intval($_SESSION['user_id'])>0)
	$uno = intval($_SESSION['user_id']);
else
	$uno = "NO" . rand(50000,9999999);
	
$pay_array = array(
				  "order_serial"    =>   $Order_serial,
				  "total"    =>   round($Cart_discount_totalPrices+$Cart_transmoney-$Cart_buypoint,0),
				  "receiver_name"   => $Receiver_name,
				  "receiver_address"   => $cart->transname_area.$cart->transname_area2.$_POST['city'].trim($_POST['addr']),
				  "receiver_tele"   => trim($_POST['receiver_tele']),
				  "receiver_mobile"   => trim($_POST['receiver_mobile']),
				  "receiver_email"   => trim($_POST['receiver_email']),
				  "receiver_post"   => trim($_POST['othercity']),
				  "order_id"   => $Insert_id,
				  "userno"   => $uno,
				  "atm"   => $Atm,
				  );
$payFunction = new PayFunction;
$_SESSION['ticket'] = "";
$_SESSION['shopping_ip'] = $FUNCTIONS->getip();
	$_SESSION['shopping_orderserial'] = $Order_serial;
	$_SESSION['shopping_orderid'] = $Insert_id;

	if ($cart->transname_id==19  && (intval($_POST['pay_id'])!=69 || $Cart_discount_totalPrices+$Cart_transmoney-$Cart_buypoint<=0)){
		$pay_array['total'] = 0;
		echo $payform = $payFunction->CreatePay(69,$pay_array);	
		exit;
	}elseif ($paytype==0 && $Cart_discount_totalPrices+$Cart_transmoney-$Cart_buypoint>0){
		echo $payform = $payFunction->CreatePay(intval($_POST['pay_id']),$pay_array);
		exit;	
	}
//$tpl->assign("payform",                   $payform);
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
	
	$Array =  array("Order_id"=>$Insert_id,"receiver_name"=>trim($Receiver_name),"truename"=>trim($truename),"orderid"=>trim($Order_serial));
	
	include "SMTP.Class.inc.php";
	include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
	$SMTP =  new smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
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
	
}
echo "<script>location.href='showorder.php?orderno=" . $Order_serial . "';</script>";
exit;

/**
 * 以下是宅配時間资料推到前台的变量
 
$tpl->assign("Action",                   trim($_POST['Action']));
$tpl->assign("key",                   trim($_POST['key']));
$tpl->assign("pay_id",                   trim($_POST['pay_id']));
$tpl->assign("transport_id",             intval($cart->transname_id));
$tpl->assign("province",                 trim($_POST['province']));
$tpl->assign("city",                     trim($_POST['city']));
$tpl->assign("Country",                     trim($_POST['country']));
$tpl->assign("addr",                     trim($_POST['addr']));
$tpl->assign("ifsendreceiver",                     intval($_POST['ifsendreceiver']));
$tpl->assign("transtime64decode_name",  $transtime64decode_name);
$tpl->assign("transtime64encode_name",  $transtime64encode_name);
$tpl->assign("transtime_id",            $transtime_id);

$tpl->assign("Order_serial",        $Order_serial); //订单编号
$tpl->assign("Need_invoice",          intval($INFO['Need_invoice']));
$tpl->assign("othercity",             $_POST['othercity']);   //  receiver_post

$tpl->assign("receiver_post",         $_POST['othercity']);
$tpl->assign("receiver_memo",         $_POST['receiver_memo']);
$tpl->assign("email",         $_POST['email']);

$tpl->assign("post",         $_POST['othercity2']);
$tpl->assign("county2",         $_POST['county2']);
$tpl->assign("province2",         $_POST['province2']);
$tpl->assign("city2",         $_POST['city2']);
$tpl->assign("addr2",         $_POST['addr2']);
$tpl->assign("tel",         $_POST['tel']);
$tpl->assign("other_tel",         $_POST['other_tel']);
$tpl->assign("paymentname",             $paymentname);   //付款方式
$tpl->assign("Point",            $Point);   //累计积分
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
$tpl->assign("Cart_tickets",       $Cart_tickets);
$tpl->assign("Cart_bonus",         $Cart_bonus['point']+intval($totalbonuspoint));
$tpl->assign("Cart_sys_trans_type",         $Cart_sys_trans_type);
$tpl->assign("Cart_sys_trans",         $Cart_sys_trans);
$tpl->assign("Cart_discount_totalPrices", $Cart_discount_totalPrices);
$tpl->assign("Cart_special_trans_name", $Cart_special_trans_name);
$tpl->assign("Cart_special_trans_type", $Cart_special_trans_type);
$tpl->assign("Cart_transmoney", $Cart_transmoney);
$tpl->assign("Cart_nomal_trans_type", $Cart_nomal_trans_type);
$tpl->assign("Cart_combipoint",   $Cart_combipoint);
$tpl->assign("SendType",  $SendType);
$tpl->assign("Gpicpath",         $INFO['good_pic_path']);
$tpl->assign("IfNeed_invoice",       intval($INFO['Need_invoice']));  //系统中指定的是否需要发
$tpl->assign("Cart_buypoint",   intval($Cart_buypoint));


$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Basic_Command);
if (substr($_POST['key'],0,2)=="FB")
$tpl->display("shopping4_fb.html");
else
$tpl->display("shopping4.html");
*/
?>
