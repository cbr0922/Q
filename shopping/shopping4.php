<?php
error_reporting(7);
require_once('../Classes/cart.class.php' );
session_start();
include("../configs.inc.php");
include_once 'crypt.class.php';
include("global.php");
include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";
include "SMTP.Class.inc.php";
	include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
	$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
	include_once "sms2.inc.php";
	include_once "sendmsg.php";
	$sendmsg = new SendMsg;
	require_once("mat.php");
SMSP_load_mods();
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
//print_r($cart->tickets);exit;
//$cart->transname_area = $_POST['county'];
//$cart->transname_area2 = $_POST['province'];
//購物車中商品
$items_array = $cart->getCart($_POST['key']);
if (!is_array($items_array) || count($items_array)<=0){
	echo "<script>location.href='shopping.php';</script>";
	exit;
}
$Cart_item = array();
$i = 0;
$ds_array = array();
foreach($items_array as $k => $v){
	//print_r($v);
	if ($v['packgid']==0){
		if ($v['promotion_state']==4)
			$ds_array[$i] = $v['dsid'];
		$Cart_item[$i] = $v;
		$provider_id = $v['provider_id'];
		$Cart_item[$i]['total'] = $v['count']*$v['price'];
		$i++;
	}
}
$ds_array = array_filter(array_unique($ds_array));
//print_r($ds_array);
//exit;
$cart->setTotal($_POST['key']);
$totalbonuspoint = $cart->setGroupbonuspoint($_POST['key']);
$Cart_totalPrices = $cart->totalPrices;//商品網絡總計
$Cart_discount_totalPrices = $cart->discount_totalPrices;//優惠金額
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
$Sql = "SELECT  u.*,s.login,s.enddate,s.startdate,s.ifpub FROM `{$INFO[DBPrefix]}user` as u left join `{$INFO[DBPrefix]}saler` as s on u.companyid=s.id  where u.user_id='".intval($_SESSION['user_id'])."' limit 0,1";
$Query  = $DB->query($Sql);
$Rs=$DB->fetch_array($Query);
$caddress = str_replace("請選擇","",$Rs[Country].$Rs[canton].$Rs[city]);//地址
$ctel = $Rs[tel].",".$Rs['other_tel'];//电话
$username = $Rs['username'];
$recommendno = $Rs['recommendno'];
$companyid = $Rs['companyid'];
$truename = $Rs['true_name'];
$useremail = $Rs['email'];
$startdate = $Rs['startdate'];
$enddate = $Rs['enddate'];
$ifpub = $Rs['ifpub'];
if($Rs['login']!="" && ($startdate<=date("Y-m-d") || $startdate=="") && ($enddate>=date("Y-m-d")||$enddate=="") && $ifpub==1)
	$_SESSION['saler'] = $Rs['login'];
/**
 * 这里是根据上边传输过来的资料来确定支付的名字以及是内部设定的付款方式还是线上金流
 */
