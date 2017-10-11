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
	$piaocode   = $Result['piaocode'];
	$senddate   = $Result['senddate'];
	if ($Result['true_name']!=""){
		$true_name = $Result['true_name'];
	}else {
		$true_name = $Result['en_firstname'] . " " . $Result['en_secondname'];
	}

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
<ul>
<li>訂單編號：".$Result['order_serial']."</li>

<li>訂購人：" . $FUNCTIONS->getOrderUInfo($true_name,1) . "</li>

<li>訂購商品名稱與數量：".($Rs_detail['price'])."<br>


";
	$Query_detail = $DB->query(" select g.*,gd.bn from `{$INFO[DBPrefix]}order_detail` as g inner join `{$INFO[DBPrefix]}goods` as gd on g.gid=gd.gid where g.order_id=".intval($Order_id)." and g.packgid=0 order by g.order_detail_id asc ");
	$i = 0 ;
	while ($Rs_detail = $DB->fetch_array($Query_detail)){
		//print_r($Rs_detail);

						//if ($Rs['detail_id'] > 0){
						 	$g =  $Rs_detail['goodsname'];
							if($Rs_detail['packgid']>0)
								$g .=   "[組合商品]";
							elseif ($Rs_detail['ifpack']==1){
								$Rs_detail[market_price] = "";
								$Rs_detail[price] = "";
							}
							$g .=  "&nbsp;";
							$g .=   $Rs_detail['detail_bn'] . "&nbsp;";
							if($Rs_detail[detail_name] !=""){
							$g .=   "( ".$Rs_detail['detail_name'] . " )";
							}
							//$g .=   "&nbsp;".$Rs_detail['detail_des']; //詳細商品描述、折扣內容
						//}else{
						//	$g =   $Rs['detail_name'] . "<br>";
						//	$g .=   $Rs['goodsname'];
						//}
		if($Rs_detail[good_color] !="" && $Rs_detail[good_size]!=""){
			$color_size= "( ".$Rs_detail[good_color]."/".$Rs_detail[good_size]." )&nbsp;&nbsp;";
		}else{
			if($Rs_detail[good_color] !=""){
			$color_size= "( ".$Rs_detail[good_color]." )&nbsp;&nbsp;";
			}elseif($Rs_detail[good_size] !=""){
				$color_size= "( ".$Rs_detail[good_size]." )&nbsp;&nbsp;";
			}else{
				$color_size="";
			}
		}
		$MailOrderHtml .="
              ".$Rs_detail[bn]."&nbsp;&nbsp;".$g."&nbsp;&nbsp;".$color_size."x".$Rs_detail[goodscount]."<br>



			";
	}
	$MailOrderHtml .="</li>

</ul>



     </TD>
  </TR>
</TABLE>
";
//echo $MailOrderHtml;exit;
	return $MailOrderHtml ;
}
?>
