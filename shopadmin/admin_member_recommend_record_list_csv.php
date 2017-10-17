<?php
include_once "Check_Admin.php";

$goods_starttime  = $_GET['goods_starttime']!="" ? $_GET['goods_starttime'] : date("Y-m-d",time()-7*24*60*60);
$goods_endtime  = $_GET['goods_endtime']!="" ? $_GET['goods_endtime'] : date("Y-m-d",time());

$Sql      = "select u.memberno,u.user_id,u.username,u.true_name,u.en_firstname,COUNT(t.order_serial) ordercount,SUM(t.totalprice) totalprice from `{$INFO[DBPrefix]}user` u left join `{$INFO[DBPrefix]}order_table` t on u.`memberno`=t.`recommendno` WHERE `createtime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') AND t.order_state!=3 AND t.pay_state!=2 GROUP BY u.user_id ORDER BY totalprice desc";

//echo $Sql;

$content = ",".$INFO['company_name']."\n\n";
$content .= "會員推薦消費紀錄列表,".$goods_starttime."~".$_GET['goods_endtime']."\n\n";
$content .= "會員編號,帳號,姓名,訂單數量,訂單總金額\n";

$Query_Excel = $DB->query($Sql);
$TotalPrice    = 0;
$Num = $DB->num_rows($Query_Excel);
if ($Num>0){
	while ($RS_Excel = $DB->fetch_array($Query_Excel)){
		$content .= $RS_Excel['memberno'].",".$RS_Excel['username'].",".$RS_Excel['true_name'].$RS_Excel['en_firstname'].$Rs['en_secondname'].",".$RS_Excel['ordercount'].",".$RS_Excel['totalprice']."\n";
	}
}

header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename='會員推薦消費紀錄列表".$goods_starttime."_".$goods_endtime.".csv'");

$content = mb_convert_encoding($content , "Big5" , "UTF-8");
echo $content;
exit;

?>
