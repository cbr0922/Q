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

if (is_array($_GET[cid])){
	$_SESSION[Order_cid] = $_GET[cid];
	$OrderId_Array	= $_SESSION[Order_cid];
	$Order_id      = intval($OrderId_Array[0]);
}elseif (is_array($_POST[cid])){
	$_SESSION[Order_cid] = $_POST[cid];
	$OrderId_Array	= $_SESSION[Order_cid];
	$Order_id      = intval($OrderId_Array[0]);
}else{
	$Order_id        = intval($FUNCTIONS->Value_Manage($_POST['order_id'],$_POST['order_id'],'back',''));
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
 */
$TotalNum = count($OrderId_Array);

?>


<HTML  xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript" src="../js/LodopFuncs.js"></script>
<LINK href="../css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet><!--font icon-->
<LINK href="../css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet><!--font icon-->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $Order_Pack[Order_Form];?></title>

</head>

<body>
<!--<div style="font-size:15px;"><i class="icon-print"></i> <a href="javascript:prn1_preview()">批次列印</a></div>-->
<?php
//print_r($_POST[cid]);
$pagnum = count($_POST[cid]);
foreach($_POST[cid] as $k=>$Order_id){
	$Query_first = $DB->query(" select * from `{$INFO[DBPrefix]}order_table` where order_id=".intval($Order_id)." limit 0,1");
	$Result_first       = $DB->fetch_array($Query_first);
	$order_serial_together = $Result_first['order_serial_together'];
	
	$Query_first = $DB->query(" select ot.*,ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_id=".intval($Order_id)." limit 0,1");
	$Num_first   = $DB->num_rows($Query_first);
	if ($Num_first>0){
		$Result_first       = $DB->fetch_array($Query_first);
		$Order_state_first  = $Result_first[order_state];
		$Transtime_name     = $Result_first[transtime_name];
			$Rs = $Result_first;
		$Order_serial = $Rs['order_serial'];
							$User_id      = $Rs['user_id'];
							$Order_Time   = date("Y-m-d H: i a ",$Rs['createtime']);
	
	}
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

<table width="636" border="0" align="center" cellpadding="0" cellspacing="0"  class="STYLE2" >
  <tr>
    <td align="center" class="STYLE3">出貨單明細</td>
  </tr>
  <tr>
    <td align="right">列印日期：<?php echo date("Y-m-d",time());?></td>
  </tr>
  <tr>
    <td><hr></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="STYLE2">
      <tr>
        <td width="50%"><?php echo $Cart[Order_serial_say];?>： <?php echo $Order_serial?></td>
    <td width="50%">訂單日期：<?php echo $Order_Time?></td>
  </tr>
  <tr>
    <td><?php echo $Cart[ShopMemberName];?>：<?php echo $True_name;?> <?php echo $Result_user['en_firstname']?> <?php echo $Result_user['en_secondname'];?><!--訂購人姓名--></td>
    <td colspan="2">訂購人聯絡電話：<?php echo $Tel;?> / <?php echo $other_tel;?></td>
  </tr>
  <tr>
    <td width="50%">護照號碼 ： <?php echo $Result_user['certcode']?></td>
    <td width="50%"></td>
  </tr>
  <tr>
    <td><?php echo $Cart[name_say];?>： <?php echo $Result_first['receiver_name'];?></td>
    <td colspan="2">收貨人聯絡電話：<?php echo MD5Crypt::Decrypt ( trim($Result_first['receiver_tele']), $INFO['tcrypt']) . " / " . MD5Crypt::Decrypt ( trim($Result_first['receiver_mobile']), $INFO['mcrypt']);?></td>
      </tr>
      <tr>
        <td>收貨人地址：<?php echo $Rs['receiver_post'];?><?php echo $Result_first['receiver_address'];?></td>
        <td>&nbsp;</td>
      </tr>
      <?php
      if(intval($INFO['ifsenddate'])==1){
	  ?>
      <tr>
        <td>配送日期：<?php echo $Result_first['senddate'];?> <?php echo $Transtime_name;?></td>
        <td></td>
      </tr>
      <?php
	  }
	  ?>
    </table></td>
  </tr>
  <tr>
    <td><hr></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>訂單商品明細：</td>
  </tr>
  <tr>
    <td><hr></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="STYLE2">
      <tr>
        <td width="26%" height="25" style="padding-left:5px;"><strong>商品貨號</strong></td>
        <td width="30%" style="padding-left:5px;"><strong>商品名稱</strong></td>
        <td width="17%" align="center"><strong>顏色</strong></td>
        <td width="13%" align="center"><strong>尺寸</strong></td>
        <td width="14%" align="center"><strong>數量</strong></td>
      </tr>
      <tr>
    <td colspan="5"><hr></td>
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
  						$Receiver_tele    = MD5Crypt::Decrypt ( trim($Rs['receiver_tele']), $INFO['tcrypt']);
  						$Receiver_mobile  = MD5Crypt::Decrypt ( trim($Rs['receiver_mobile']), $INFO['mcrypt']);
  						$Receiver_memo    = nl2br($Rs['receiver_memo']);
  						$Detail_order_state =  $Rs['detail_order_state']==3  ? "√" : "Χ" ;
						if ($Rs[ticketmoney] > 0){
							$tickets = "[折價后價格：" . $Rs[ticketmoney] . "]";
							$tickets2 = "[折價后價格：" . ($Rs[ticketmoney]+$Rs[transport_price]) . "]";
						}
						$discount_totalPrices  = $Rs['discount_totalPrices'];
						if($Rs['ifpack']==0)
							$totalcount += $Rs['goodscount'];
	
					?>
      <tr>
        <td style="padding-left:5px;"><?php echo $Bn;?>&nbsp;</td>
        <td style="padding-left:5px;"><?php echo $Rs['goodsname'] . $Rs['detail_name']?><?php if($Rs['packgid']>0) echo "[組合商品]";?></td>
        <td align="center"><?php echo $Rs['good_color']?>&nbsp;</td>
        <td align="center"><?php echo $Rs['good_size']?>&nbsp;</td>
        <td align="center"><?php echo $Rs['goodscount']?></td>
      </tr>
      <?php
					}
	  ?>
    </table></td>
  </tr>
    <tr>
    <td><hr></td>
  </tr>
  <tr>
    <td align="right">總件數：<?php echo $totalcount;?></td>
  </tr>
  <tr>
    <td><hr></td>
  </tr>
  <tr>
    <td>
	<?php 
	$Sql      = "select * from `{$INFO[DBPrefix]}admin_info` where info_id=12 order by info_id  ";
	$Query    = $DB->query($Sql);
	$Rs=$DB->fetch_array($Query);
	echo $Rs['info_content'];
	?>

    </td>
  </tr>
</table>

</div>
<br>
<?php
if($pagnum-1>$k){
?>
<p style="page-break-after:always"></p>
<?php
}
?>
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
	var LODOP; //声明为全局变量    
	function prn1_preview() {	
	//alert("a");
		LODOP=getLodop();  
		LODOP.PRINT_INIT("出貨單");
		<?php
		$len = count($_POST[cid]);
		$i = 0;
		foreach($_POST[cid] as $k=>$Order_id){
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
