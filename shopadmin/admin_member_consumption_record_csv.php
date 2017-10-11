<?php
include_once "Check_Admin.php";

$goods_starttime  = $_GET['goods_starttime']!="" ? $_GET['goods_starttime'] : date("Y-m-d",time()-7*24*60*60);
$goods_endtime  = $_GET['goods_endtime']!="" ? $_GET['goods_endtime'] : date("Y-m-d",time());

$Sql      = "select u.username,d.goodsname,d.price,d.bn,d.packgid,t.order_serial,t.paymentname from `{$INFO[DBPrefix]}user` u left join `{$INFO[DBPrefix]}order_table` t on u.`user_id`=t.`user_id` left join `{$INFO[DBPrefix]}order_detail` d on t.`order_id` = d.`order_id` WHERE u.`memberno`='".$_GET['memberno']."' AND `createtime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') AND t.order_state!=3 AND t.pay_state!=2 GROUP BY t.order_serial,t.paymentid,d.gid ORDER BY t.order_serial desc";

//echo $Sql;

$content = ",".$INFO['company_name']."\n\n";
$content .= "會員".$_GET['memberno']."消費紀錄,".$goods_starttime."~".$goods_endtime."\n\n";
$content .= "訂單編號,付款方式,商品貨號,商品名稱,單價\n";

$Query_Excel = $DB->query($Sql);
$TotalPrice    = 0;
$Num = $DB->num_rows($Query_Excel);
if ($Num>0){
	while ($RS_Excel = $DB->fetch_array($Query_Excel)){
		$price = $RS_Excel['packgid']==0 ? $RS_Excel['price'] : "";
		$goodsname = $RS_Excel['packgid']==0 ? $RS_Excel['goodsname'] : $RS_Excel['goodsname']."[組合商品]";
		$content .= $RS_Excel['order_serial'].",".$RS_Excel['paymentname'].",".$RS_Excel['bn'].",".$goodsname.",".$price."\n";
	}
}

header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename='會員".$_GET['memberno']."消費紀錄".$goods_starttime."_".$goods_endtime.".csv'");

$content = mb_convert_encoding($content , "Big5" , "UTF-8");
echo $content;
exit;

?>
