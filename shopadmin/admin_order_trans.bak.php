<?php
include_once "Check_Admin.php";
include_once 'crypt.class.php';
include "../language/".$INFO['IS']."/Admin_sys_Pack.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/Cart.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";

/**
 * 判断是传进来的是字符串还是数字
 */

//print_r($_SESSION[Order_cid]);

if (is_array($_GET[cid])){
	$_SESSION[Order_cid] = $_GET[cid];
	$OrderId_Array	= $_SESSION[Order_cid];
	$Order_id      = intval($OrderId_Array[0]);
}else{
	$Order_id        = intval($FUNCTIONS->Value_Manage($_GET['order_id'],$_POST['order_id'],'back',''));
	$Sql      = "select order_id  from  `{$INFO[DBPrefix]}order_table`  where order_state=3 or  order_state=2 order by order_id desc ";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);

	if ($Num>0){
		$i = 0;
		$OrderId_Array =  array();
		while ($Rs = $DB->fetch_array($Query)){
			$OrderId_Array[$i] = $Rs[order_id];
			$i++;
		}
	}
}

if (!empty($_SESSION[Order_cid])){
	$OrderId_Array	= $_SESSION[Order_cid];
}

$TotalNum = count($OrderId_Array);

//$Query_first = $DB->query(" select order_state from `{$INFO[DBPrefix]}order_table` where order_id=".intval($Order_id)." limit 0,1");

$Query_first = $DB->query(" select ot.*,ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_id=".intval($Order_id)." limit 0,1");
$Num_first   = $DB->num_rows($Query_first);
if ($Num_first>0){
	$Result_first       = $DB->fetch_array($Query_first);
	$Order_state_first  = $Result_first[order_state];
	$Transtime_name     = $Result_first[transtime_name];
}else{
	$FUNCTIONS->sorry_back('back','');
}

$Sql      = "select * from `{$INFO[DBPrefix]}order_detail` od inner join `{$INFO[DBPrefix]}order_table` ot  on (od.order_id=ot.order_id)  where ot.order_id=".$Order_id." order by  od.order_detail_id desc ";


$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$Nums     = $Num ;
}else{
	$FUNCTIONS->sorry_back('back','');
}


?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $Order_Pack[Order_Form];?></title>
<style type="text/css">
<!--
.STYLE2 {font-size: 12px}
.STYLE3 {
	font-size: 24px;
	font-weight: bold;
}
.style5 {font-size: 15px}
.style6 {font-family: Wingdings}
.style7 {font-size: 9px}
-->
</style>
</head>

