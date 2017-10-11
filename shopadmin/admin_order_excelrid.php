<?php
@ob_start();
//session_start();
include "Check_Admin.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");
@ob_implicit_flush(0);
$Creatfile ="order_rid_".date("Y-m-d");
	//@header("Content-Type: text/html;  charset=UTF-8; name=\"$Creatfile.csv\"");
	@header("Content-Type: text/x-delimtext;  name=\"".$Creatfile.".csv\"");
	@header("Content-disposition: attachment;filename=\"".$Creatfile.".csv\"");
	@header("Content-Type: application/ms-excel;  name=\"".$Creatfile.".csv\"");
include_once "Time.class.php";
$TimeClass = new TimeClass;
$file_string .=iconv("UTF-8","big5", "訂購日期,訂單編號,購買人,Market Taiwan RID number,商品編號,購買商品,數量,網路銷售價,總計\n");
$file_string .= "Order Date,Order Serial Number,Buyer Name,,Product Serial Number ,Product description,Product Quantity,Unit price,Sale Amount\n";
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

$FUNCTIONS->setLog("訂單匯出美安格式");
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

	$Sql = " select  o.order_serial,o.order_id,o.createtime,o.totalprice,o.transport_price,
	         o.transport_price,o.deliveryname,o.paymentname,o.receiver_name,o.order_state,o.pay_state,o.receiver_post,o.receiver_tele,o.receiver_mobile,o.receiver_address ,od.goodsname,od.price,od.goodscount ,o.rid,g.bn
	         from `{$INFO[DBPrefix]}order_detail` as od left join `{$INFO[DBPrefix]}order_table` o on od.order_id=o.order_id  inner join  `{$INFO[DBPrefix]}goods` as g on od.gid=g.gid
	         where  o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix'  and o.rid<>''
	         ".$Add_one."   ".$Add_two."   ".$Add_three."  ".$Add."  ".$Provider_Search." ";
	$Sql      .= "  order by o.createtime desc";


}else{
	//下边如果不参与查询的资料

	//$Add  = $_GET['State']!=""  ? str_replace("and","where",$Add)." and o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' " :  " where o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' ";
	//$Add  = $_GET[Order_Tracks]=="Show" ? $Add." and o.order007_status=1 and  o.order007_begtime <= '".date("Y-m-d",time())."' " : $Add ;
	
	$Sql  = " select o.order_serial,o.order_id,o.createtime,o.totalprice,o.transport_price,
	         o.transport_price,o.deliveryname,o.paymentname,o.receiver_name,o.order_state,o.pay_state,o.receiver_post,o.receiver_tele,o.receiver_mobile,o.receiver_address ,od.goodsname,od.price,od.goodscount ,o.rid,g.bn
	        from `{$INFO[DBPrefix]}order_detail` as od left join `{$INFO[DBPrefix]}order_table` o on od.order_id=o.order_id  inner join  `{$INFO[DBPrefix]}goods` as g on od.gid=g.gid
	         where o.rid<>'' and od.packgid=0";
	$Sql      .= "  order by o.createtime desc";
}

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);

if ($Num > 0){
	while ($Rs = $DB->fetch_array($Query)){
		$file_string .= date("Y-m-d",$Rs['createtime']) . "," . $Rs['order_serial'] . "," . iconv("UTF-8","big5", $Rs['receiver_name']) . "," . iconv("UTF-8","big5", $Rs['rid']) . "," . iconv("UTF-8","big5", $Rs['bn']) .  "," . iconv("UTF-8","big5", $Rs['goodsname']) .  "," . $Rs['goodscount'] .  "," . $Rs['price'] .  "," . ($Rs['price']*$Rs['goodscount'])  . "\n";
	}

	


	/**
	 * 这个部分是写一个本地文件，在目前这里是没有用的。临时保留
	 * 
	
	if ( $fh = fopen( $Creatfile.'.csv', 'w+' ) )
	{
	fputs ($fh, iconv("UTF-8","big5",$file_string), strlen($file_string) );
	fclose($fh);
	@chmod ($Creatfile.'.csv',0777);
	}
	 */

	
	//@header("location:".$Creatfile.'.csv');
}
echo $file_string;
?>