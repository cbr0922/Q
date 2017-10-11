<?php
require_once( '../Classes/cart.class.php' );
session_start();
include("../configs.inc.php");
include("global.php");
include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";

require_once("mat.php");
SMSP_load_mods();
@header("Content-type: text/html; charset=utf-8");


/**
 cart_LOGO的尺寸
*/
$tpl->assign("cart_logo_width",  $INFO["cart_logo_width"]);
$tpl->assign("cart_logo_height", $INFO["cart_logo_height"]);

if(!isset($_SESSION['cart'])) {
	header("Location:shopping.php");
}


$cart =&$_SESSION['cart'];
if(!($cart->flightstyle!="" && $cart->flightid!="" && $cart->flightno!="")){
	if($_POST['flightstyle']==""){
		$FUNCTIONS->sorry_back("back","請選擇航班信息");
	}
	if($_POST['flight-id']==""){
		$FUNCTIONS->sorry_back("back","請填寫航班");
	}
	if($_POST['flight-no']==""){
		$FUNCTIONS->sorry_back("back","請輸入班機號碼");
	}

	$cart->flightstyle = $_POST['flightstyle'];
	$cart->flightid = $_POST['flight-id'];
	$cart->flightno = $_POST['flight-no'];
	$cart->flightdate = $_POST['flight-time'];
	$cart->Departure = $_POST['Departure'];
}
//print_r($_POST);
if($_GET['cvstemp']!="" && $_GET['cvsid']!="" && $_GET['cvsspot']!=""){
	$cart->cvsname = trim(iconv("big5","UTF-8",$_GET['name']));
	$cart->cvsnum = $_GET['cvsspot'];
	$_GET['key'] = $_GET['cvstemp'];
}


if ($_GET['key']==""){
	$_GET['key']=$cart->get_key;
}
if($cart->transname_id==23)
	if($cart->okmap['city']=="" || $cart->okmap['id']=="" || $cart->okmap['name']=="") {
		$FUNCTIONS->sorry_back("back","請選擇取貨門市");
	}
	if($cart->transname_id==29)
	if($cart->cvsname=="" || $cart->cvsnum=="") {
		$FUNCTIONS->sorry_back("back","請選擇取貨門市");
	}

//購物車中商品
$items_array = $cart->getCart($_GET['key']);
if (!is_array($items_array) || count($items_array)<=0){
	header("Location:shopping.php");
}
include_once "PayFunction.php";
$payFunction = new PayFunction;
if ($cart->transname_id==13 &&  $cart->cvsname == ""){
	$pay_array = array(
				  "order_serial"    =>   $sid,
				  "key"   => $_GET['key'],
				  );
	echo $payform = $payFunction->CreatePay(70,$pay_array);	
	exit;
}

$Cart_item = array();
$i = 0;
$ifhaveappoint = 0;
$ifmonth = 1;
$month = array(3,6,12);
$ismonth = array(3=>"1",6=>"1",12=>"1");
foreach($items_array as $k => $v){
	if ($v['packgid']==0){
		$Cart_item[$i] = $v;
		$Cart_item[$i]['total'] = $v['count']*$v['price'];
		$i++;
	}
	//是否存在預購商品
	if($v['ifappoint']==1){
		$ifhaveappoint = 1;	
	}
	$m = explode(",",$v['month']);
	if(!in_array(3,$m)){
		$ismonth[3] = 0;		
	}
	if(!in_array(6,$m)){
		$ismonth[6] = 0;		
	}
	if(!in_array(12,$m)){
		$ismonth[12] = 0;		
	}
	if ($v['if_monthprice'] == 0)
		$ifmonth = 0;
}

