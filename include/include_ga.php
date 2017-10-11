<?php
/*** 处理前台页面底部信息的程序片段 ***/

error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include( RootDocument."/".Classes."/global.php");


/* Google Analytics */
$track_id = '1';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){
	if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
		$track_Js = "
		<!------------------ google-analytics  Start --------------------------------------------------->
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){

  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new
Date();a=s.createElement(o),


m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a
,m)


})(window,document,'script','https://www.google-analytics.com/analytics.js',
'ga');


			ga('create', '" . $track_array[trackcode] . "', 'auto');
			ga('send', 'pageview');
		</script>
		<!------------------ google-analytics  End  --------------------------------------------------->
		";
  }
  else $track_Js =" ";
  $tpl->assign("googleAnalytics_track",   $track_Js);      
}

/*Facebook像素*/
$track_id = '4';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){
	if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
		$track_Js ="<!-- Facebook Pixel Code -->
	<script>
		!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
		n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
		document,'script','//connect.facebook.net/en_US/fbevents.js');
		fbq('init', '" . $track_array[trackcode] . "');
		fbq('track', 'PageView');
	</script>
	<noscript><img height='1' width='1' style='display:none'
src='https://www.facebook.com/tr?id=" . $track_array[trackcode] . "&ev=PageView&noscript=1'
/></noscript>
<!-- End Facebook Pixel Code -->";
	}
	else $track_Js =" ";
	$tpl->assign("PageView_track",   $track_Js);
}

/*yahoo追蹤碼*/
$track_id = '15';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){
	if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
		$track_Js =$track_array[trackcode];
	}
	else $track_Js =" ";
	$tpl->assign("yahooUET_track",   $track_Js);
}




$tpl->display("include_ga.html");
?>



