<?php
include_once "Check_Admin.php";

include "../language/".$INFO['IS']."/Admin_sys_Pack.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/Cart.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";
include_once 'crypt.class.php';
/**
 * 判断是传进来的是字符串还是数字
 */

//print_r($_SESSION[Order_cid]);
/*
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
*/

?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>揀貨單明細</title>
<script language="javascript" src="../js/LodopFuncs.js"></script>
<LINK href="../css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet><!--font icon-->
<LINK href="../css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet><!--font icon-->

</head>

<body>
<!--<div style="font-size:15px;"><i class="icon-print"></i> <a href="javascript:prn1_preview();">批次列印</a></div>-->

<?php
if($_GET['order_id']>0)
	$_GET['cid'][0] = $_GET['order_id'];
$pagnum = count($_GET[cid]);
foreach($_GET[cid] as $k=>$Order_id){
	$Query_first = $DB->query(" select * from `{$INFO[DBPrefix]}order_table` where order_id=".intval($Order_id)." limit 0,1");
	$Result_first       = $DB->fetch_array($Query_first);
	$order_serial_together = $Result_first['order_serial_together'];


//$Query_first = $DB->query(" select order_state from `{$INFO[DBPrefix]}order_table` where order_id=".intval($Order_id)." limit 0,1");

	$Query_first = $DB->query(" select ot.*,ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_id=".intval($Order_id)." limit 0,1");
	$Num_first   = $DB->num_rows($Query_first);
	if ($Num_first>0){
		$Result_first       = $DB->fetch_array($Query_first);
		$Order_state_first  = $Result_first[order_state];
		$Transtime_name     = $Result_first[transtime_name];
		$transtype     = $Result_first[transtype];
		$Rs = $Result_first;
		$Order_serial = $Rs['order_serial'];
  						$User_id      = $Rs['user_id'];
  						$Order_Time   = date("Y-m-d H: i a ",$Rs['createtime']);
						if ($Rs['sendtime']!="")
  							$Send_Time    = date("Y-m-d H: i a ",$Rs['sendtime']);
  						$Paymentname  = $Rs['paymentname'];
  						$Deliveryname = $Rs['deliveryname'];
  						$Totalprice   = round($Rs['totalprice'],0);
  						$Transport_price = $Rs['transport_price'];
						$tickets  = $Rs['ticketmoney'];
						$ticket_discount_money  = $Rs['ticket_discount_money'];
						$bonuspoint  = $Rs['bonuspoint'];



  						switch (intval($Rs['ifinvoice'])){
  							case 0:
  								$Ifinvoice   = $Cart[Two_piao];
  								$Invoiceform = $Basic_Command['Null'];
  								$TheOneNum   = $Basic_Command['Null'];
  								break;
  							case 1:
  								$Ifinvoice   =  $Cart[Three_piao];
  								$Invoiceform =  trim($Rs['invoiceform']);
  								$TheOneNum   =  "<font color=red>".trim($Rs['invoice_num'])."</font>";

  								break;
  							case 2:
  								$Ifinvoice   = $Basic_Command['Null'];
  								$Invoiceform = $Basic_Command['Null'];
  								$TheOneNum   = $Basic_Command['Null'];
  								break;
							case 3:
								$Ifinvoice   =  "捐贈華民國全球元母大慈協會";
								$Invoiceform =  $Basic_Command['Null'];
								$TheOneNum   =  $Basic_Command['Null'];

								break;
  						}




  						$Order_state      = $Rs['order_state'];
  						$Pay_state        = $Rs['pay_state'];
  						$Receiver_name    = $Rs['receiver_name'];
  						$ATM              = trim($Rs['atm'])!="" ? trim($Rs['atm']) : $Basic_Command['Null'] ;
  						$Receiver_address = $Rs['receiver_address'];
  						$Receiver_email   = $Rs['receiver_email'];
  						$Receiver_post    = $Rs['receiver_post'];
  						$Receiver_tele    = MD5Crypt::Decrypt ( trim($Rs['receiver_tele']), $INFO['tcrypt']);
  						$Receiver_mobile  = MD5Crypt::Decrypt ( trim($Rs['receiver_mobile']), $INFO['mcrypt']);
  						$Receiver_memo    = nl2br($Rs['receiver_memo']);
						$piaocode    = nl2br($Rs['piaocode']);
						$flightstyle    = ($Rs['flightstyle']);
						$flightid    = ($Rs['flightid']);
						$flightno    = ($Rs['flightno']);
						$flightdate    = ($Rs['flightdate']);
						$Departure    = ($Rs['Departure']);
						$bonuspoint = $Rs[bonuspoint]; //折抵
						$totalbonuspoint = $Rs[totalbonuspoint]; //兑换
						if ($Rs[ticketmoney] > 0){
							$tickets = $Rs[ticketmoney];
							$tickets2 = "[折價后價格：" . ($Rs[ticketmoney]+$Rs[transport_price]) . "]";
						}
						$discount_totalPrices  = $Rs['discount_totalPrices'];
	}

//$Sql      = "select * from `{$INFO[DBPrefix]}order_detail` od where od.order_id=".$Order_id." order by  od.order_detail_id desc ";
	if($order_serial_together!="")
		$Sql      = "select * from `{$INFO[DBPrefix]}order_detail` od inner join `{$INFO[DBPrefix]}order_table` ot  on (od.order_id=ot.order_id)  where ot.order_serial_together='".$order_serial_together."' order by  od.order_detail_id asc ";
	else
		$Sql      = "select * from `{$INFO[DBPrefix]}order_detail` od inner join `{$INFO[DBPrefix]}order_table` ot  on (od.order_id=ot.order_id)  where ot.order_id=".$Order_id." order by  od.order_detail_id asc ";


	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	if ($Num>0){
		$Nums     = $Num ;
	}else{
		$FUNCTIONS->sorry_back('back','');
	}
	
	$Query_user = $DB->query(" select * from `{$INFO[DBPrefix]}user` where user_id=".intval($User_id)." limit 0,1 ");
	$Num_user   = $DB->num_rows($Query_user);
	if ($Num_user>0){
		$Result_user= $DB->fetch_array($Query_user);
		$True_name = $Result_user['true_name'];
		$Tel = MD5Crypt::Decrypt ( trim($Result_user['tel']), $INFO['tcrypt']);
		$other_tel = MD5Crypt::Decrypt ( trim($Result_user['other_tel']), $INFO['mcrypt']);
		$certcode = $Result_user['certcode'];
	}
?>
<div id="ordershow<?php echo $Order_id;?>">
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
@media print {
    p{page-break-after: always;}
}
</style>
<table width="620" border="0" align="center" cellpadding="0" cellspacing="0" class="STYLE2">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="STYLE2">
      <tr>
          <td align="center" class="STYLE3">揀貨單明細</td>
      </tr>
      <tr>
          <td align="right">列印日期：<?php echo date("Y-m-d",time());?></td>
      </tr>
    </table>
<hr noshade="noshade" style="border: 0px currentColor; border-image: none; height: 1px; background-color:#000000;">
<br>
<table width="620" border="0" cellspacing="0" cellpadding="2" class="STYLE2">
  <tr>
    <td width="50%"><?php echo $Cart[Order_serial_say];?>： <?php echo $Order_serial?></td>
    <td width="50%">訂單日期：<?php echo $Order_Time?></td>
  </tr>
  <tr>
    <td><?php echo $Cart[ShopMemberName];?>：<?php echo $True_name;?>  <?php echo $Result_user['en_firstname']?> <?php echo $Result_user['en_secondname'];?><!--訂購人姓名--></td>
    <td colspan="2">取貨人聯絡電話：<?php echo $Tel;?> / <?php echo $other_tel;?></td>
  </tr>
  <tr>
    <td width="50%">班機號碼： <?php echo $flightid;?> <?php echo $flightno;?></td>
    <td width="50%">目的地：<?php echo $Departure;?></td>
  </tr>
  <tr>
    <td width="50%">直飛/轉機 ： <?php echo $flightstyle == "direct"?"直飛":"轉機";?></td>
    <td width="50%">班機時間： <?php echo $flightdate;?></td>
  </tr>
  <tr>
    <td width="50%">護照號碼 ： <?php echo $certcode;?></td>
    <td width="50%"></td>
  </tr>
       
  <!--  <tr>
    <td width="50%">英文名 ： <?php echo $Result_user['en_firstname']?> <?php echo $Result_user['en_secondname'];?></td>
    <td width="50%"></td>
  </tr>
  <tr>
    <td width="50%">中文名 ： <?php echo $Result_user['true_name']?> <?php echo $Result_user['cn_secondname']?></td>
    <td width="50%"></td>
  </tr>
  
  
  
  

  <tr>
    <td><?php echo $Cart[name_say];?>： <?php echo $Receiver_name;?></td>
    <td colspan="2">收貨人聯絡電話：<?php echo $Receiver_tele?> / <?php echo $Receiver_mobile?></td>
  </tr>  
  <tr>
    <td colspan="3" class="STYLE2"><?php echo $Cart[addr_say];?>： <?php echo $Receiver_post?><?php echo $Receiver_address ;?></td>
    </tr>
  <tr>  
    <td><?php echo $Cart[HomeSend_TimeType] ;?>：<?php echo $Result_first['senddate'];?> / <?php echo $Transtime_name?></td>
  </tr>
  
  <tr>
    <td colspan="2"><?php echo $Cart[Need_invoice_say];?><!--發票格式--：<?php echo $Ifinvoice?> ( <?php echo $Cart[Invoice_num_say];?><!--統一編號--：<?php echo $TheOneNum?>　<?php echo $Cart[Top_invoice_say];?><!--發票抬頭--：<?php echo $Invoiceform?> )</td>
  </tr>-->
</table>
<!--<table width="100%" border="0" cellspacing="0" cellpadding="2" class="STYLE2">
  <tr>
    <td width="12%"  valign="top">會員備註：</td>
    <td width="88%"  valign="top"><?php echo $Receiver_memo?></td>
  </tr>
</table>-->
<br>
<hr>
<br>
<div>網路訂單商品</div>
<br>
<table width="620" border="1" cellspacing="0" cellpadding="1" bordercolor="#333333" class="STYLE2">
  <tr>
    <td width="120" height="25" align="center">貨號</td>
    <td width="250" align="center"><?php echo $Cart[ProductName_say] ;?><!--商品名稱--></td>
    <td width="60" align="center">顏色</td>
    <td width="60" align="center">尺寸</td>
    <td width="60" align="center"><?php echo $Cart[ProductNum_say] ;?><!--數量--></td>
    <td width="70" align="center"><?php echo $Cart[MemPrice_say] ;?></td>
    </tr>
  <?php               
  					$i=0;
					$gcount = 0;
  					while ($Rs=$DB->fetch_array($Query)) {
$Detail_order_state =  $Rs['detail_order_state']==3  ? "√" : "Χ" ;
  						

					?>
  <tr>
    <td style="padding-left:5px;"><?php echo $Rs['bn']?></td>
    <td style="padding-left:5px;"><?php echo $Rs['goodsname'] . $Rs['detail_name']?>
    </td>
    <td align="center"><?php echo $Rs['good_color']?></td>
    <td align="center"><?php echo $Rs['good_size']?></td>
    <td align="center"><?php if($Rs['packgid']>0 || ($Rs['packgid']==0 && $Rs['ifpack']==0) ){echo $Rs['goodscount'];}?></td>
    <td align="center"><?php if($Rs['packgid']==0 ){echo "$".round($Rs['price'],0);}?>&nbsp;</td>
    </tr>
   <?
  if($Rs['packgid']>0 || ($Rs['packgid']==0 && $Rs['ifpack']==0) ){ $gcount+=$Rs['goodscount'];}
                  $i++;
  					}
                  ?>
</table><table width="100%" border="0" cellspacing="0" cellpadding="0" class="STYLE2">
  <tr>
    <td width="84%" height="25" align="right">總件數： </td>
    <td width="16%" align="right">&nbsp;<?php echo $gcount;?>&nbsp;件</td>
  </tr>
  <tr>
    <td width="84%" height="25" align="right">訂單商品總金額： </td>
    <td width="16%" align="right">&nbsp;$&nbsp;<?php echo $Totalprice?>&nbsp;</td>
  </tr> 

</table>

<br><br>

<div>追蹤清單酒類商品 <span style="color: #f00;">(酒類商品依法令規定不可在線上銷售，故揀貨時請另外提醒該會員是否一起取貨)</span></div>
<br>
<table width="620" border="1" cellspacing="0" cellpadding="1" bordercolor="#333333" class="STYLE2">
  <tr>
    <td width="120" height="25" align="center">貨號</td>
    <td width="250" align="center"><?php echo $Cart[ProductName_say] ;?><!--商品名稱--></td>
    <td width="60" align="center">顏色</td>
    <td width="60" align="center">尺寸</td>
    <td width="60" align="center"><?php echo $Cart[ProductNum_say] ;?><!--數量--></td>
    <td width="70" align="center"><?php echo $Cart[MemPrice_say] ;?></td>
    </tr>
<?php
$Sql = "select g.smallimg,g.goodsname,g.intro,g.price,g.pricedesc,g.gid,c.collection_id,g.bn from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid)  inner join  `{$INFO[DBPrefix]}collection_goods` c on (c.gid=g.gid)  where  b.catiffb=1 and g.ifpub=1 and c.user_id='".intval($User_id)."' and g.extendbid like '%\"6\"%' order by c.cidate desc ";

$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);	
	$winetotal = 0;
