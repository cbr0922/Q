<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
/*$Sql      = "select * from `{$INFO[DBPrefix]}ticketpubrecord` as r inner join `{$INFO[DBPrefix]}ticket` as t on r.ticketid=t.ticketid where r.ticketid='" . $_GET['ticketid'] . "' order by r.recordid desc";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}*/




include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($_GET['begtime'],"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($_GET['endtime'],"-")+60*60*24;

$Sql = "select sum(od.goodscount*od.price) as smtotal,sum(od.goodscount) as sntotal,od.goodsname, og.bid, og.view_num, oc.top_id";
$Sql .=	" from `{$INFO[DBPrefix]}order_detail` as od";
$Sql .=	" inner join `{$INFO[DBPrefix]}order_table` as ot on od.order_id=ot.order_id";
$Sql .=	" left join `{$INFO[DBPrefix]}goods` as og on od.gid=og.gid";
$Sql .=	" left join `{$INFO[DBPrefix]}bclass` as oc on og.bid=oc.bid";
$Sql .=	" where ot.createtime>='" . $begtimeunix . "' and ot.createtime<='" . $endtimeunix . "' and ot.order_state=4";
if( $_GET['top_id'] != 0 && isset($_GET['top_id']) )
	$Sql .=	" and oc.top_id=" . $_GET['top_id'];
$Sql .=	" group by od.gid";
if( isset($_GET['type']) ){
	if( $_GET['type'] == 0 )
		$Sql .=	" order by smtotal desc";
	else if( $_GET['type'] == 1 )
		$Sql .=	" order by sntotal desc";
	else if( $_GET['type'] == 2 )
		$Sql .=	" order by view_num desc";
}
else{
	$Sql .=	" order by sntotal desc";
}

if( isset($_GET['count']) ){
	$Sql .=	" limit ".$_GET['count'];
}
else{
	$Sql .=	" limit 100";
}

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$Results_count = 0;
$Results;
$Total = 0;

if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		$Field['index'] = ($Results_count + 1);
		$Field['goodsname'] = $value['goodsname'];
		
		$Field['sntotal'] = $value['sntotal'];
		$Field['smtotal'] = $value['smtotal'];
		
		$Total =  $Total + $value['smtotal'];
		
		$Results[$Results_count] = $Field;
		$Results_count++;
	}	
}

$content = ",".$INFO['company_name']."\n\n";

$content .= ",".$_GET['begtime']." ".$_GET['endtime']."\n\n";
$content .= "排名,商品名稱,(商品銷售金額 / 銷售總金額),銷售金額,數量\n";

for($i=0; $i<$Results_count; $i++){
	$compare = floor(($Results[$i]['smtotal']/$Total) * 100);
	$content .= $Results[$i]['index'].",".$Results[$i]['goodsname'].",".$compare."%,".$Results[$i]['smtotal'].",".$Results[$i]['sntotal']."\n";
}

$content .= "\n\n銷售總金額：".$Total;

header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename='".date("Y-m-d",time()).".csv'");

$content = mb_convert_encoding($content , "Big5" , "UTF-8");
echo $content;
exit;

?>
