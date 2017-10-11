<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include( dirname(__FILE__)."/../../configs.inc.php");
include( RootDocument."/".Classes."/global.php");
$advid = intval($_GET['advid']);
if ($advid>0){
	$Sql = " select adv_id,adv_left_url,adv_right_url,adv_width,adv_height,adv_content,point_num,adv_left_img,adv_right_img,adv_tag from `{$INFO[DBPrefix]}advertising` where adv_display = 1 and adv_id='" . $advid . "' and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "') limit 0,1";
$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result = $DB->fetch_array($Query);
		$DB->query("update `{$INFO[DBPrefix]}advertising` set click_count=click_count+1 where adv_id=".intval($advid));  
		echo "<script>location.href='" . $_GET['url'] . "';</script>";
	}
}
echo "error!";
?>