$cart->setTotal($_GET['key']);
$Cart_totalPrices = $cart->totalPrices;//商品網絡總計
$Cart_buypoint = $cart->totalBuypoint;
$Cart_discount_totalPrices = $cart->discount_totalPrices;//優惠金額
$Cart_combipoint = $cart->combipoint;//優惠金額
$Cart_tickets = $cart->tickets;//優惠卷
$Cart_bonus = $cart->bonus['point'];//紅利
$Cart_sys_trans_type = $cart->sys_trans_type;//自定義配送方式，配送公式
$Cart_sys_trans = $cart->sys_trans;//配送信息
$Cart_transmoney = $cart->transmoney;//配送配用
$Cart_special_trans_name = $cart->transname;

if (intval($_GET['key']) > 0){
	$man_trans_type	 =1 ;//是否是特殊配送方式
}elseif (intval($_GET['key']) == 0){
	$man_trans_type	 =0 ;
}

$Cart_special_trans_type = $cart->special_trans_type;//是否是特殊配送方式
$Cart_nomal_trans_type = $cart->nomal_trans_type;//一般配送方式所選擇的配送方式



/**
 *  这里是获得宅配時間
 */

$Query  = $DB->query("select transtime_id,transtime_name from `{$INFO[DBPrefix]}transtime` order by transtime_id asc ");
$i=0;
while($Rs=$DB->fetch_array($Query)){
	$HomeTimeType[$i][transtime_id]              = $Rs['transtime_id'];
	$HomeTimeType[$i][transtime_name]            = $Rs['transtime_name'];
	$HomeTimeType[$i][transtime64encode_name]    = base64_encode($Rs['transtime_name']);
	$HomeTimeType[$i][transtime64decode_name]    = $Rs['transtime_name'];

	$i++;
}
$tpl->assign("HomeTimeType",  $HomeTimeType);

/**
金流
**/
$i = 0;
$i3 = 0;
$i6 = 0;
$key_value = explode("P",$_GET['key']);
$Query = $DB->query("select * from `{$INFO[DBPrefix]}transportation` where transport_id=".intval($cart->transname_id)." limit 0,1");
$Num   = $DB->num_rows($Query);
if ($Num>0){
	$Result= $DB->fetch_array($Query);
	$payment   =  $Result['payment'];
}
if ($payment!="")
	$sub_sql = " and p.mid in (" . $payment . ")";
if($ifhaveappoint==1){
	$sub_sql = " and p.ifcanappoint=1";	
}
$month_array = array();
if($ismonth[3]==1){
	$month_array[count($month_array)] = "  p.month=3";	
}
if($ismonth[6]==1){
	$month_array[count($month_array)] = "  p.month=6";	
}
if($ismonth[12]==1){
	$month_array[count($month_array)] = "  p.month=12";	
}
if(count($month_array)>=1)
	$sub_sql .= " and (" . implode(" or ",$month_array) . " or p.month=0)";
else
	$sub_sql .= " and p.month=0";
$paySql = "select *,p.content as pcontent,p.month as pmonth from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pm on p.pid=pm.pid where p.ifopen=1 and p.showtype like '%1%' " . $sub_sql . " order by p.orderby,pm.paytype desc,p.mid";
$payQuery    = $DB->query($paySql);
$payNum      = $DB->num_rows($payQuery);
if($payNum>0){
	while($payRs = $DB->fetch_array($payQuery)){
			if ($i == 0)
				$payArray[$i]['checked'] = 1;
			$payArray[$i]['methodname'] = $payRs['methodname'];
			$payArray[$i]['mid'] = $payRs['mid'];
			$payArray[$i]['pcontent'] = $payRs['pcontent'];
			$payArray[$i]['payname'] = $payRs['payname'];
			$payArray[$i]['month'] = $payRs['pmonth'];
			if($payRs['pmonth']>0)
				$payArray[$i]['price'] =  round(($Cart_discount_totalPrices+$Cart_transmoney)/$payRs['pmonth'],0);
			$i++;
		
		
	}
}