while ($Rs=$DB->fetch_array($Query)) {
?>
  <tr>
    <td style="padding-left:5px;"><?php echo $Rs['bn']?></td>
    <td style="padding-left:5px;"><?php echo $Rs['goodsname']?></td>
    <td align="center"></td>
    <td align="center"></td>
    <td align="center">1</td>
    <td align="center"><?php echo $Rs['pricedesc']?>&nbsp;</td>
    </tr>
<?php
	$winetotal+=$Rs['pricedesc'];
}
?>
</table><table width="100%" border="0" cellspacing="0" cellpadding="0" class="STYLE2">
  <tr>
    <td width="84%" height="25" align="right">總件數： </td>
    <td width="16%" align="right">&nbsp;<?php echo $Num;?>&nbsp;件</td>
  </tr>
  <tr>
    <td width="84%" height="25" align="right">酒類商品總金額： </td>
    <td width="16%" align="right">&nbsp;$&nbsp;<?php echo $winetotal;?>&nbsp;</td>
  </tr>
</table>
<hr>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="STYLE2">
  <?if ($ticket_discount_money > 0){?>
  <tr>
    <td width="84%" height="25" align="right">使用現金券： </td>
    <td width="16%" align="right">&nbsp;- $&nbsp;<?php echo $ticket_discount_money?>&nbsp;</td>
  </tr>
  <? } ?>  
  <?if ($bonuspoint > 0){?>
  <tr>
    <td width="84%" height="25" align="right">紅利折抵點數： </td>
    <td width="16%" align="right">&nbsp;- $&nbsp;<?php echo $bonuspoint?>&nbsp;</td>
  </tr>
  <? } ?>
  <tr style="color: #f00;font-size: 14px;">
    <td width="84%" height="25" align="right">網路訂單 & 酒類折扣後總金額： </td>
    <td width="16%" align="right">&nbsp;$&nbsp;<?php echo ($discount_totalPrices+$Transport_price+$winetotal)?>&nbsp;</td>
  </tr>
