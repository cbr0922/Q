<?php
error_reporting(7);
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
header('Content-Type: text/x-json; charset=utf-8"');

include (dirname(__FILE__)."/configs.inc.php");
include ("global.php");

$areaname = $_GET['areaname'];
$level = $_GET['level'];
switch($level){
	case 0:
		$name = "county";
		break;
	case 1:
		$name = "province";
		break;
	case 2:
		$name = "city";
		break;
}
if($areaname=="")
	$top_id = 0;
else{
	$gResult = $DB->query("select * from `{$INFO[DBPrefix]}area` where areaname='" . $areaname . "'");
	$gRow =  $DB->fetch_array($gResult);
	$top_id = $gRow['area_id'];
}
if($level>0 && $top_id==0){
	echo 0;
	exit;
}
echo "<option value=''>請選擇</option>";
$gResult = $DB->query("select * from `{$INFO[DBPrefix]}area` where top_id='" . $top_id . "'");
$num_row = $DB->num_rows($gResult);
if($num_row>0){
	while ($gRow =  $DB->fetch_array($gResult)){
		echo "<option name='" . $gRow['areaname'] . "' ";
		if($gRow['areaname'] == $_GET['sel'])
			echo "selected";
		echo ">" . $gRow['areaname'] . "</option>";
	}
}else{
	echo 0;	
}
?>