//print_r($payArray);
$tpl->assign("payArray",  $payArray);
$tpl->assign("payArray3",  $payArray3);
$tpl->assign("payArray6",  $payArray6);
$tpl->assign("PleaseInputEmail",      $PleaseInputEmail); //请输入E-MAIL地址！
$tpl->assign("Nocarriage",            $Nocarriage);
$tpl->assign("PleaseInputTrueEmail",  $Cart[PleaseInputTrueEmail]); //必须输入有效的E-MAIL地址！
$tpl->assign("PleaseInputName",       $Cart[PleaseInputName]); //请输入收货人姓名
$tpl->assign("PleaseInputAddr",       $Cart[PleaseInputAddr]); //请输入收货人地址



/*滿額禮*/
$key_value = explode("_",$_GET['key']);
//額滿禮
$goods_sql = "select * from `{$INFO[DBPrefix]}goods` as g where g.ifpub='1' and g.ifjs!=1  and g.ifbonus!='1' and g.ifpresent=1 and g.storage>0 having g.present_endmoney>='" . $Cart_discount_totalPrices . "' and g.present_money<='" . $Cart_discount_totalPrices . "' and g.present_money<(" . $Cart_discount_totalPrices . "+1000)";
$goods_sql .= " and g.ttype='" . $key_value[2] . "' ";
	
if (intval($key_value[1])>0){
	$goods_sql .= " and g.provider_id=".intval($key_value[1]) . " and g.iftogether=0";	
}else{
	$goods_sql .= " and (g.provider_id=0 or g.iftogether=1)";		
}
 $goods_sql .= " order by present_money desc,g.idate desc";
$goods_Query    = $DB->query($goods_sql);
$j = 0;
while ($goods_Rs=$DB->fetch_array($goods_Query)) {
	$em_goods_array[$j]['goodsname'] = $goods_Rs['goodsname'];
	$em_goods_array[$j]['gid'] = $goods_Rs['gid'];
	$em_goods_array[$j]['present_money'] = $goods_Rs['present_money'];
	$em_goods_array[$j]['present_endmoney'] = $goods_Rs['present_endmoney'];
	if ($goods_Rs['present_money'] - $Cart_discount_totalPrices > 0)
		$em_goods_array[$j]['have_present_money'] = $goods_Rs['present_money'] - $Cart_discount_totalPrices;
	else 
		$em_goods_array[$j]['have_present_money'] = 0;
	$em_goods_array[$j]['smallimg'] = $goods_Rs['smallimg'];
	$j++;
}
$tpl->assign("em_goods_array",     $em_goods_array);
/*滿額禮*/


/**
 * 这里是得到会员的基本资料
 */
