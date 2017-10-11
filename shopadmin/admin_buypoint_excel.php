<?php
@ob_start();
include "Check_Admin.php";
$Creatfile ="buypoint_".date("Y-m-d");
@header("Content-Type: text/x-delimtext;  name=\"".$Creatfile.".csv\"");
@header("Content-disposition: attachment;filename=\"".$Creatfile.".csv\"");
@header("Content-Type: application/ms-excel;  name=\"".$Creatfile.".csv\"");

include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;

$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d",time()-7*24*60*60);
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time());
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
if($_GET['user_id']!="")
	$subsql = " and bp.user_id='" . intval($_GET['user_id']) . "'";
if($_GET['username']!=""){
	$subsql .= " and (u.username like '%" . $_GET['username'] . "%' or u.true_name like '%" . $_GET['username'] . "%')";
}
if(intval($_GET['timestate'])==0 && $_GET['timestate']!=""){
	$subsql .= " and bp.addtime <='" . time() . "' and bp.endtime >='" . time() . "'";
}
if(intval($_GET['timestate'])==1 && $_GET['timestate']!=""){
	$subsql .= " and (bp.addtime >='" . time() . "' or bp.endtime <='" . time() . "')";
}
if($_GET['order_serial']!=""){
	$subsql .= " and o.order_serial like '%" . $_GET['order_serial'] . "%' ";
}
if($_GET['buypointtype']!=""){
	$subsql .= " and bp.buypointtype ='" . intval($_GET['buypointtype']) . "' ";
}
$Sql      = "select bp.*,u.username,u.true_name,u.memberno from `{$INFO[DBPrefix]}buypoint` as bp inner join `{$INFO[DBPrefix]}user` as u on bp.user_id=u.user_id left join `{$INFO[DBPrefix]}order_table` as o on o.order_id=bp.orderid where 1=1 " . $subsql . "  and bp.addtime>='$begtimeunix' and bp.addtime<='$endtimeunix' order by bp.id desc";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);

$file_string .= "ID,會員編號,會員,姓名,原因,帳上餘額,日期,管理員\r\n";

echo iconv("UTF-8","big5",$file_string);

while ($Rs=$DB->fetch_array($Query)) {
	$type = $Rs['type']==0?"+":"-";
	$pm = "";
	if($Rs['sa_type']==0){
					$Sql_U = "select sa as uname from `{$INFO[DBPrefix]}administrator` where sa_id='".$Rs['sa_id']."' limit 0,1";
					$usertitle = "(高級管理員)";
					$Query_U    = $DB->query($Sql_U);
					$Rs_U=$DB->fetch_array($Query_U);
				$pm = $Rs_U['uname'].$usertitle;
				}elseif($Rs['sa_type']==1){
					$Sql_U = "select username as uname from `{$INFO[DBPrefix]}operater` where opid='".$Rs['sa_id']."' limit 0,1";
					$usertitle = "(一般管理員)";
					$Query_U    = $DB->query($Sql_U);
					$Rs_U=$DB->fetch_array($Query_U);
				$pm = $Rs_U['uname'].$usertitle;
				}
	$file_string = iconv("UTF-8","big5",formatstr($Rs['id'])) . ",";//編號
	$file_string .= iconv("UTF-8","big5",formatstr($Rs['memberno'])) . ",";//會員編號
	$file_string .= iconv("UTF-8","big5",formatstr($Rs['username'])) . ",";//會員
	$file_string .= iconv("UTF-8","big5",formatstr($Rs['true_name'])) . ",";//姓名
	$file_string .= iconv("UTF-8","big5",formatstr($Rs['content'])) . ",";//原因
	$file_string .= iconv("UTF-8","big5",formatstr($type.$Rs['point'])) . ",";//帳上餘額
	$file_string .= iconv("UTF-8","big5",formatstr(date("Y-m-d",$Rs['addtime']))) . ",";//日期
	$file_string .= iconv("UTF-8","big5",formatstr($pm)) . "\r\n";//管理員
	echo $file_string;
	$file_string = "";
}

function getBanner($bid){
	global $DB,$INFO,$class_banner,$list,$Bcontent;
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($bid)." limit 0,1 ");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result     =  $DB->fetch_array($Query);
		$class_banner[$list]['bid'] = $Result['bid'];
		$class_banner[$list]['catname'] = $Result['catname'];
		$class_banner[$list]['banner'] = $Result['banner'];
		$list++;
		if ($Result['top_id']>0)
			getBanner($Result['top_id']);
		else
			$Bcontent = $Result['catcontent'];
	}
}
function formatstr($str){
	$str = str_replace(",","，",$str);
	$str = str_replace("\"","“",$str);
	$str = str_replace("\r"," ",$str);
	$str = str_replace("\n"," ",$str);	
	return $str;
}
?>
