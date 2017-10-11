<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

$pub_starttime  = $_GET['pub_starttime']!="" ? $_GET['pub_starttime'] : date("Y-m-d",time()-7*24*60*60);
$pub_endtime  = $_GET['pub_endtime']!="" ? $_GET['pub_endtime'] : date("Y-m-d",time());

$market;

$Sql = "";
$Data = "";
$Join = "";
$Where = " WHERE FROM_UNIXTIME(`createtime`,'%y-%m-%d') BETWEEN DATE_FORMAT('".$pub_starttime."','%y-%m-%d') AND DATE_FORMAT('".$pub_endtime."','%y-%m-%d')";
$Group = "";
$top_ids = "";

if($_GET['provider'] != "na" && isset($_GET['provider'])){
	if($_GET['provider'] == "yes"){
		$Where .= " AND t.provider_id=".intval($_GET['provider_id']);
	}else if($_GET['provider'] == "no"){
		if($_GET['provider_id'] == 0 && isset($_GET['provider_id'])){
			$Where .= " AND t.provider_id>".intval($_GET['provider_id']);
		}else{
			$Where .= " AND t.provider_id=".intval($_GET['provider_id']);
		}
	}
}

if($_GET['item_id']!="" && isset($_GET['item_id'])){
	$Where .= " AND (d.`gid`=".intval($_GET['item_id'])." OR d.`bn` REGEXP '".trim($_GET['item_id'])."')";
}

if(isset($_GET['order_state'])){
	if($_GET['order_state'] == 4){
		$Where .= " AND t.order_state=".intval($_GET['order_state']);
	}else{
		$Where .= " AND (t.order_state=4 OR t.transport_state=".intval($_GET['order_state']).")";
	}
}else{
	$Where .= " AND t.order_state=4";
}

if($_GET['top_id'] != 0 && isset($_GET['top_id'])){
	$Join .= " JOIN `{$INFO[DBPrefix]}goods` g ON d.`gid` = g.`gid`";
	$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($_GET[top_id]));
	$Next_ArrayClass  = array_filter(explode(",",$Next_ArrayClass));
	$Array_class      = array_unique($Next_ArrayClass);
	//print_r($Array_class);
	if (count($Array_class)>0){
		$top_ids = intval($_GET['top_id']).",".implode(",",$Array_class);
		$Where .= " AND g.bid in (".$top_ids.")";
	}else{
		$top_ids = intval($_GET['top_id']);
		$Where .= " AND g.`bid`=".$top_ids;
	}

}

$Sql .= "SELECT d.goodsname,t.iftogether,d.hadsend Totalhadsend,d.price Totalprice,d.packgid FROM `{$INFO[DBPrefix]}order_table` t JOIN `{$INFO[DBPrefix]}order_detail` d ON t.order_id = d.order_id";
$Sql .= $Join.$Where;

//echo $Sql;

$content = ",".$INFO['company_name']."\n\n";
$content .= ",".$_GET['pub_starttime']." ".$_GET['pub_endtime']."\n\n";
$content .= "商品名稱,訂單類型,銷售數量,價格,銷售金額\n";

$Query_Excel = $DB->query($Sql);
$TotalPrice    = 0;
$Num = $DB->num_rows($Query_Excel);
if ($Num>0){
	while ($RS_Excel = $DB->fetch_array($Query_Excel)){
		if($RS_Excel['packgid']==0){
			$Total = $RS_Excel['Totalprice']*$RS_Excel['Totalhadsend'];
			$TotalPrice += $Total;
			$Type = $RS_Excel['iftogether']==0 ? "供應商" : "統倉";
			$content .= $RS_Excel['goodsname'].",".$Type.",".$RS_Excel['Totalhadsend'].",".floor($RS_Excel['Totalprice']).",".$Total."\n";
		}else{
			$Type = $RS_Excel['iftogether']==0 ? "供應商" : "統倉";
			$content .= $RS_Excel['goodsname']."[組合商品],".$Type.",".$RS_Excel['Totalhadsend'].",0,0\n";
		}
	}
}

$content .= "\n\n銷售總金額：".$TotalPrice;

header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename='".date("Y-m-d",time()).".csv'");

$content = mb_convert_encoding($content , "Big5" , "UTF-8");
echo $content;
exit;

?>