</table>



<span class="STYLE2">
<!--<?php echo $Cart[Product_totalprice_say];?>商品總金額 ：$&nbsp;<?php echo $Totalprice . $tickets?><br>-->
<!--<?php echo $Cart[send_money_say];?>配送費用 ：$&nbsp;<?php echo $Transport_price?><br>-->
<!--<?php echo $Cart[TotalPrice_say]?>訂單總金額 ：$&nbsp;<?php echo ($discount_totalPrices+$Transport_price)?><br><br>-->
<!--訂單 & 酒類總金額：$&nbsp;<?php echo ($discount_totalPrices+$Transport_price+2450)?><br><br>-->
<!--應收帳款：<?php echo $Paymentname;?>&nbsp;$&nbsp;<?php echo ($discount_totalPrices+$Transport_price)?>--></span>
<span class="STYLE2"><br>
<!--宅配單號： <?php echo $piaocode;?></span>-->
<hr>
<br>
<span class="STYLE2">注意事項： <br>
  1.　缺貨或訂單不明請先洽直屬主管，主管將與網購部聯繫。<br>
  2.　消費者個資請注意不能外洩，訂單如需廢棄請用碎紙機碾碎處理。<br>
<!--  3.　宅配單收據可填寫編號或黏貼於此單空白處，以利追蹤查詢。--></span>
<br>
<div>揀貨人員備註：</div>
</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?php
if($pagnum-1>$k){
?>
<p style="page-break-after:always"></p>
<?php
}
?>
</div>
<br>
<?php
}
?>

<script language="javascript" type="text/javascript">   
        var LODOP; //声明为全局变量 
	       
	function prn3_preview(){
		LODOP=getLodop();  
		LODOP.PRINT_INIT("出貨單");
		LODOP.ADD_PRINT_HTM(0,0,"100%","100%",document.getElementById("ordershow").innerHTML);
		LODOP.PREVIEW();	
	};	
	function prn1_preview() {	
	//alert("a");
		LODOP=getLodop();  
		LODOP.PRINT_INIT("出貨單");
		<?php
		$len = count($_GET[cid]);
		$i = 0;
		foreach($_GET[cid] as $k=>$Order_id){
		?>
		LODOP.ADD_PRINT_HTM(0,0,"100%","100%",document.getElementById("ordershow<?php echo $Order_id;?>").innerHTML);
		<?php
		if($len>$i+1){
		?>
		LODOP.NewPage();
		<?php
		}else{
		?>
		LODOP.PREVIEW();
		<?php
		}
			$i++;
		}
		?>
	};

</script> 


</body>
</html>
