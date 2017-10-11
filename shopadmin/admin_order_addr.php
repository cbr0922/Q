<?php
include "Check_Admin.php";
include_once 'crypt.class.php';
include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/Cart.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>

<style type="text/css">
<!--
td.title {border-width:1px;color:#666666;text-align:center;line-height:15px;padding-top:3px;
          background-image: url(/wms/hotel/images/tdbg.gif);
		  background-repeat: repeat-x repeat-y;}

.text { font-size:13px }
table.out { border-width:1px;border-style:dotted;border-color:#666666;}
.dotted {border-style:dotted;border-width:1px;b1worder-color:#999999}
.solid {border-style:solid;border-width:1px;border-color:#999999}
.sp_hideform { width:0px;height:0px;overflow:hidden }

a.btn:link    { font-size:10pt;color:#666666;border-style:solid;border-width:1px;border-color:#999999;background-image: url(/wms/hotel/images/btnbg.gif);text-decoration:none;width:90%;padding-top:3px}
a.btn:active  { font-size:10pt;color:#666666;border-style:solid;border-width:1px;border-color:#999999;background-image: url(/wms/hotel/images/btnbg.gif);text-decoration:none;width:90%;padding-top:3px}
a.btn:visited { font-size:10pt;color:#666666;border-style:solid;border-width:1px;border-color:#999999;background-image: url(/wms/hotel/images/btnbg.gif);text-decoration:none;width:90%;padding-top:3px}
a.btn:hover   { font-size:10pt;color:#666666;border-style:solid;border-width:1px;border-color:#999999;background-image: url(/wms/hotel/images/btnbg_f.gif);text-decoration:none;width:90%;padding-top:3px}

.ez8 { font-size:8pt; line-height:11pt; font-family:'helvetica,arial'; } 
.ez9 { font-size:9pt; line-height:12pt; font-family:'helvetica,arial'; }
.ez10 { font-size:10pt; line-height:13pt; font-family:'helvetica,arial'; }
.ez11 { font-size:11pt; line-height:14pt; font-family:'helvetica,arial'; } 
.ez12 { font-size:12pt; line-height:15pt; font-family:'helvetica,arial'; }
.ezhotel2 { color:#d20000}
.ezhotel3 { color:#001188}

a:link    { color:#3399ff;text-decoration:none}
a:active  { color:#3399ff;text-decoration:none}
a:visited { color:#3399ff;text-decoration:none}
a:hover   { color:#ff9900;text-decoration:none}
.cancelbutton { font-size:10pt;color:#ff0000;background-color:#fbd9d6;}

	div.remarktype1{height:100px; overflow-x:auto; overflow-y:scroll;cursor:hand};
	div.remarktype2{height:100px;cursor:hand};

-->
</style>

</head>
<body>
<script language="javascript" src="../js/CheckActivX.js"></script>

<object  id="LODOP" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> </object> 
<script language="javascript">CheckLodop();</script>
<script language="javascript" type="text/javascript">    
	function prn1_preview() {	
		CreateOneFormPage();	
		LODOP.PREVIEW();	
	};
	function prn1_print() {		
		CreateOneFormPage();
		LODOP.PRINT();	
	};
	
</script>

<a href="javascript:void(0);" onClick="javascript:prn1_preview()">列印預覽</a>,可<a href="javascript:void(0);" onClick="javascript:prn1_print()">直接列印</a>
<?php

include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;

$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : $TimeClass->ForGetDate("Month","-6","Y-m-d");
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : $TimeClass->ForGetDate("Day","1","Y-m-d");

switch ($INFO['admin_IS']){
	case "gb":
		$ToEncode = "GB2312";
		break;
	case "en":
		$ToEncode = "GB2312";
		break;
	case "big5":
		$ToEncode = "BIG5";
		break;
	default:
		$ToEncode = "GB2312";
		break;
}

if (intval($_GET['provider_id'])>0){
	$Query_Provider =  $DB->query("SELECT DISTINCT order_id FROM  `{$INFO[DBPrefix]}order_detail`   where  provider_id=".intval($_GET['provider_id']));
	$Num_Provider   =  $DB->num_rows($Query_Provider);
	if ($Num_Provider>0){
		$Provider_Search = " and ( ";
		while ($Rs_Provider   =  $DB->fetch_array($Query_Provider)){
			$Provider_Search .= "o.order_id='".intval($Rs_Provider[order_id])."'  or ";
		}

		$Provider_Search   = substr($Provider_Search,0,strlen($Provider_Search)-3);
		$Provider_Search  .= " )";
	}
}


$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-");

switch (trim($_GET['State'])){
	case "NoOp":
		$Add = " and o.order_state<=2 ";  // 这里设置为小于等于2，就是说包括未确定，已确定，部分发货三类内容
		$Join = " left join ";
		break;
	case "NoView":
		$Add = " and ou.userback_alread=0 ";
		$Join = " inner join ";
		break;
	case "AllCancel":
		$Add = " and o.order_state=5 ";  // 这里设置为5
		$Join = " left join ";
		$Topname = "[".$Order_Pack[OrderState_say_six]."]";//已取消
		break;
	case "AllPigeonhole":
		$Add = " and o.order_state=4 ";  // 这里设置为4
		$Join = " left join ";
		$Topname = "[".$Order_Pack[OrderState_say_five]."]";//已归档
		break;
	case "Noreplay":
		$Add = " and ou.sys_say='' ";
		$Join = " inner join ";
		break;
    default :
		$Join = " left join ";
		break;
}

if ($_GET['action']=='search') {
	if (trim(urldecode($_GET['skey']))!=$Basic_Command['InputKeyWord']   && trim($_GET['skey'])!=""){

		if (trim($_GET['type'])=='o.order_serial' || trim($_GET['type'])=='u.true_name' ){
			$Add_one = " and ".$_GET['type']."  like '%".trim(urldecode($_GET['skey']))."%' ";
		}else{
			$Add_one = " and ".$_GET['type']."'".trim(urldecode($_GET['skey']))."' ";
		}
	}

	if ($_GET['orderstate']!=''){
		$Add_two  =  " and  o.order_state=".intval($_GET['orderstate'])." ";
		switch (intval($_GET['orderstate'])){
			case 0:
				$Topname = "[".$Order_Pack[OrderPayState_say_one]."]" ;//未确认
				break;
			case 1:
				$Topname = "[".$Order_Pack[OrderState_say_two]."]";//已确认
				break;
			case 2:
				$Topname = "[".$Order_Pack[OrderState_say_three]."]";//部分发货
				break;
			case 3:
				$Topname = "[".$Order_Pack[OrderState_say_four]."]";//已发货
				break;
			case 4:
				$Topname = "[".$Order_Pack[OrderState_say_five]."]";//已归档
				break;
			case 5:
				$Topname = "[".$Order_Pack[OrderState_say_six]."]";//已取消
				break;
		}
	}

	if ($_GET['paystate']!=''){
		$Add_three  =  " and  o.pay_state=".intval($_GET['paystate'])." ";
	}
	if (intval($_GET['company'])>0){
		$Add_three  =  " and  u.companyid=".intval($_GET['company'])." ";
	}

	$Sql = " select  u.true_name,u.tel,u.post,u.addr,o.order_serial,o.order_id,o.createtime,o.totalprice,o.transport_price,
	         o.transport_price,o.deliveryname,o.paymentname,o.receiver_name,o.order_state,o.pay_state,o.receiver_post,o.receiver_tele,o.receiver_mobile,o.receiver_address,
	         ou.sys_say,ou.userback_type,ou.userback_alread  ,o.ticketmoney,o.discount_totalPrices
	         from `{$INFO[DBPrefix]}order_table` o  left  join  `{$INFO[DBPrefix]}user` u on (u.user_id=o.user_id)  
	         ".$Join." `{$INFO[DBPrefix]}order_userback` ou on (o.order_id=ou.order_id)  
	         where  o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' 
	         ".$Add_one."   ".$Add_two."   ".$Add_three."  ".$Add."  ".$Provider_Search." ";
	$Sql      .= "  order by o.createtime desc";


}else{
	//下边如果不参与查询的资料

	$Add  = $_GET['State']!=""  ? str_replace("and","where",$Add)." and o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' " :  " where o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' ";
	$Add  = $_GET[Order_Tracks]=="Show" ? $Add." and o.order007_status=1 and  o.order007_begtime <= '".date("Y-m-d",time())."' " : $Add ;
	$Sql  = " select u.true_name,u.tel,u.post,u.addr,o.order_serial,o.order_id,o.createtime,o.totalprice,o.transport_price,
	         o.transport_price,o.deliveryname,o.paymentname,o.receiver_name,o.order_state,o.pay_state,o.receiver_post,o.receiver_tele,o.receiver_mobile,o.receiver_address,
	         ou.sys_say,ou.userback_type,ou.userback_alread ,o.ticketmoney,o.discount_totalPrices 
	         from `{$INFO[DBPrefix]}order_table` o  left join  `{$INFO[DBPrefix]}user` u on (u.user_id=o.user_id)  
	         ".$Join." `{$INFO[DBPrefix]}order_userback` ou on (o.order_id=ou.order_id ) ".$Add."  ";
	$Sql      .= "  order by o.createtime desc";
}

// $Sql;
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$file_string .= "訂單編號,產品,件數,（寄）姓名,（寄）電話,（寄）郵編區號,（寄）地址,（收）姓名,（收）電話,（收）郵編區號,（收）地址,收款金額,付款方式,下單日期\n";
$i = 1;
$j = 0;
if ($Num > 0){

?>
<script language="javascript">
function CreateOneFormPage(){
		LODOP.PRINT_INIT("地址套印");
<?php
	while ($Rs = $DB->fetch_array($Query)){
		if ($i>3){
			$i = 1;
			$j++;
		}
?>
		LODOP.ADD_PRINT_TEXT(<?php echo $j*140+21;?>,<?php echo 30+($i-1)*255?>,200,25,"<?php echo $Rs['receiver_name']; ?>");
		LODOP.ADD_PRINT_TEXT(<?php echo $j*140+46;?>,<?php echo 30+($i-1)*255?>,200,50,"<?php echo $Rs['receiver_post'] . "  " . $Rs['receiver_address']; ?>");
		LODOP.ADD_PRINT_TEXT(<?php echo $j*140+86;?>,<?php echo 30+($i-1)*255?>,200,25,"<?php echo MD5Crypt::Decrypt ( trim($Rs['receiver_tele']), $INFO['tcrypt']) . "  " . MD5Crypt::Decrypt ( trim($Rs['receiver_mobile']), $INFO['mcrypt']); ?>");
<?php
$i++;
	}
	
}
?>
	};
	prn1_preview();
	history.back(-1);
</script>
</body>
</html>
