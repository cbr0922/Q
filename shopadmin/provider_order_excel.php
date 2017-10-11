<?php
//@ob_start();
//session_start();
//print_r($_POST);print_r($_GET);
include "Check_Admin.php";
	include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
include_once 'crypt.class.php';
@header("Content-type: text/html; charset=utf-8");
	@ob_implicit_flush(0);
	$Creatfile ="order_".date("Y-m-d");
	//@header("Content-Type: text/html;  charset=UTF-8; name=\"$Creatfile.csv\"");
	@header("Content-Type: text/x-delimtext;  name=\"".$Creatfile.".csv\"");
	@header("Content-disposition: attachment;filename=\"".$Creatfile.".csv\"");
	@header("Content-Type: application/ms-excel;  name=\"".$Creatfile.".csv\"");
/*
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
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;

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

		if (trim($_GET['type'])=='o.order_serial' || trim($_GET['type'])=='u.true_name' || trim($_GET['type'])=='u.username' || trim($_GET['type'])=='o.receiver_name' || trim($_GET['type'])=='o.receiver_email' ){
			$Add_one = " and ".$_GET['type']."  like '%".trim(urldecode($_GET['skey']))."%' ";
		}elseif(trim($_GET['type'])=='g.bn'){
			$Add_od = "inner join (select od.order_id from `{$INFO[DBPrefix]}order_detail` as od left join `{$INFO[DBPrefix]}goods` g on (g.gid=od.gid) where g.bn like '%" . trim(urldecode($_GET['skey'])) . "%') as odg on odg.order_id=o.order_id";
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
	         o.transport_price,o.deliveryname,o.paymentname,o.receiver_name,o.order_state,o.pay_state,o.receiver_post,o.receiver_tele,o.receiver_mobile,o.receiver_address,ou.sys_say,ou.userback_type,ou.userback_alread  ,o.ticketmoney,o.discount_totalPrices
	         from `{$INFO[DBPrefix]}order_table` o  left  join  `{$INFO[DBPrefix]}user` u on (u.user_id=o.user_id)  
	         ".$Join." `{$INFO[DBPrefix]}order_userback` ou on (o.order_id=ou.order_id)  " . $Add_od . "
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
*/
//$file_string .= "編號,訂單編號,收貨人姓名,郵遞區號,收貨人地址,收貨人聯絡電話(市話),收貨人聯絡電話(手機),商品代號,商品名稱,數量,單價,收貨時段,出貨單號碼,配送方式,訂購人(送禮人),總金額,優惠後總金額,發票號碼,發票抬頭,統一編號,備註\n";
$file_string .= "編號,訂單編號,收貨人姓名,郵遞區號,收貨人地址,收貨人電話,收貨人手機,商品代號,商品名稱,規格,數量,單價,總金額,點數折抵,折扣額,運費	,實收金額	,紅利點數	,	廠編,廠商名稱,訂購日,訂單狀態,收貨時段,延遲天數,延遲罰款,出貨日,出貨單號碼,配送方式,訂購人(送禮人),備註\r\n";


echo iconv("UTF-8","big5",$file_string);
	//$Creatfile ="../__share/order_doc/order_".date("Y-m-d");


	/**
	 * 这个部分是写一个本地文件，在目前这里是没有用的。临时保留
	 * 
	 */
	//if ( $fh = fopen( $Creatfile.'.csv', 'w+' ) )
	//{
	//fputs ($fh, iconv("UTF-8","big5",$file_string), strlen($file_string) );
	
$Cid  = $_POST['cid'];
$Ci   = $_POST['Ci'];
$Tonum= $_POST['tonum'];
$Gid  = $_POST['gid'];

