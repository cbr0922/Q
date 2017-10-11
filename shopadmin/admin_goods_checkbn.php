<?php
include_once "Check_Admin.php";

$gid = intval($_GET['gid']);
$bn = $_GET['bn'];

$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid!='".$gid."' and bn='" . $bn . "' limit 0,1");
$Num   = $DB->num_rows($Query);
if ($Num){
	echo "0";
	exit;
}

/*$Query = $DB->query("select * from `{$INFO[DBPrefix]}attributeno` where goodsno='" . $bn . "' limit 0,1");
$Num   = $DB->num_rows($Query);
if ($Num){
	echo "0";
	exit;
}

$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods_detail` where detail_bn='" . $bn . "' limit 0,1");
$Num   = $DB->num_rows($Query);
if ($Num){
	echo "0";
	exit;
}*/

echo "1";
exit;
?>
