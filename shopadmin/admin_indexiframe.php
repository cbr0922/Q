<?php
include_once "Check_Admin.php";
$indexIframeSQL   = "select adv_tag,adv_title,adv_content from `{$INFO[DBPrefix]}advertising` where adv_display=1 AND adv_type=3 and adv_tag='".trim($_GET[adv_tag])."' order by adv_id desc limit 0,1";
$indexIframeQuery = $DB->query($indexIframeSQL);
while ($indexIframeRs = $DB->fetch_array($indexIframeQuery)){
	echo $indexIframeRs[adv_content];
}
?>