if (intval($_SESSION['user_id'])>0){
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}user` where user_id=".intval($_SESSION['user_id'])." limit 0,1 ");
	$Num   =  $DB->num_rows($Query);
	if ( $Num > 0 ){
		$Rs  = $DB->fetch_array($Query);
		$email       = $Rs['email'];
		$tel         = $Rs['tel'];
		$post        = $Rs['post'];
		$city        = $Rs['city'];
		$canton      = $Rs['canton'];
		$Country      = $Rs['Country'];
		$addr        = $Rs['addr'];
		$true_name   = $Rs['true_name'];
		$other_tel   = $Rs['other_tel'];
		$born_date   = $Rs['born_date'];
		$mybuypoint = $FUNCTIONS->Buypoint(intval($Rs['user_id']),2);
	}
}
//紅利折抵
$Query = $DB->query("select * from `{$INFO[DBPrefix]}bonus`");
$Result= $DB->fetch_array($Query);
$rebate  =  $Result['rebate'];//最多折抵金額百分比
$point =$FUNCTIONS->Userpoint(intval($_SESSION['user_id']),1);
//最多可以使用的折抵紅利點數
$MaxbonusPoint = round($Cart_totalPrices * $rebate/100,0)>$point?$point:round($Cart_totalPrices * $rebate/100,0);
//折價券
$Sql = "select *,sum(ut.count) as count from `{$INFO[DBPrefix]}userticket` as ut inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid where ut.count>0 and ut.userid=".intval($_SESSION['user_id'])." and ( t.use_starttime<='" . date('Y-m-d',time()) . "' and t.use_endtime>='" . date('Y-m-d',time()) . "') and t.ordertotal<='" . $Cart_discount_totalPrices . "'  group by ut.ticketid";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
$i = 0;
if ($Num>0){
	while ($Rs = $DB->fetch_array($Query)) {
		$TicketList[$i]['tickeid'] = $Rs['ticketid'];
		$TicketList[$i]['id'] = $Rs['id'];
		$TicketList[$i]['count']   = intval($Rs['count']);
		$TicketList[$i]['money']   = intval($Rs['money']);
		$TicketList[$i]['ticketname']     = $Rs['ticketname'];
		$i++;
	}
}
if(intval($_SESSION['user_id'])>0){
	$Sql = "select t.*,ut.ticketcode from `{$INFO[DBPrefix]}ticketcode` as ut inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid where  ut.ownid=".intval($_SESSION['user_id'])." and ( t.use_starttime<='" . date('Y-m-d',time()) . "' and t.use_endtime>='" . date('Y-m-d',time()) . "') and t.ordertotal<='" . $Cart_discount_totalPrices . "' and ut.userid=0 and ut.ownid='" . intval($_SESSION['user_id']) . "' and t.canmove=1";
	$Query =  $DB->query($Sql);
	$Num   =  $DB->num_rows($Query);
	if ($Num>0){
		while ($Rs = $DB->fetch_array($Query)) {
			$TicketList[$i]['tickeid'] = $Rs['ticketid'];
			$TicketList[$i]['id'] = $Rs['id'];
			$TicketList[$i]['count']   = intval($Rs['count']);
			$TicketList[$i]['money']   = intval($Rs['money']);
			$TicketList[$i]['ticketcode']   = ($Rs['ticketcode']);
			$TicketList[$i]['ticketname']     = $Rs['ticketname'] . "[" . $Rs['ticketcode'] . "]";
			$i++;
		}
	}
}
$tpl->assign("TicketList",         $TicketList);
$tpl->assign("Cart_tickets",       $cart->tickets);
$tpl->assign("MaxbonusPoint",      $MaxbonusPoint);
$tpl->assign("mybuypoint",         intval($mybuypoint));
$tpl->assign("Cart_buypoint",         $Cart_buypoint);
$tpl->assign("ismonth3",         $ismonth[3]);
$tpl->assign("ismonth6",         $ismonth[6]);
$tpl->assign("email",         $email);
$tpl->assign("tel",           $tel);
$tpl->assign("city",          $city);
$tpl->assign("canton",        $canton);
$tpl->assign("Country",        $Country);
$tpl->assign("post",          $post);
$tpl->assign("addr",          $addr);
$tpl->assign("true_name",     $true_name);
$tpl->assign("session_uid",     intval($_SESSION['user_id']));
$tpl->assign("ifmonth",     $ifmonth);
$tpl->assign("other_tel",     $other_tel);
$tpl->assign("born_date",     $born_date);
$tpl->assign("Cart_combipoint",   $Cart_combipoint);
$tpl->assign("Cart_item",          $Cart_item);
$tpl->assign("saleoffinfo",     $cart->saleoffinfo);
$tpl->assign("Cart_totalPrices",   $Cart_totalPrices);
$tpl->assign("Cart_tickets",       $Cart_tickets);
$tpl->assign("Cart_bonus",         $Cart_bonus);
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
$tpl->assign("transname",  $cart->transname);
$tpl->assign("cvsname",  $cart->cvsname);
$tpl->assign("transname_area",  urlencode($cart->transname_area));
$tpl->assign("transname_area2",  urlencode($cart->transname_area2));
// 電子發票
if( $INFO['mod.einvoice.enable'] == "yes" )
{
	$charity = new Charity();
	$charity_options = $charity->print_options();
	$tpl->assign("invoice_donate_list", $charity_options );
}
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Basic_Command);
if (substr($_GET['key'],0,2)=="FB")
$tpl->display("shopping3_fb.html");
else
$tpl->display("shopping3.html");
?>
