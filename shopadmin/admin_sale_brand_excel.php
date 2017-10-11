<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

$starttime  = $_GET['starttime']!="" ? $_GET['starttime'] : date("Y-m-d",time()-7*24*60*60);
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time());

$Sql = "SELECT `brandname`,SUM(`hadsend`) `totalcount`,SUM(`hadsend`*d.`price`) `totalprice` FROM `ntssi_order_table` t";
$Sql .= " JOIN `ntssi_order_detail` d ON t.`order_id` = d.`order_id`";
$Sql .= " JOIN `ntssi_goods` g ON d.`gid` = g.`gid`";
$Sql .= " JOIN `ntssi_brand` b ON g.`brand_id` = b.`brand_id`";
$Sql .= " WHERE FROM_UNIXTIME(`createtime`,'%y-%m-%d') BETWEEN DATE_FORMAT('".$starttime."','%y-%m-%d') AND DATE_FORMAT('".$endtime."','%y-%m-%d') AND pay_state=1 AND order_state=4";
$Sql .= " GROUP BY b.`brand_id`";

if( isset($_GET['type']) ){
	if( $_GET['type'] == 0 )
		$Sql .=	" ORDER BY totalprice DESC";
	else if( $_GET['type'] == 1 )
		$Sql .=	" ORDER BY totalcount DESC";
}
else{
	$Sql .=	" ORDER BY totalprice DESC";
}

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$Results_count = 0;
$Results;
$Total = 0;

if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		$Field['index'] = ($Results_count + 1);
		$Field['brandname'] = $value['brandname'];
		
		$Field['totalprice'] = $value['totalprice'];
		$Field['totalcount'] = $value['totalcount'];
		
		$Total += $value['totalprice'];
		
		$Results[$Results_count] = $Field;
		$Results_count++;
	}	
}

$content = ",".$INFO['company_name']."\n\n";

$content .= ",".$_GET['starttime']."~".$_GET['endtime']."\n\n";
$content .= "排名,品牌名稱,(品牌銷售金額 / 銷售總金額),銷售金額,數量\n";

for($i=0; $i<$Results_count; $i++){
	$compare = floor(($Results[$i]['totalprice']/$Total) * 100);
	$content .= $Results[$i]['index'].",".$Results[$i]['brandname'].",".$compare."%,".floor($Results[$i]['totalprice']).",".$Results[$i]['totalcount']."\n";
}

$content .= "\n\n銷售總金額：".$Total;

header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename='".date("Y-m-d",time()).".csv'");

$content = mb_convert_encoding($content , "Big5" , "UTF-8");
echo $content;
exit;

?>
