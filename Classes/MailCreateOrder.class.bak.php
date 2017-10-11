<?php
//@header("Content-type: text/html; charset=utf-8");
/**
 *  装载客户服务语言包
 */
include RootDocument."/language/".$INFO['IS']."/Admin_Member_Pack.php";
include RootDocument."/language/".$INFO['IS']."/Cart.php";
include RootDocument."/language/".$INFO['IS']."/Good.php";
include RootDocument."/language/".$INFO['IS']."/Order_Pack.php";
include RootDocument."/language/".$INFO['IS']."/JsMenu.php";


function  CreateMailForOrder($Order_id){
	global $DB,$INFO,$FUNCTIONS,$Cart,$Good,$Admin_Member_Pack,$Order_Pack;
	include_once "orderClass.php";
	$orderClass = new orderClass;	

	$Query     = $DB->query("select ot.*,ttime.transtime_name ,u.* from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) left join `{$INFO[DBPrefix]}user` u on ot.user_id=u.user_id where ot.order_id=".intval($Order_id)." limit 0,1");
	$Num       = $DB->num_rows($Query);

	if ( $Num <= 0 ){
		return false;
	}

	$MailOrderHtml  = "";
	$Result    = $DB->fetch_array($Query);

	$Transtime_name   = $Result[transtime_name];



	switch (intval($Result['ifinvoice'])){
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
			$Ifinvoice   = $Cart[DontNeed_piao];//$Basic_Command['Null'];
			$Invoiceform = $Basic_Command['Null'];
			$TheOneNum   = $Basic_Command['Null'];
			break;
		case 3:
		  $Ifinvoice   =  "捐贈中華民國全球元母大慈協會";
		  $Invoiceform =  $Basic_Command['Null'];
		  $TheOneNum   =  $Basic_Command['Null'];
	  
		  break;
	}

	$Order_state      = $Result['order_state'];
	$paycode      = $Result['paycode'];
	$storename      = $Result['storename'];
	$Pay_state        = $Result['pay_state'];
	$Transport_state        = $Result['transport_state'];
	$Totalprice   = $Result['totalprice'];
	$Total_price = $Result[totalprice]+$Result[transport_price];
	$discount_totalPrices  = $Result['discount_totalPrices'];
	$Totalprice   = $Result['totalprice'];
	$bonus = $Result[bonuspoint]+$bonuspoint[totalbonuspoint];
	$ATM              = trim($Result['atm'])!="" ? "".trim($Result['atm']) : $Basic_Command['Null'] ;
	
	$MailOrderHtml = "
<style type=\"text/css\">
<!--
.style1 {color: #000000}
.style2 {color: #996600}
.style3 {font-size: 14px}
.style5 {font-size: 14px; color: #996600; }
.p9black {
	FONT-SIZE: 11px; WORD-SPACING: 5px; COLOR: #000000; FONT-FAMILY: \"Verdana\", \"Arial\", \"Helvetica\", \"sans-serif\"
}
.p9v {
	FONT-SIZE: 11px; COLOR: #666666; LINE-HEIGHT: 18px; FONT-FAMILY: \"Arial\", \"Helvetica\", \"sans-serif\"
}
.p9navyblue {
	FONT-SIZE: 11px; COLOR: #336699; FONT-FAMILY: \"Arial\", \"Helvetica\",\"sans-serif\"
}
.p9orange {
	FONT-SIZE: 11px; COLOR: #cc6600; FONT-FAMILY: \"Arial\",\"Helvetica\", \"sans-serif\"
}
-->
</style>
<table width=100% border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <TR>
    <TD align=\"center\">
      <table width=\"98%\" height=\"98%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
        <tr>
          <td bgcolor=\"#CCCCCC\">
            <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"1\" class=\"p9v\">
			  <tr bgcolor=\"#FFFFFF\">
                <td colspan=\"4\" class=\"p9black\">訂單資訊</td>
              </tr>
               <tr bgcolor=\"#FFFFFF\">
                <td width=\"112\" class=\"p9black\" bgcolor=\"#EEEEEE\">訂購人姓名</td>
                <td width=\"252\">" . $FUNCTIONS->getOrderUInfo($Result['true_name'],1) . "</td>
				<td width=\"112\" class=\"p9black\" bgcolor=\"#EEEEEE\"></td>
                <td width=\"252\"></td>
                
              </tr>
			  <tr bgcolor=\"#FFFFFF\">
                <td width=\"112\" class=\"p9black\" bgcolor=\"#EEEEEE\">訂單編號</td>
                <td width=\"252\">".$Result['order_serial']."</td>
				<td width=\"112\" class=\"p9black\" bgcolor=\"#EEEEEE\">訂單狀態</td>
                <td width=\"252\">".$orderClass->getOrderState($Order_state,1).",".$orderClass->getOrderState(intval($Pay_state),2).",".$orderClass->getOrderState(intval($Transport_state),3)."</td>
                
              </tr>
			  <tr bgcolor=\"#FFFFFF\">
                <td width=\"112\" class=\"p9black\" bgcolor=\"#EEEEEE\">支付狀態</td>
                <td width=\"252\">".$orderClass->getOrderState(intval($Pay_state),2)."</td>
				<td width=\"112\" class=\"p9black\" bgcolor=\"#EEEEEE\">配送狀態</td>
                <td width=\"252\">".$orderClass->getOrderState(intval($Transport_state),3)."</td>
                
              </tr>
              <tr bgcolor=\"#FFFFFF\">
                <td width=\"107\" class=\"p9black\" bgcolor=\"#EEEEEE\">訂貨時間</td>
                <td width=\"276\">".date("Y-m-d H:i a ",$Result[createtime])."</td>
				<td width=\"112\" class=\"p9black\" bgcolor=\"#EEEEEE\">商品總金額</td>
                <td width=\"252\">".trim($Result[totalprice])."</td>
              </tr>
			   <tr bgcolor=\"#FFFFFF\">
                <td width=\"107\" align=\"left\" bgcolor=\"#EEEEEE\" class=\"p9black\">配送方式</td>
                <td width=\"276\">".trim($Result[deliveryname])."</td>
                <td width=\"107\" nowrap bgcolor=\"#EEEEEE\" class=\"p9black\">配送費用</td>
                <td width=\"276\">".trim($Result[transport_price])."元</td>
              </tr>";
			  if ($Result['ifgroup']==1){
			  $MailOrderHtml .= "
			  
			  <tr bgcolor=\"#FFFFFF\">
                <td width=\"107\" align=\"left\" bgcolor=\"#EEEEEE\" class=\"p9black\"></td>
                <td width=\"276\"></td>
                <td width=\"107\" nowrap bgcolor=\"#EEEEEE\" class=\"p9black\">消費總金額</td>
                <td width=\"276\">".($discount_totalPrices+$Result[transport_price])."元</td>
              </tr>
			  <tr bgcolor=\"#FFFFFF\">
                <td width=\"107\" align=\"left\" bgcolor=\"#EEEEEE\" class=\"p9black\">團購點</td>
                <td width=\"276\">" . $Result['totalGrouppoint'] . "</td>
                <td width=\"107\" nowrap bgcolor=\"#EEEEEE\" class=\"p9black\">購物金</td>
                <td width=\"276\">".$Result['buyPoint']."元</td>
              </tr>
			 ";
			  }else{
			  $MailOrderHtml .= "
			  
			  <tr bgcolor=\"#FFFFFF\">
                <td width=\"107\" align=\"left\" bgcolor=\"#EEEEEE\" class=\"p9black\">折價券</td>
                <td width=\"276\">" . $Result['ticket_discount_money'] . "</td>
                <td width=\"107\" nowrap bgcolor=\"#EEEEEE\" class=\"p9black\">折扣後金額</td>
                <td width=\"276\">".$discount_totalPrices."元</td>
              </tr>
			  
			  <tr bgcolor=\"#FFFFFF\">
                <td width=\"107\" align=\"left\" bgcolor=\"#EEEEEE\" class=\"p9black\">使用積分點數</td>
                <td width=\"276\">".trim($bonus)."</td>
                <td width=\"107\" nowrap bgcolor=\"#EEEEEE\" class=\"p9black\">消費總金額</td>
                <td width=\"276\">".($discount_totalPrices+$Result[transport_price])."元</td>
              </tr>
			  <tr bgcolor=\"#FFFFFF\">
                <td width=\"107\" align=\"left\" bgcolor=\"#EEEEEE\" class=\"p9black\"></td>
                <td width=\"276\"></td>
                <td width=\"107\" nowrap bgcolor=\"#EEEEEE\" class=\"p9black\">購物金</td>
                <td width=\"276\">".$Result['buyPoint']."元</td>
              </tr>
			  ";
			  }
			 $MailOrderHtml .=  "<tr bgcolor=\"#FFFFFF\">
                <td width=\"107\" align=\"left\" bgcolor=\"#EEEEEE\" class=\"p9black\">付款方式</td>
                <td width=\"276\">".$Result[paymentname]."</td>
                <td width=\"107\" nowrap bgcolor=\"#EEEEEE\" class=\"p9black\">支付現金</td>
                <td width=\"276\">".($discount_totalPrices+$Result[transport_price]-$Result['buyPoint'])."</td>
              </tr>
			  <tr bgcolor=\"#FFFFFF\">
                <td width=\"112\" bgcolor=\"#EEEEEE\" class=\"p9black\">發票格式</td>
                <td width=\"252\">".$Ifinvoice."</td>
				<td width=\"107\" bgcolor=\"#EEEEEE\" class=\"p9black\">發票抬頭</td>
                <td width=\"276\">".$Invoiceform."</td>
              </tr>
			  <tr bgcolor=\"#FFFFFF\">
                <td nowrap bgcolor=\"#EEEEEE\" class=\"p9black\">ATM虛擬帳號</td>
                <td>".$ATM."</td>
                <td bgcolor=\"#EEEEEE\"><span class=\"p9black\">統一編號</span></td>
                <td class=\"p9black\">".$TheOneNum."</td>
              </tr>
			  <tr bgcolor=\"#FFFFFF\">
                <td nowrap bgcolor=\"#EEEEEE\" class=\"p9black\">超商繳款編號</td>
                <td>".$paycode."</td>
                <td bgcolor=\"#EEEEEE\"><span class=\"p9black\">取貨門市</span></td>
                <td class=\"p9black\">".$storename."</td>
              </tr>
          </table></td>
        </tr>
      </table>
      <br>
      <table width=\"98%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >
        <tr>
          <td height=\"27\"> <span class=\"style5\">".$Order_Pack[MailCContent]."</span> </td>
        </tr>
      </table>
      <table width=\"98%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
        <tr>
          <td bgcolor=\"#CCCCCC\">
            <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"1\" class=\"p9v\">
              <tr bgcolor=\"#FFFFFF\">
                <td width=\"112\" nowrap bgcolor=\"#EEEEEE\" class=\"p9black\">收貨人姓名</td>
                <td width=\"252\">".$FUNCTIONS->getOrderUInfo(trim($Result[receiver_name]),1)."</td>
                <td width=\"107\" align=\"left\" nowrap bgcolor=\"#EEEEEE\" class=\"p9black\">電子信箱</td>
                <td width=\"276\">".$FUNCTIONS->getOrderUInfo(trim($Result[receiver_email]),5)."</td>
              </tr>
              <tr bgcolor=\"#FFFFFF\">
                <td nowrap bgcolor=\"#EEEEEE\" class=\"p9black\">郵遞區號</td>
                <td>".trim($Result[receiver_post])."</td>
                <td width=\"15%\" class=\"p9black\" nowrap bgcolor=\"#EEEEEE\">聯絡電話</td>
                <td width=\"35%\">********</td>
              </tr>
              <tr bgcolor=\"#FFFFFF\">
                <td class=\"p9black\" bgcolor=\"#EEEEEE\">收貨人地址</td>
                <td colspan=\"3\">".$FUNCTIONS->getOrderUInfo(trim($Result[receiver_address]),10)."</td>
              </tr>
              <tr bgcolor=\"#FFFFFF\">
                <td class=\"p9black\" bgcolor=\"#EEEEEE\">宅配時間</td>
                <td colspan=\"3\">".trim($Transtime_name)."</td>
              </tr>

              <tr bgcolor=\"#FFFFFF\">
                <td class=\"p9black\" bgcolor=\"#EEEEEE\">訂單備註</td>
                <td colspan=\"3\">".trim($Result[receiver_memo])."&nbsp;&nbsp;&nbsp;</td>
              </tr>
          </table></td>
        </tr>
      </table>
      <br>
      <table width=\"98%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=dotX>
        <tr>
          <td height=\"27\"> <span class=\"style5\">
            ".$Order_Pack[Order_ProductInfo]."
          </span> </td>
        </tr>
      </table>
      <table width=\"98%\" height=\"98%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
        <tr>
          <td bgcolor=\"#CCCCCC\">
            <table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"4\" cellspacing=\"1\" class=\"p9v\">
              <tr bgcolor=\"#E4E3E1\" align=\"center\">
                <td width=\"12%\" nowrap class=\"p9blue\">".$Good[Bn_name]."</td>
                <td class=\"p9blue\" width=\"40%\" bgcolor=\"#E4E3E1\">".$Good[ProductName_say]."</td>
				<td class=\"p9blue\" width=\"12%\" bgcolor=\"#E4E3E1\">顔色/尺寸</td>
                <td width=\"15%\" bgcolor=\"#E4E3E1\" class=\"p9blue\">".$Good[Price_name]."</td>
                <td class=\"p9blue\" width=\"12%\">".$Good[Pricedesc_say]."</td>
                <td class=\"p9blue\" width=\"12%\">".$Good[JianNum_say]."</td>
              </tr>
";
	$Query_detail = $DB->query(" select g.*,gd.bn from `{$INFO[DBPrefix]}order_detail` as g inner join `{$INFO[DBPrefix]}goods` as gd on g.gid=gd.gid where g.order_id=".intval($Order_id)." and g.packgid=0 order by g.order_detail_id asc ");
	$i = 0 ;
	while ($Rs_detail = $DB->fetch_array($Query_detail)){
	
						//if ($Rs['detail_id'] > 0){
						 	$g =  $Rs_detail['goodsname'];
							if($Rs_detail['packgid']>0)
								$g .=   "[組合商品]"; 
							else{
								$Rs_detail[market_price] = "";
								$Rs_detail[price] = "";
							}
							$g .=  "<br>";
							$g .=   $Rs_detail['detail_bn'] . "<br>";
							$g .=   $Rs_detail['detail_name'] . "<br>";
							$g .=   $Rs_detail['detail_des'];
						//}else{
						//	$g =   $Rs['detail_name'] . "<br>";
						//	$g .=   $Rs['goodsname'];
						//}

		$MailOrderHtml .="
              <tr bgcolor=\"#FFFFFF\">
                <td class=\"p9black\" width=\"12%\" bgcolor=\"#FFFFFF\" align=\"CENTER\">".$Rs_detail[bn]."</td>
                <td class=\"p9black\" width=\"40%\" bgcolor=\"#FFFFFF\">".$g."</td>
				<td class=\"p9black\" width=\"12%\" bgcolor=\"#FFFFFF\" align=\"center\" >".$Rs_detail[good_color]."/".$Rs_detail[good_size]."</td>
                <td class=\"p9black\" width=\"12%\" bgcolor=\"#FFFFFF\" align=\"center\">".$Rs_detail[market_price]."</td>
                <td class=\"p14red\" width=\"12%\" bgcolor=\"#FFFFFF\" align=\"center\"><span class=\"p9black\">".$Rs_detail[price]."</span>       </td>
                <td class=\"p9black\" width=\"12%\" bgcolor=\"#FFFFFF\" align=\"center\">".$Rs_detail[goodscount]."</td>
              </tr>
			  
			";
	}
	$MailOrderHtml .="
          </table></td>
        </tr>
      </table>

      <p>&nbsp;</p>
    <p>&nbsp;</p></TD>
  </TR>
</TABLE>
";
//echo $MailOrderHtml;exit;
	return $MailOrderHtml ;
}
?>
