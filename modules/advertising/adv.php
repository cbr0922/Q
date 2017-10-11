<?php
error_reporting(7);

include( dirname(__FILE__)."/../../configs.inc.php");
include( RootDocument."/".Classes."/global.php");
$advtag = trim($_GET['tag']);
$Sql = " select adv_id,adv_left_url,adv_right_url,adv_width,adv_height,adv_content,point_num,adv_left_img,adv_right_img,adv_tag from `{$INFO[DBPrefix]}advertising` where adv_display = 1 and adv_type=3 and adv_tag='" . $advtag . "' and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "') order by rand() limit 0,1";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$Result = $DB->fetch_array($Query);
$Adv =  str_replace("'","\\'",$Result['adv_content']);
$Adv =  str_replace("\r","",$Adv);
$Adv =  str_replace("\n","",$Adv);
if ($Result['adv_left_url']!="")
	$Adv = "<a href=\"" . $INFO['site_url'] ."/modules/advertising/clickadv.php?advid=" .$Result['adv_id']  . "&url=" .urlencode($Result['adv_left_url'])  . "\">".$Adv."</a>";
$adv_id       =  $Result['adv_id'];
$DB->query("update `{$INFO[DBPrefix]}advertising` set point_num=point_num+1 where adv_id=".intval($adv_id));  
echo $output = "document.write('" . $Adv . "')";
/*
header( 'Content-Type: text/javascript' );
header( 'Content-Disposition: attachment;filename='.basename( __FILE__ ) );
$fp = fopen('php://output', 'w');
fputs( $output );
fclose($fp);
*/
?>