//發票處理
if  (intval($INFO['Need_invoice'])==1){
	if($_POST['ifinvoice'] == 0 ){
		$yesifinvoice = $Cart[Two_piao];
		$pCarrierType = intval($_POST['pCarrierType']);
		$pCarrierNum = $_POST['pCarrierNum' . intval($_POST['pCarrierType'])];
	}elseif($_POST['ifinvoice'] == 1 )
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
function YSXNAcc($ordernum,$shopno,$total){
	$sunday = 0;
	if(strlen($ordernum)<6)
		$ordernum = str_repeat("0",6-strlen($ordernum)) . $ordernum;
	$a = "654321987654321";
	$b = "21987654321";
	$date = date("md",time()+60*60*24*7);
	$len1 = strlen($shopno.$date.$ordernum);
	$quan1 = substr($a,15-$len1);
	$len2 = strlen($total);
	$quan2 = substr($b,11-$len2);
	$zong1=0;
	$zong2=0;
	$string1 = $shopno.$date.$ordernum;
	for($i=0;$i<$len1;$i++){
		$zong1+=substr($string1,$i,1)*substr($quan1,$i,1);
	}
	for($i=0;$i<$len2;$i++){
		$zong2+=substr($total,$i,1)*substr($quan2,$i,1);
	}
	$zong3 = ($zong1+$zong2);
	$checkno = substr($zong3,strlen($zong3)-1);
	$xn = $shopno . $date . $ordernum . $checkno;
	return $xn;
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
	$code = $shopno . date("md",time()+60*60*24*1) . $ordernum;
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
	 $xn = $shopno . date("md",time()+60*60*24*1) . $ordernum . $check2;
	//exit;
	return $xn;
}
function getHNAcc($ordernum,$shopno,$total){
	if(strlen($ordernum)<8)
		$ordernum = str_repeat("0",8-strlen($ordernum)) . $ordernum;
	$code = $shopno . $ordernum;
	$quan = "9379379379379";
	$zong = 0;
	for($i=0;$i<13;$i++){
		$cheng=substr($code,$i,1)*substr($quan,$i,1);
		$zong+=$cheng;
	}
	$shang = intval($zong/11);
	$yushu = intval($zong%11);
	if($yushu==1)
		$s = 11;
	elseif($yushu==0)
		$s = 10;
	else
		$s = $yushu;
	$j = 11-$s;
	return $code.$j;
}
function YS14XNAcc($ordernum,$shopno,$total){
	$sunday = 0;
	if(strlen($ordernum)<8)
		$ordernum = str_repeat("0",8-strlen($ordernum)) . $ordernum;
	$a = "4321987654321";
	$b = "21987654321";
	$len1 = strlen($shopno.$ordernum);
	$quan1 = substr($a,13-$len1);
	$len2 = strlen($total);
	$quan2 = substr($b,11-$len2);
	$zong1=0;
	$zong2=0;
	$string1 = $shopno.$date.$ordernum;
	for($i=0;$i<$len1;$i++){
		$zong1+=substr($string1,$i,1)*substr($quan1,$i,1);
	}
	for($i=0;$i<$len2;$i++){
		$zong2+=substr($total,$i,1)*substr($quan2,$i,1);
	}
	$zong3 = ($zong1+$zong2);
	$checkno = substr($zong3,strlen($zong3)-1);
	$xn = $shopno . $ordernum . $checkno;
	return $xn;
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
	$paymentname = "購物金";
	$pay_state   =1 ; //已经交付
	$order_state =1 ; //已经确定
}else{
	$pay_state   =0 ; //已经交付
	$order_state =0 ; //已经确定
}$tpl->assign("paymentname",     $paymentname);   //ATM
$tpl->assign("ATM",     $ATM_STRING);   //ATM
// 電子發票
	$invoice_print  = $_POST['invoice_print'];
	if ( $invoice_print == "yes" ){
		$invoice_print_txt = "是";
	}
	else{
		$invoice_print_txt = "否";
	}
	if( trim($_POST['ifinvoice']) == "2" &&  $invoice_print != "yes" && trim($_POST['invoice_num']) == "" ){
		$invoice_donate = $_POST['invoice_donate'];
	}
	else{
		$invoice_donate = "";
	}
	$tpl->assign("invoice_print"      , $invoice_print );
	$tpl->assign("invoice_donate"     , $invoice_donate );
if( $INFO['mod.einvoice.enable'] == "yes" )
{
	$charity = new Charity();
	$charity_info = $charity->get( $invoice_donate );
	$invoice_donate_txt = $charity_info['fullname'];
	if( !empty($charity_info['name']) ){
		$invoice_donate_txt = $invoice_donate_txt."(".$charity_info['name'].")";
	}
	$tpl->assign("invoice_print_txt"  , $invoice_print_txt );
	$tpl->assign("invoice_donate_txt" , $invoice_donate_txt );
}
//如果是非会员,将把非会员信息插入数据库
//if ($_SESSION['user_id']==""){
//	$Receiver_name = trim($_POST['receiver_name']).$Cart[NO_member]; //非会员
//}else{
	$Receiver_name = trim($_POST['receiver_name']);
//}
if ($_POST['Action']=="view" || $_POST['Action']==""){
	$_POST['Action']="view";
	$Array_HomeTimeType      = array();
	$Array_HomeTimeType      =  explode("(&)",$_POST[HomeTimeType]);
	$transtime_id            =  $Array_HomeTimeType[0];
	$transtime64encode_name  =  $Array_HomeTimeType[1];
	$transtime64decode_name  =  base64_decode($transtime64encode_name);
	$tpl->assign("yesifinvoice",        $yesifinvoice);
}
if($cart->transname_id==23){
		$okmap = $cart->okmap['name'] . " (" . $cart->okmap['id'] . ") " . $cart->okmap['addr'];
	}
//if ($_POST['Action']=="insert" || $paytype == 0){
	if (intval($_SESSION['user_id'])==0){
		$Sql  = "select * from `{$INFO[DBPrefix]}user` u where u.email='".trim($_POST['email'])."'  limit 0,1";
			$Query = $DB->query($Sql);
			$Num   = $DB->num_rows($Query);
			if($Num>0){
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您使用的郵箱已存在，您是否登入後再結帳，或換個郵箱繼續結帳');location.href='shopping3.php?key=" . $_POST['key'] . "';</script>";exit;				}
			$One   = rand(1,10);
		$Two   = rand(11,80);
		$Three = rand(10,40);
		$Four  = rand(5,9);
		$Five  = rand(6,16);
		$Six   = rand(100,1000);
		$Query_country = $DB->query("select membercode from `{$INFO[DBPrefix]}area` where areaname='" . $cart->transname_area . "' and top_id=0");
		$Rs_country = $DB->fetch_array($Query_country);
		$firstcode = $Rs_country['membercode'];
		$memberno = $FUNCTIONS->setMemberCode($firstcode);
		$authnum=randstr(5);
		if($_SESSION['saler']!=""){
			$Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}saler` where login='" . $_SESSION['saler'] . "' and ifpub=1 and (startdate<='" . date("Y-m-d") . "' or startdate='') and (enddate>='" . date("Y-m-d") . "' or enddate='') limit 0,1");
			$Result_old = $DB->fetch_array($Query_old);
			$companyid = intval($Result_old['id']);
			$userlevel = $Result_old['userlevel'];
		}else{
			$userlevel = intval($INFO['reg_userlevel']);
		}

			$db_string = $DB->compile_db_insert_string( array (
			'password'          => password_hash(trim($_POST['password']), PASSWORD_BCRYPT),
			'username'         => trim($_POST['email']),
			 'true_name'         => trim($_POST['true_name']),
			'cn_secondname'         => trim($_POST['cn_secondname']),
			'en_firstname'         => trim($_POST['en_firstname']),
			'en_secondname'         => trim($_POST['en_secondname']),

			'bornCountry'         => trim($_POST['bornCountry']),
			  'email'             => trim($_POST['email']),
			  'addr'              => trim($_POST['addr2']),
			  'city'              => $_POST['city2'],
			  'canton'            => $_POST['province2'],
			  'Country'            => $_POST['county2'],
			  'zip'               => trim($_POST['othercity2']),
				'certcode'               => trim($_POST['certcode']),
			  'post'              => trim($_POST['post']),
			  'sex'              => trim($_POST['sex']),
			  'born_date'         => trim($_POST['byear']."-".$_POST['bmonth']."-".$_POST['bday']),
			  'tel'               => MD5Crypt::Encrypt ( trim($_POST['tel']), $INFO['tcrypt']),
			  'other_tel'         => MD5Crypt::Encrypt ( trim($_POST['other_tel']), $INFO['mcrypt']),
			  'memberno'         => trim($memberno),
			  'user_level'        => $userlevel,
			'authnum' =>$authnum,
			'companyid'         => $companyid,
			'user_state'=>1,
			'dianzibao'    => intval($_POST['dianzibao']),
			'reg_date'          => date("Y-m-d",time()),
	'reg_ip'            => $FUNCTIONS->getip(),
			)      );
			$Sql="INSERT INTO `{$INFO[DBPrefix]}user` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
			$Result_Update = $DB->query($Sql);
			$user_id = mysql_insert_id();
			$Sql  = "select * from `{$INFO[DBPrefix]}user` u where u.user_id='".$user_id."' limit 0,1";
			$Query = $DB->query($Sql);
			$Num   = $DB->num_rows($Query);
			$Result= $DB->fetch_array($Query);
			$sendemail = trim($_POST['receiver_email']);
			$truename = $Receiver_name;
			$FUNCTIONS->AddBonuspoint(intval($user_id),intval($INFO['regpoint']),6,"會員註冊" . trim($_POST['email']),1,0);
		$Array =  array("authnum"=>trim($authnum),"username"=>trim($_POST['email']),"truename"=>trim($_POST['true_name']),"password"=>trim($_POST['password']));
		$SMTP->MailForsmartshop(trim($_POST['email']),"",1,$Array);
		$sendmsg->send(trim($_POST['other_tel']),$Array,1);
		$useremail = trim($_POST['email']);
		$first=1;
	}else{
		$user_id = intval($_SESSION['user_id'])	;
			$Query1 = $DB->query("select * from `{$INFO[DBPrefix]}user` where user_id=".$user_id." limit 0,1 ");
			$Num1   =  $DB->num_rows($Query1);
			if ( $Num1 > 0 ){
				$Rs1  = $DB->fetch_array($Query1);				
				$born_date   = $Rs1['born_date'];
			}
			if($born_date != '' && $born_date != '--'){
			  $born_date_update=$born_date;
			}else{
				$born_date_update=$_POST['byear']."-".$_POST['bmonth']."-".$_POST['bday'];
			}
		$db_string = $DB->compile_db_update_string( array (
			  'true_name'         => trim($_POST['true_name']),
			  'email'             => trim($_POST['email']),

			'certcode'               => trim($_POST['certcode']),

			'cn_secondname'         => trim($_POST['cn_secondname']),
			'en_firstname'         => trim($_POST['en_firstname']),
			'en_secondname'         => trim($_POST['en_secondname']),

			'bornCountry'         => trim($_POST['bornCountry']),
			   'tel'               => MD5Crypt::Encrypt ( trim($_POST['tel']), $INFO['tcrypt']),
			  'other_tel'         => MD5Crypt::Encrypt ( trim($_POST['other_tel']), $INFO['mcrypt']),
			  'born_date'         => trim($born_date_update),
			  //'born_date'         => trim($_POST['byear']."-".$_POST['bmonth']."-".$_POST['bday']),
			  //'reg_date'          => date("Y-m-d",time()),
			)      );
				  $Sql = "UPDATE `{$INFO[DBPrefix]}user` SET $db_string WHERE user_id=".intval($_SESSION['user_id']);
			$Result_Update = $DB->query($Sql);
			$useremail = trim($_POST['email']);
			$first=0;
	}
	if ($paytype == 0)
		$_POST['receiver_memo'] .= $cart->saleoffinfo;
	if ($_POST[HomeTimeType]!=""){
		$Array_HomeTimeType      =  explode("(&)",$_POST[HomeTimeType]);
		$transtime_id            =  $Array_HomeTimeType[0];
		$transtime64encode_name  =  $Array_HomeTimeType[1];
		$transtime64decode_name  =  base64_decode($Array_HomeTimeType[1]);
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
	}
	$key_value = explode("_",$_POST['key']);
	$key_value_shop = explode("S",$_POST['key']);
	if($key_value[1]==0 && $key_value[0]==0)
		$iftogether = 1;
	if($INFO['sp_orderdate']<=date("Y-m-d") && $INFO['sp_orderenddate']>=date("Y-m-d")  && intval($INFO['sp_ordermultiple'])>0){
		$sp_ordermultiple = $INFO['sp_ordermultiple'];
	}
	if($pay_id==93){
		$okgo_code = getOkgo();
	}
	$db_string = $DB->compile_db_insert_string( array (
	'user_id'                      => $user_id,
	'receiver_name'                => $Receiver_name,
	'pay_state'                    => $pay_state,
	'onlinepay'                    => intval($PayPay),
	'order_state'                  => intval($order_state),
	'receiver_address'             => $cart->transname_area.$cart->transname_area2.$_POST['city'].trim($_POST['addr']),
	'receiver_email'               => trim($_POST['receiver_email']),
	'receiver_post'                => trim($_POST['othercity']), //  receiver_post
	'receiver_tele'                => MD5Crypt::Encrypt ( trim($_POST['receiver_tele']), $INFO['tcrypt']),//trim($_POST['receiver_tele']),
	'receiver_mobile'              => MD5Crypt::Encrypt ( trim($_POST['receiver_mobile']), $INFO['mcrypt']),//trim($_POST['receiver_mobile']),
	'receiver_memo'                => trim($_POST['receiver_memo']),
	'invoiceform'                  => trim($_POST['invoiceform']),
	'invoice_num'                  => ($_POST['invoice_num']),
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
	'senddate'                =>trim($_POST['datepicker']),
	'storename'                =>$cart->cvsname,
	'storeid'                =>$cart->cvsnum,
	'session_id' =>$session_id,
	'pCarrierType' =>$pCarrierType,
	'pCarrierNum' =>$pCarrierNum,
	'ifmobile' =>$ismobile,
	'flightstyle'                =>$cart->flightstyle,
	'flightid'                =>$cart->flightid,
	'flightno'                =>$cart->flightno,
	'flightdate'                =>$cart->flightdate,
	'Departure'                =>$cart->Departure,
	)      );
	$Order_serial = setOrder();
	//$LOCK = $DB->query("LOCK TABLES `{$INFO[DBPrefix]}order_table` WRITE");
	//$Sql="INSERT INTO `{$INFO[DBPrefix]}order_table` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	//$Result = $DB->query($Sql);
	//$UNLOCK = $DB->query("UNLOCK TABLES");
	// 電子發票
		$Sql = sprintf( "UPDATE `{$INFO[DBPrefix]}order_table` set %s='%s', %s='%s' where order_serial='%s'"
				, 'invoice_donate', trim($invoice_donate)
				, 'invoice_print' , trim($_POST['invoice_print'])
				, $Order_serial
			      );
		$Result = $DB->query($Sql);
	if ($Cart_tickets['id'] > 0 ){
		if ($Cart_tickets['type'] == 0 && $Cart_tickets['canmove']==0 && $Cart_tickets['ticketcode']=="" ){
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
			if($item['detail_name']=="")
				$item['detail_des'] = $item['promotion_name'];
			if($item['packgid']>0){
				//設置數量
				$Sql_p         = "select gl.* from `{$INFO[DBPrefix]}goods_pack` gl where gl.gid=".$item['packgid']." and gl.packgid='".$item['gid']."' order by gl.idate desc ";
				$Query_p       = $DB->query($Sql_p);
				$Result_p      = $DB->fetch_array($Query_p);
				if($Result_p['count']>0){
					$item['count'] = $item['count']*$Result_p['count'];
				}
			}
			$DB->query("LOCK TABLES `{$INFO[DBPrefix]}order_detail` WRITE");
			$Sql_Ok = "insert into `{$INFO[DBPrefix]}order_detail` (gid,goodsname,unit,good_color,good_size,goodscount,market_price,price,order_id,point,provider_id,month,detail_id,detail_bn,detail_name,detail_des,xygoods,ifxygoods,ifchange,xygoods_color,xygoods_size,xygoods_des,oeid,memberorprice,memberprice,combipoint,bonuspoint,ifbonus,bn,iftogether,cost,salecontent,detail_pay_state,detail_order_state,ifpack,packgid) values('".$item['gid']."','".str_replace("'","''",$item['goodsname']).$item['rebateinfo']."','".$item['unit']."','".$item['good_color']."','".$item['good_size']."','".$item['count']."','".intval($item['temp_price'])."','".$item['price']."','".$Insert_id."','".$item['point']."','".$item['provider_id']."','".intval($item['month'])."','".intval($item['detail_id'])."','".$item['detail_bn']."','".$item['detail_name']."','".$item['detail_des']."','" . $xygoods . "','" . intval($item['ifxygoods']) . "','" . intval($item['ifchange']) . "','" . $xygoods_color . "','" . $xygoods_size . "','" . $item['xygoods_des'] . "','" . $item['oeid'] . "','" . $item['memberorprice'] . "','" . $item['memberprice'] . "','" . $item['combipoint'] . "','" . $item['bonuspoint'] . "','" . $item['ifbonus'] . "','" . $item['bn'] . "','".$item['iftogether']."','" . $cost . "','" . $salecontent . "','" . intval($pay_state) . "','" . intval($order_state) . "','" . $item['ifpack'] . "','" . $item['packgid'] . "')";
			$Result_ok = $DB->query($Sql_Ok);
			$DB->query("UNLOCK TABLES");
			$goods_bn .= $item['gid'] . "||";
			$goods_name .= $flag.$item['goodsname'];
			$goods_price .= $flag.$item['price'];
			$goods_count .= $flag.$item['count'];
			$oe .= $item['oeid'] . "||";
			$flag = "#";
			//$Result_ok = $DB->query("insert into `{$INFO[DBPrefix]}order_detail` (gid,goodsname,unit,good_color,good_size,goodscount,market_price,price,order_id,provider_id) values('".$item['gid']."','".$item['goodsname']."','".$item['unit']."','".$item['good_color']."','".$item['good_size']."','".$item['count']."','".$item['temp_price']."','".$MemberPice."','".$Insert_id."','".$item['provider_id']."')");

			$FUNCTIONS->setStorage(intval($item['count']),"1",intval($item['gid']),intval($item['detail_id']),$item['good_size'],$item['good_color'],"",0,1,$Insert_id,$item['xygoods'],0);
			
			
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
			$FUNCTIONS->AddBuypoint(intval($_SESSION['user_id']),intval($Cart_buypoint),1,"訂單" . $Order_serial . "使用購物金",$Insert_id);
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
			if($pay_id==114 || $pay_id==117){
				$Psql = "select * from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.mid='" . $pay_id . "' order by p.mid";
				$PQuery    = $DB->query($Psql);
				$PRs=$DB->fetch_array($PQuery);
				if ($PRs['mid']>0){
					$Atm = YS14XNAcc($Insert_id,$PRs['shopcode'],intval($Cart_discount_totalPrices-$Cart_buypoint+$Cart_transmoney));
					$tpl->assign("ATM",$Atm);
					$Update_sql = "update `{$INFO[DBPrefix]}order_table` set atm='" . $Atm . "' where order_id=".intval($Insert_id);
					$DB->query($Update_sql);
				}
			}
			if($pay_id==93||$pay_id==94){
				$Psql = "select * from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.mid='" . $pay_id . "' order by p.mid";
				$PQuery    = $DB->query($Psql);
				$PRs=$DB->fetch_array($PQuery);
				if ($PRs['mid']>0){
					$f1 = explode("|",$PRs['f1']);
					$shopno = $f1[1];
					$Atm = getHNAcc($Insert_id,$shopno,intval($Cart_discount_totalPrices+$Cart_transmoney));
					$tpl->assign("ATM",$Atm . "[華南銀行 008]");
					$Update_sql = "update `{$INFO[DBPrefix]}order_table` set atm='" . $Atm . "[華南銀行 008]" . "' where order_id=".intval($Insert_id);
					$DB->query($Update_sql);
				}
			}
			if($pay_id==96||$pay_id==97){
				$InsertId_Sql = "select count(*) as todayno from `{$INFO[DBPrefix]}order_table`  where order_year='".date("Y",time())."' and order_month='".date("m",time())."' and order_day='".date("d",time())."' and order_id<='" . $Insert_id . "' limit 0,1";
					$InsertId_Query = $DB->query($InsertId_Sql);
					$InsertId_Result= $DB->fetch_array($InsertId_Query);
					$todayno  = $InsertId_Result[todayno];
				$Psql = "select * from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.mid='" . $pay_id . "' order by p.mid";
				$PQuery    = $DB->query($Psql);
				$PRs=$DB->fetch_array($PQuery);
				if ($PRs['mid']>0){
					$Atm = GTXNAcc($todayno,$PRs['shopcode'],intval($Cart_discount_totalPrices+$Cart_transmoney));
					$tpl->assign("ATM",$Atm);
					$Update_sql = "update `{$INFO[DBPrefix]}order_table` set atm='" . $Atm . "' where order_id=".intval($Insert_id);
					$DB->query($Update_sql);
				}
			}
			if($pay_id==167){
				$Psql = "select * from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.mid='" . $pay_id . "' order by p.mid";
				$PQuery    = $DB->query($Psql);
				$PRs=$DB->fetch_array($PQuery);
				if ($PRs['mid']>0){
					$InsertId_Sql = "select count(*) as todayno from `{$INFO[DBPrefix]}order_table`  where order_year='".date("Y",time())."' and order_month='".date("m",time())."' and order_day='".date("d",time())."' and order_id<='" . $Insert_id . "' limit 0,1";
					$InsertId_Query = $DB->query($InsertId_Sql);
					$InsertId_Result= $DB->fetch_array($InsertId_Query);
					$todayno  = $InsertId_Result[todayno];
					$Atm = ZXAcc($todayno,$PRs['shopcode'],intval($Cart_discount_totalPrices+$Cart_transmoney));
					$tpl->assign("ATM",$Atm);
					 $Update_sql = "update `{$INFO[DBPrefix]}order_table` set atm='" . $Atm . "' where order_id=".intval($Insert_id);
					$DB->query($Update_sql);
				}
			}
			if($pay_id==169){
				$Psql = "select * from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.mid='" . $pay_id . "' order by p.mid";
				$PQuery    = $DB->query($Psql);
				$PRs=$DB->fetch_array($PQuery);
				if ($PRs['mid']>0){
					$Atm = DZXNAcc($Insert_id,$PRs['shopcode'],intval($Cart_discount_totalPrices-$Cart_buypoint+$Cart_transmoney));
					$tpl->assign("ATM",$Atm);
					$Update_sql = "update `{$INFO[DBPrefix]}order_table` set atm='" . $Atm . "' where order_id=".intval($Insert_id);
					$DB->query($Update_sql);
				}
			}
			if($pay_id==171){
				$Psql = "select * from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pg on p.pid=pg.pid where p.mid='" . $pay_id . "' order by p.mid";
				$PQuery    = $DB->query($Psql);
				$PRs=$DB->fetch_array($PQuery);
				if ($PRs['mid']>0){
					$InsertId_Sql = "select count(*) as todayno from `{$INFO[DBPrefix]}order_table`  where order_year='".date("Y",time())."' and order_month='".date("m",time())."' and order_day='".date("d",time())."' and order_id<='" . $Insert_id . "' limit 0,1";
					$InsertId_Query = $DB->query($InsertId_Sql);
					$InsertId_Result= $DB->fetch_array($InsertId_Query);
					$todayno  = $InsertId_Result[todayno];
					$Atm = ZHXNAcc($todayno,$PRs['shopcode'],intval($Cart_discount_totalPrices+$Cart_transmoney));
					$tpl->assign("ATM",$Atm);
					$Update_sql = "update `{$INFO[DBPrefix]}order_table` set atm='" . $Atm . "' where order_id=".intval($Insert_id);
					$DB->query($Update_sql);
				}
			}
			if($pay_id==174){
				$InsertId_Sql = "select count(*) as todayno from `{$INFO[DBPrefix]}order_table`  where order_year='".date("Y",time())."' and order_month='".date("m",time())."' and order_day='".date("d",time())."' and order_id<='" . $Insert_id . "' limit 0,1";
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
					$Atm = DYXNAcc($Insert_id,$PRs['shopcode'],intval($Cart_discount_totalPrices-$Cart_buypoint+$Cart_transmoney));
					$tpl->assign("ATM",$Atm);
					$Update_sql = "update `{$INFO[DBPrefix]}order_table` set atm='" . $Atm . "' where order_id=".intval($Insert_id);
					$DB->query($Update_sql);
				}
			}
			require "oeya_cps.php";
			//oeyaapi(intval($_SESSION['user_id']),$Receiver_name,$Order_serial,$goods_bn,$goods_name,$goods_price,$goods_count,$Cart_discount_totalPrices,$oe,trim($_POST['receiver_email']),trim($_POST['receiver_tele']),$other);
			$cart->clearGoods(($_POST['key']));
	//}
	$tpl->assign("receiver_name",         $FUNCTIONS->getOrderUInfo($_POST['receiver_name'],1));
	$tpl->assign("receiver_mobile",       $FUNCTIONS->getOrderUInfo($_POST['receiver_mobile'],5));
	$tpl->assign("receiver_email",        $FUNCTIONS->getOrderUInfo($_POST['receiver_email'],5));
	$tpl->assign("receiver_tele",         $FUNCTIONS->getOrderUInfo($_POST['receiver_tele'],5));
	$tpl->assign("receiver_address",      $FUNCTIONS->getOrderUInfo($_POST['receiver_address'],10));
	$tpl->assign("true_name",       $FUNCTIONS->getOrderUInfo($_POST['true_name'],1));
	$_SESSION['shopping_ip'] = $FUNCTIONS->getip();
	$_SESSION['shopping_orderserial'] = $Order_serial;
	$_SESSION['shopping_orderid'] = $Insert_id;
	$_SESSION['ticket'] = "";
}else{
	$Order_serial = getOrderNo();
	$_POST['receiver_memo'] .= $cart->saleoffinfo;
	$tpl->assign("receiver_email",        $_POST['receiver_email']);
	$tpl->assign("receiver_tele",         $_POST['receiver_tele']);
	$tpl->assign("receiver_address",      str_replace("請選擇","",$cart->transname_area.$cart->transname_area2.$_POST['city'].trim($_POST['addr'])));
	$tpl->assign("receiver_name",         $_POST['receiver_name']);
	$tpl->assign("receiver_mobile",       $_POST['receiver_mobile']);
	$tpl->assign("true_name",       $_POST['true_name']);
}
function getOkgo(){
	return "OK" . time()  . rand(10,99);
}
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
	$Sql="INSERT INTO `{$INFO[DBPrefix]}order_table` (".$db_string['FIELD_NAMES'].",order_serial,pay_serial) VALUES (".$db_string['FIELD_VALUES'].",'" . $order_serial . "','" . $order_serial . "0')";
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
				  "goods_name"   => $goods_name,
				  "goods_price"   => $goods_price,
				  "goods_count"   => $goods_count,
				  "date"   => $Time,
				  "atm"   => $Atm,
				  "st_code"   => $cart->cvscate.$cart->cvsnum,
				  );
$payFunction = new PayFunction;
if ($cart->transname_id==29 ){
	if($_POST['pay_id']==69){
		$pay_array['order_type'] = 1;

	}else{
		$pay_array['order_type'] = 3;
		$pay_array['total'] = 0;
	}
	echo $payform = $payFunction->CreatePay(69,$pay_array,0);
	if($payform!="")
		exit;
}elseif ($paytype==0){
	echo $payform = $payFunction->CreatePay(intval($_POST['pay_id']),$pay_array,0);
	if($payform!="")
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
	/*
	$Query = $DB->query("select o.email from `{$INFO[DBPrefix]}operater` as o inner join `{$INFO[DBPrefix]}operatergroup` as og on o.groupid=og.opid where og.maillist like '%,6' or og.maillist like '%,6,%' or og.maillist like '6,%' or og.maillist='6'");
	$Result= $DB->fetch_array($Query);
	while($Result= $DB->fetch_array($Query)){
		if ($Result['email']!=""){
			$cmail .= "," . $Result['email'];
		}
	}
	$Sql   = "select * from `{$INFO[DBPrefix]}administrator` as og where og.maillist like '%,6' or og.maillist like '%,6,%' or og.maillist like '6,%' or og.maillist='6'";
		$Query = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result = $DB->fetch_array($Query);
			$cmail .= "," . $Result['email'];
	}
	*/
	$Array =  array("Order_id"=>$Insert_id,"username"=>trim($username),"receiver_name"=>trim($Receiver_name),"truename"=>trim($truename),"orderid"=>trim($Order_serial));
	//echo $_POST['ifsendreceiver'];echo "d";
	if($_POST['ifsendreceiver']==1)
		$useremail .= "," . $_POST['receiver_email'];
	$SMTP->MailForsmartshop($useremail,$cmail,6,$Array);
	//print_r($Array);exit;
	//echo "aaaaaaaaa";
	//include_once "sms2.inc.php";
	//include_once "sendmsg.php";
	$sendmsg = new SendMsg;
	$sendmsg->send(trim($_POST['other_tel']),$Array,6);
	echo "<script>location.href='showorder.php?first=" . $first . "&orderno=" . $Order_serial . "';</script>";
	exit;
}
function randstr($len=6) {
	$chars='0123456789';
	// characters to build the password from
	mt_srand((double)microtime()*1000000*getmypid());
	// seed the random number generater (must be done)
	$password='';
	while(strlen($password)<$len)
	$password.=substr($chars,(mt_rand()%strlen($chars)),1);
	return $password;
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
$tpl->assign("byear",                     trim($_POST['byear']));
$tpl->assign("bmonth",                     trim($_POST['bmonth']));
$tpl->assign("bday",                     trim($_POST['bday']));
$tpl->assign("sex",                     trim($_POST['sex']));
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
$tpl->assign("dianzibao",         $_POST['dianzibao']);
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
$tpl->assign("okmap",  $okmap);
$tpl->assign("datepicker",      trim($_POST['datepicker']));
$tpl->assign("okgo_code",  $okgo_code);
$tpl->assign("password",  $_POST['password']);
$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Basic_Command);
$tpl->assign("ifsenddate",  intval($INFO['ifsenddate']));
if (substr($_POST['key'],0,2)=="FB")
$tpl->display("shopping4_fb.html");
else
$tpl->display("shopping4.html");
?>