<body>
<table width="636" border="0" align="center" cellpadding="0" cellspacing="0"  class="STYLE2">
  <tr>
    <td align="center" class="STYLE3">出貨明細表</td>
  </tr>
  <tr>
    <td align="right">出貨日期：<?php echo date("Y-m-d",time());?></td>
  </tr>
  <tr>
    <td><hr></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="STYLE2">
      <tr>
        <td width="13%">收 貨 人：</td>
        <td width="87%">&nbsp;<?php echo $Result_first['receiver_name'];?></td>
      </tr>
      <tr>
        <td>聯絡電話：</td>
        <td>&nbsp;<?php echo MD5Crypt::Decrypt ( trim($Result_first['receiver_tele']), $INFO['tcrypt']) . " " . MD5Crypt::Decrypt ( trim($Result_first['receiver_mobile']), $INFO['mcrypt']);?></td>
      </tr>
      <tr>
        <td>收件地址：</td>
        <td>&nbsp;<?php echo $Result_first['receiver_address'];?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><hr></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>訂單的明細：</td>
  </tr>
  <tr>
    <td><hr></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="STYLE2">
      <tr>
        <td width="15%" align="center"><strong>訂單日期</strong></td>
        <td width="18%" align="center"><strong>訂單編號</strong></td>
        <td width="52%"><strong>商品名稱</strong></td>
        <td width="15%" align="center"><strong>數量</strong></td>
      </tr>
      <tr>
    <td colspan="4"><hr></td>
  </tr>
      <?php               
  					$i=0;
					$totalcount = 0;
  					while ($Rs=$DB->fetch_array($Query)) {

  						$Order_serial = $Rs['order_serial'];
  						$Bn           = $Rs['bn'];
  						$User_id      = $Rs['user_id'];
  						$Order_Time   = date("Y-m-d",$Rs['createtime']);
						if ($Rs['sendtime']!="")
  							$Send_Time    = date("Y-m-d H: i a ",$Rs['sendtime']);
  						$Paymentname  = $Rs['paymentname'];
  						$Deliveryname = $Rs['deliveryname'];
  						$Totalprice   = $Rs['totalprice'];
  						$Transport_price = $Rs['transport_price'];



  						switch (intval($Rs['ifinvoice'])){
  							case 0:
  								$Ifinvoice   = $Cart[Two_piao];
  								$Invoiceform = $Basic_Command['Null'];
  								$TheOneNum   = $Basic_Command['Null'];
  								break;
  							case 1:
  								$Ifinvoice   =  $Cart[Three_piao];
  								$Invoiceform =  trim($Result['invoiceform']);
  								$TheOneNum   =  "<font color=red>".trim($Result['invoice_num'])."</font>";

  								break;
  							case 2:
  								$Ifinvoice   = $Basic_Command['Null'];
  								$Invoiceform = $Basic_Command['Null'];
  								$TheOneNum   = $Basic_Command['Null'];
  								break;
  						}




  						$Order_state      = $Rs['order_state'];
  						$Pay_state        = $Rs['pay_state'];
  						$Receiver_name    = $Rs['receiver_name'];
  						$ATM              = trim($Rs['atm'])!="" ? trim($Rs['atm']) : $Basic_Command['Null'] ;
  						$Receiver_address = $Rs['receiver_address'];
  						$Receiver_email   = $Rs['receiver_email'];
  						$Receiver_post    = $Rs['receiver_post'];
  						$Receiver_tele    = $Rs['receiver_tele'];
  						$Receiver_mobile  = $Rs['receiver_mobile'];
  						$Receiver_memo    = nl2br($Rs['receiver_memo']);
  						$Detail_order_state =  $Rs['detail_order_state']==3  ? "√" : "Χ" ;
						if ($Rs[ticketmoney] > 0){
							$tickets = "[折價后價格：" . $Rs[ticketmoney] . "]";
							$tickets2 = "[折價后價格：" . ($Rs[ticketmoney]+$Rs[transport_price]) . "]";
						}
						$discount_totalPrices  = $Rs['discount_totalPrices'];
						$totalcount += $Rs['goodscount'];

					?>
      <tr>
        <td align="center" bgcolor="#CCCCCC"><?php echo $Order_Time;?></td>
        <td align="center" bgcolor="#CCCCCC"><?php echo $Order_serial;?></td>
        <td bgcolor="#CCCCCC"><?php echo $Rs['goodsname']?></td>
        <td align="center" bgcolor="#CCCCCC"><?php echo $Rs['goodscount']?></td>
      </tr>
      <?php
					}
	  ?>
    </table></td>
  </tr>
  <tr>
    <td align="right">總件數：<?php echo $totalcount;?></td>
  </tr>
  <tr>
    <td><hr></td>
  </tr>
  <tr>
    <td><p>&nbsp;</p>
      <p>親愛的客戶您好： </p>
      <p>非常感謝您對U-TV金連網Live即時影音購物商城的支持與愛護！ <br>
        1. 為了保障您的權益，即日起您享有商品七天鑑賞期，鑑賞期間內享有退/換貨權益，但商品必須全新狀態且完整包裝（含原包裝、贈品及配件），若有違反恕不接受退換貨。 <br>
        2. 貨到24小時內，請您確認所訂商品是否有短缺情形，如短缺，請務必至U-TV金連網Live即時影音購物商城線上客服諮詢，以保障您的訂購權益。 <br>
        <br>
        提醒您： <br>
      U-TV金連網Live即時影音購物商城對於「客戶權益」與「消費資料」絕對列為機密保護，若接不明電話，請拒絕答覆並掛斷電話，您可以於線上客服諮詢反應，或於上班時間撥打客服電話02-27416166或U-Phone客服電話 779988免費查證，我們會有專人為您服務，請您放心繼續支持U-TV金連網Live即時影音購物商城購物！ </p>
      <p>祝 購物愉快 </p>
      <p>U-TV金連網Live即時影音購物商城 敬上 <br>
    www.u-tv.com.tw </p></td>
  </tr>
</table>

</body>
</html>