$Cid_num   = count($Cid);
$Ci_num    = count($Ci);
$Tonum_num = count($Tonum);
for ($i=0;$i<$Cid_num;$i++){
	$goods = "";
	$g_count = "";
	$price= "";
	$Bn= "";
		$Sql_s = "select ot.*,od.*,od.provider_id as dprovider_id  from `{$INFO[DBPrefix]}order_detail` od inner join `{$INFO[DBPrefix]}order_table` ot  on (od.order_id=ot.order_id)  where ot.order_id='".intval($Cid[$i])."' order by  od.order_detail_id desc ";


		$Query_s    = $DB->query($Sql_s);
		$Num_s      = $DB->num_rows($Query_s);
		while ($Rs_s=$DB->fetch_array($Query_s)) {
			$Bn = "";
			$color = "";
			$size = "";
			$Query_g = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($Rs_s['gid'])." limit 0,1");
			$Result_g= $DB->fetch_array($Query_g);
			$Bn        =  $Rs_s['bn'] ;
			$goodsno        =  $Result_g['goodsno'] ;
			//if (intval($Rs_s['detail_id'])>0){
			//	$Bn = $Rs_s['detail_bn'];
			//}
			$goods = $Rs_s['goodsname'] ;
			$g_count = $Rs_s['goodscount'] ;
			if ($Rs_s['good_color'] != "")
				$color = " 顏色：" . $Rs_s['good_color'] . " ";
			if ($Rs_s['good_size'] != "")
				$size = " 尺寸：" . $Rs_s['good_size'];
			if ($Rs_s['detail_bn'] != "")
				$detail = "  " . $Rs_s['detail_bn'];
			if ($Rs_s['detail_name'] != "")
				$detail .= "  " . $Rs_s['detail_name'];
			if ($Rs_s['detail_des'] != "")
				$detail .= "  " . $Rs_s['detail_des'];
			if ($Rs_s['ifxygoods'] ==1)
				$detail .= "  " . str_replace("<br>","|",$Rs_s['xygoods_des']);
			$price = $Rs_s['price'] ;
			$user_id = $Rs_s['user_id'];
			
			$Query_p = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($Rs_s['dprovider_id'])." limit 0,1");
			$Result_p= $DB->fetch_array($Query_p);
			$provider = $Result_p['provider_name'];
			$providerno = $Result_p['providerno'];
			
			
			$Query_m = $DB->query("select * from `{$INFO[DBPrefix]}user` where user_id=".intval($user_id)." limit 0,1");
			$Result_m= $DB->fetch_array($Query_m);
			$Query_first = $DB->query(" select ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}transtime` ttime where ttime.transtime_id='".intval($Rs_s['transtime_id'])."' limit 0,1");
			$Num_first   = $DB->num_rows($Query_first);
			$Result_first= $DB->fetch_array($Query_first);
			
			
			$memo = str_replace(",","，",$Rs_s['receiver_memo']);
			$memo = str_replace("\"","“",$memo);
			$memo = str_replace("\r"," ",$memo);
			$memo = str_replace("\n"," ",$memo);
			$Rs_s['receiver_tele']    = MD5Crypt::Decrypt ( trim($Rs_s['receiver_tele']), $INFO['tcrypt']);
  			$Rs_s['receiver_mobile']  = MD5Crypt::Decrypt ( trim($Rs_s['receiver_mobile']), $INFO['mcrypt']);
			$Order_state      = $Rs_s['order_state'];
						$Pay_state        = $Rs_s['pay_state'];
						$Transport_state        = $Rs_s['transport_state'];
			//$file_string .= "編號,訂單編號,收貨人姓名,郵遞區號,收貨人地址,收貨人電話,收貨人手機,商品代號,商品名稱,數量,單價,總金額,點數折抵,折扣額,運費	,實收金額	,成本單價	,成本小計,	延遲天數,延遲罰款,廠編,廠商名稱,訂購日,出貨日,收貨時段,出貨單號碼,配送方式,訂購人(送禮人),備註\r\n";

			echo $file_string =$Rs_s['order_detail_id'] . "," .$Rs_s['order_serial'] . "," . iconv("UTF-8","big5",$Rs_s['receiver_name']) . "," . $Rs_s['receiver_post'] . "," . iconv("UTF-8","big5",str_replace(",","，",$Rs_s['receiver_address'])) . "," . iconv("UTF-8","big5",$Rs_s['receiver_tele']) . "," . iconv("UTF-8","big5",$Rs_s['receiver_mobile']) . "," . iconv("UTF-8","big5",$Bn) . "," . iconv("UTF-8","big5",$goods) . "," . iconv("UTF-8","big5",$color . $size . $detail) . "," . $g_count . "," . $price . "," . ($g_count * $price) . "," . ($Rs_s[bonuspoint]+$Rs_s[totalbonuspoint]) . "," . ($Rs_s[totalprice]-$Rs_s[discount_totalPrices]) . "," . $Rs_s['transport_price'] . "," . ($Rs_s['discount_totalPrices']+$Rs_s['transport_price']) . "," . ($Rs_s['point']) . "," . $providerno . "," . iconv("UTF-8","big5",$provider) . "," . date("Y-m-d",$Rs_s['createtime']) . "," . iconv("UTF-8","big5",$orderClass->getOrderState(intval($Order_state),1))  . "," . iconv("UTF-8","big5",$Result_first['transtime_name']) . ",,," . iconv("UTF-8","big5",$Rs_s['sendtime']) . "," . $Rs_s['piaocode'] . "," . iconv("UTF-8","big5",$Rs_s['deliveryname']) . ",," . iconv("UTF-8","big5",$memo) . "\n";
			//fputs ($fh, $file_string, strlen($file_string) );
		}

		
	}

//fclose($fh);
	//@chmod ($Creatfile.'.csv',0777);
	//}

	
	


	//@header("location:".$Creatfile.'.csv');
//}
?>